<?php
namespace PublishPress\Permissions\FileAccess;

class FileFilters
{
    public static function userCanReadFile($file, &$return_attachment_id, &$matched_published_post, $uploads = '')
    {
        global $wpdb, $current_user;

        $pp = presspermit();

        // don't filter the direct file URL request if filtering is disabled, or if the request is from wp-admin
        if (!presspermit()->filteringEnabled()) {
            return true;
        }

        $user = presspermit()->getUser();

        $results = self::getFilePosts($file, compact('uploads'));

        $matched_published_post = [];
        $return_attachment_id = 0;

        $listed_post_tt_ids = [];

        $can_read = $pp->isContentAdministrator();

        // optional wp-admin perf enhancement for logged Authors / Editors: no file filtering queries
        if (!$can_read && defined('PP_NO_ADMIN_FILE_FILTERING') && !empty($current_user) && !empty($current_user->allcaps['upload_files'])) {
            if (!empty($_SERVER['HTTP_REFERER']) && (false !== strpos(esc_url_raw($_SERVER['HTTP_REFERER']), get_option('siteurl') . '/wp-admin'))) {
                $can_read = true;
            }
        }

        if (empty($results)) {
            $type_obj = get_post_type_object('attachment');
            return $can_read || !empty($current_user->allcaps[$type_obj->cap->edit_others_posts]);
        } else {
            $media_filtered = in_array('attachment', $pp->getEnabledPostTypes(), true);

            $post_ids = [];
            foreach ($results as $attachment) {
                $post_ids []= $attachment->ID;
            }
            $post_id_csv = implode("','", $post_ids);

            foreach ($results as $attachment) {
                // If multiple attachment records matched the filename but the current result has no key stored, continue to next result 
                if (count($results) > 1) {
                    if (!isset($result_num)) $result_num = 0;
                    $result_num++;

                    if ($result_num < count($results) && !$key = get_post_meta($attachment->ID, '_rs_file_key')) {
                        continue;
                    }
                }

                if ($attachment->post_parent && get_post($attachment->post_parent)) {
                    if ($parent_post = $wpdb->get_row(
                        $wpdb->prepare(
                            "SELECT post_type, post_status FROM $wpdb->posts WHERE ID = %d LIMIT 1",
                            $attachment->post_parent
                        )
                    )) {
                        $object_type = $parent_post->post_type;
                        $containing_post_status = $parent_post->post_status;

                        // Only return content that is attached to published (potentially including private) posts/pages
                        // If some other statuses for published posts are introduced in later WP versions, 
                        // the failure mode here will be to overly suppress attachments
                        $status_obj = get_post_status_object($containing_post_status);

                        if (empty($status_obj) || ($status_obj->internal && ('trash' != $containing_post_status))) {  // allow for preview viewing when parent post is unpublished but editable, viewing attachments to trashed posts if they have exceptions directly assigned
                            continue;
                        }

                        $check_id = $attachment->post_parent;

                        // exceptions assigned directly for file
                        if ($media_filtered && !$can_read) {
                            $user->retrieveExceptions('read', 'post', ['post_types' => 'attachment', 'via_item_source' => 'post', 'item_id' => $attachment->ID, 'assign_for' => 'item']);

                            if (! empty($user->except['read_post']['post']['']['additional']['attachment'])) {
                                foreach (['include', 'exclude', 'additional'] as $mod) {
                                    if (isset($user->except['read_post']['post'][''][$mod]['attachment']['']) && in_array($attachment->ID, $user->except['read_post']['post'][''][$mod]['attachment'][''])) {
                                        $check_id = $attachment->ID;
                                        break;
                                    }
                                }
                            } else {
                                // exceptions assigned for a term which the file has assigned to it
                                $user->retrieveExceptions('read', 'post', ['post_types' => 'attachment', 'via_item_source' => 'term', 'assign_for' => 'item']);
                                
                                if (!empty($user->except['read_post']['term'])) {
                                    foreach(array_keys($user->except['read_post']['term']) as $taxonomy ) {
                                        if (!empty($user->except['read_post']['term'][$taxonomy]['additional']['attachment'][''])) {
                                            if (!isset($listed_post_tt_ids[$taxonomy])) {
                                                $results = $wpdb->get_results("SELECT object_id, term_taxonomy_id FROM $wpdb->term_relationships WHERE object_id IN ('$post_id_csv')");
                                                foreach($results as $row) {
                                                    $listed_post_tt_ids[$taxonomy][$row->object_id][] = $row->term_taxonomy_id;
                                                }
                                            }

                                            if (
                                                !empty($listed_post_tt_ids[$taxonomy][$row->object_id]) 
                                                && array_intersect(
                                                    $listed_post_tt_ids[$taxonomy][$row->object_id], 
                                                    $user->except['read_post']['term'][$taxonomy]['additional']['attachment']['']
                                                )
                                            ) {
                                                $check_id = $attachment->ID;
                                                break;
                                            }
                                        }
                                    }
                                }
                            }

                            $user->except = [];
                        }

                        $teaser_passthrough = false;

                        // Teaser: pass through file under some conditions
                        if (defined('PRESSPERMIT_TEASER_VERSION') && !$pp->getOption('teaser_hide_thumbnail') && !defined('PPTX_DISABLE_THUMB_PASSTHRU')) {
                            if ($thumbnail_post_ids = $wpdb->get_col(
                                $wpdb->prepare(
                                    "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
                                    $attachment->ID
                                )
                            )) {
                                $teased_post_types = apply_filters('presspermit_teased_post_types', []);

                                $teased_types_csv = implode("','", array_map('sanitize_key', $teased_post_types));
                                $thumbnail_ids_csv = implode("','", array_map('intval', $thumbnail_post_ids));

                                if ($thumbnail_posts = $wpdb->get_results("SELECT DISTINCT post_type, post_status FROM $wpdb->posts WHERE post_type IN ('$teased_types_csv') AND ID IN ('$thumbnail_ids_csv')")) {
                                    $valid_stati = get_post_stati(['public' => true], 'names');

                                    // also account for posts/pages which are teased due to private visibility (but not if all relevant private posts/pages are hidden)
                                    if ($pp->getOption('teaser_hide_custom_private_only')) {
                                        $valid_stati[] = 'private';
                                    }

                                    $private_stati = get_post_stati(['private' => true], 'names');

                                    foreach ($thumbnail_posts as $_post) {
                                        if (in_array($_post->post_status, $valid_stati, true) || (in_array($_post->post_status, $private_stati, true) && !$pp->getTypeOption('tease_public_posts_only', $_post->post_type))) {
                                            $teaser_passthrough = true;
                                            break;
                                        }
                                    }
                                }
                            }

                            // don't pass through files which are explicitly blocked by exceptions (NOTE: this does not account for term-assigned exceptions)
                            if ($teaser_passthrough && isset($user->except['read_post']['post'][''])) {
                                if (
                                    (
                                        (isset($user->except['read_post']['post']['']['exclude']['attachment']['']) && in_array($attachment->ID, $user->except['read_post']['post']['']['exclude']['attachment']['']))
                                        ||
                                        (isset($user->except['read_post']['post']['']['include']['attachment']['']) && !in_array($attachment->ID, $user->except['read_post']['post']['']['include']['attachment']['']))
                                    )
                                    && (!isset($user->except['read_post']['post']['']['additional']['attachment']['']) || !in_array($attachment->ID, $user->except['read_post']['post']['']['additional']['attachment']['']))
                                ) {
                                    $teaser_passthrough = false;
                                }
                            }
                        }

                        if ($can_read || $teaser_passthrough || current_user_can("read_post", $check_id) || current_user_can("edit_post", $check_id)) {  // edit_post allowance is for preview viewing when parent post is unpublished but editable
                            $return_attachment_id = $attachment->ID;
                            break;
                        } else {
                            $matched_published_post[$object_type] = $attachment->post_name;
                        }
                    }
                } else {
                    if ($can_read || current_user_can("read_post", $attachment->ID)) {
                        $return_attachment_id = $attachment->ID;
                        break;
                    } else {
                        $matched_published_post['attachment'] = $attachment->post_name;
                    }
                }
            }
        }

        if ($attachment && !$matched_published_post) {
           $matched_published_post['attachment'] = $attachment->post_name;
        }

        return !empty($return_attachment_id);
    }

    // handle access to uploaded file where request was a direct file URL, which was rewritten according to our .htaccess addition
    public static function parseQueryForDirectAccess(&$query)
    {
        $file = $query->query_vars['attachment'];

        $uploads = FileAccess::getUploadInfo();

        $return_attachment_id = 0;
        $matched_published_post = [];
        if (self::userCanReadFile($file, $return_attachment_id, $matched_published_post, $uploads)) {
            self::returnFile($file, $return_attachment_id);
            return;
        }

        require_once(PRESSPERMIT_FILEACCESS_CLASSPATH . '/FileDenial.php');
        FileDenial::response404($file, $matched_published_post, $uploads, $return_attachment_id);
    }

    private static function getFilePostIds($file, $args = [])
    {
        $ids = [];

        if ($results = self::getFilePosts($file, $args)) {
            foreach ($results as $row)
                $ids [] = $row->ID;
        }

        return $ids;
    }

    private static function getFilePosts($file, $args = [])
    {
        $defaults = ['uploads' => false];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        global $wpdb;

        if (!is_array($uploads) || empty($uploads['basedir'])) {
            $uploads = FileAccess::getUploadInfo();
        }

        // auto-resized copies have -NNNxNNN suffix, but the base filename is stored as attachment.  Strip the suffix out for db query.
        $orig_file = preg_replace("/-[0-9]{2,4}x[0-9]{2,4}\./", '.', $file);

        // manually resized copies have -eNNNNNNNNNNNNN suffix, but the base filename is stored as attachment.  Strip the suffix out for db query.
        $orig_file = preg_replace("/-e[0-9]{13}\./", '.', $orig_file);

        $orig_file_url = $uploads['baseurl'] . "/$orig_file";

        $rewrite_base = $uploads['baseurl'];
        if (defined('PP_FILE_ROOT')) {
            $pos = strpos($rewrite_base, PP_FILE_ROOT);
            if ($pos) {
                $rewrite_base = substr($rewrite_base, $pos);
            }
        }

        $relative_path = str_replace(trailingslashit($rewrite_base), '', $orig_file_url);

        $post_ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s", $relative_path));
        $id_csv = ($post_ids) ? implode("','", array_map('intval', $post_ids)) : '';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT ID, post_parent, post_name FROM $wpdb->posts WHERE post_type = 'attachment' AND guid = %s OR ID IN ('$id_csv') ORDER BY post_modified_gmt DESC", 
                $orig_file_url
            ), 
            OBJECT_K
        );

        return $results;
    }

    private static function returnFile($file_path, $attachment_id = 0)
    {
        $uploads = FileAccess::getUploadInfo();

        if (false === strpos($file_path, $uploads['basedir']))
            $file_path = untrailingslashit($uploads['basedir']) . "/$file_path";

        $file_url = str_replace(untrailingslashit($uploads['basedir']), untrailingslashit($uploads['baseurl']), $file_path);

        if (!$attachment_id) {
            global $wpdb;  // we've already confirmed that this user can read the file; if it is attached to more than one post any corresponding file key will do

            // Resized copies have -NNNxNNN suffix, but the base filename is stored as attachment.  Strip the suffix out for db query.
            $orig_file_url = preg_replace("/-[0-9]{2,4}x[0-9]{2,4}./", '.', $file_url);

            if (!$attachment_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ID FROM $wpdb->posts WHERE post_type = 'attachment' AND guid = %s AND post_parent > 0 LIMIT 1",
                    $orig_file_url
                )
            )) {
                return;
            }
        }

        if (!$key = get_post_meta($attachment_id, '_rs_file_key')) {
            // The key was lost from DB, so regenerate it (and files / uploads .htaccess)
            require_once(PRESSPERMIT_FILEACCESS_CLASSPATH . '/RewriteRules.php');
            RewriteRules::resyncFileRules();

            // If the key is still not available, fail out to avoid recursion
            if (!$key = get_post_meta($attachment_id, '_rs_file_key')) {
                exit(0);
            }
        } elseif (!empty($_SERVER['REQUEST_URI']) && strpos(esc_url_raw($_SERVER['REQUEST_URI']), 'rs_file_key')) {
            // Apparantly, the .htaccess rules contain an entry for this file, but with invalid file key.  URL with this valid key already passed through RewriteRules.  
            // Regenerate .htaccess file in uploads folder, but don't risk recursion by redirecting again.  Note that Firefox browser cache may need to be cleared following this error.
            $last_resync = get_option('presspermit_last_htaccess_resync');
            if ((!$last_resync) || (time() - $last_resync > 3600)) {  // prevent abuse (mismatched .htaccess keys should not be a frequent occurance)
                update_option('presspermit_last_htaccess_resync', time());
                require_once(PRESSPERMIT_FILEACCESS_CLASSPATH . '/RewriteRules.php');
                RewriteRules::resyncFileRules();
            }
            exit(0);  // If htaccess rewrite was instantaneous, we could just continue without this exit.  But settle for the one-time image access failure to avoid a redirect loop on delayed file update.
        }

        if (is_array($key))
            $key = reset($key);

        if (is_multisite() && get_site_option('ms_files_rewriting')) {
            $basedir = parse_url($uploads['basedir']);
            $baseurl = parse_url($uploads['baseurl']);

            global $base;

            $file_url = str_replace(ABSPATH, $baseurl['scheme'] . '://' . $baseurl['host'] . $base, $file_path);
            $file_url = str_replace('\\', '/', $file_url);
        }

        $redirect = $file_url . "?rs_file_key=$key";

        usleep(10);
        wp_redirect($redirect);
        exit(0);
    }
}
