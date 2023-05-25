<?php
namespace PublishPress\Permissions\Statuses;

class PostSave
{
    public static function fltPostStatus($post_status)
    {
        global $current_user;

        $post_id = PWP::getPostID();

        if ($_post = get_post($post_id)) {
            $type_obj = get_post_type_object($_post->post_type);
        }

        if ('_pending' == $post_status) {
            $save_as_pending = true;
            $post_status = 'pending';
            
        } elseif (('pending' == $post_status) && !empty($type_obj) && current_user_can($type_obj->cap->publish_posts)) {
            $save_as_pending = true;
        }

        $status_obj = get_post_status_object($post_status);

        if (!empty($status_obj->public) || !empty($status_obj->private)) {
            if (!$selected_privacy = get_transient("_pp_selected_privacy_{$current_user->ID}_{$post_id}")) {
                $selected_privacy = get_transient("_pp_selected_privacy_{$post_id}");
            }

            if ($selected_privacy) {
                if ($status_obj = get_post_status_object($selected_privacy)) {
                    $post_status = $selected_privacy;
                }

                delete_transient("_pp_selected_privacy_{$current_user->ID}_{$post_id}");
                delete_transient("_pp_selected_privacy_{$post_id}");
            }
        }

        if (defined('PUBLISHPRESS_REVISIONS_VERSION') && in_array($post_status, rvy_revision_base_statuses())) {
            if ($mime_type = get_post_field('post_mime_type', $post_id)) {
                if (in_array($mime_type, rvy_revision_statuses())) {
                    return $post_status;
                }
            }
        } elseif (defined('REVISIONARY_VERSION') && (
            in_array($post_status,['pending-revision', 'future-revision']))
            || (!presspermit_empty_POST() && presspermit_is_REQUEST('page', 'rvy-revisions')
        )) {
            return $post_status;
        }

        $is_administrator = presspermit()->isContentAdministrator();

        if ($stored_status = get_post_field('post_status', $post_id)) {
            $stored_status_obj = get_post_status_object($stored_status);
        }

        $_post_status = presspermit_POST_key('post_status');

        $selected_status = ($_post_status && ('publish' != $_post_status)) ? $_post_status : $post_status;

        if (('publish' == $selected_status) && !presspermit_empty_POST('visibility')) {
            $selected_status = presspermit_POST_key('visibility');
        }

        if ('public' == $selected_status) {
            $selected_status = 'publish';
        }

        // inline edit: apply keep_status checkbox selection
        if ($_post && presspermit_is_POST('action', 'inline-save')) {
            foreach (PWP::getPostStatuses(['private' => true, 'post_type' => $_post->post_type]) as $_status) {
                if (!presspermit_empty_POST("keep_{$_status}")) {
                    $selected_status = $_status;
                    break;
                }
            }
        }

        if (!$post_status_obj = get_post_status_object($selected_status)) {
            return $post_status;
        }

        // Important: if other plugin code inserts additional posts in response, don't filter those
        static $done;
        if (!empty($done)) return $post_status;  
        $done = true;

        $post_status = $selected_status;

        $_post = get_post($post_id);

        // Scheduled Post handling (Classic Editor)  @todo: Gutenberg
        if (!defined('REST_REQUEST')) {
            if (!empty($post_status_obj->private)) {
                $_POST['post_password'] = '';

                if (presspermit_is_POST('sticky')) {
                    unset($_POST['sticky']);
                }
            }

            if ($post_status_obj->public || $post_status_obj->private) {
                if ($post_date_gmt = presspermit_POST_var('post_date_gmt')) {
                    $post_date_gmt = pp_permissions_sanitize_entry($post_date_gmt);  // local variable is only used for comparison to current time
                } elseif (!presspermit_empty_POST('aa')) {
                    foreach (['aa' => 'Y', 'mm' => 'n', 'jj' => 'j', 'hh' => '', 'mn' => '', 'ss' => ''] as $var => $format) {
                        $$var = presspermit_POST_var($var);
                        $$var = (!$format || $$var > 0) ? pp_permissions_sanitize_entry($$var) : date($format);
                    }
                    $post_date = sprintf("%04d-%02d-%02d %02d:%02d:%02d", $aa, $mm, min($jj, 31), min($hh, 23), min($mn, 59), 0);
                    $post_date_gmt = get_gmt_from_date($post_date);
                }

                // set status to future if a future date was selected with a private status
                $now = gmdate('Y-m-d H:i:59');
                if (!empty($post_date_gmt) && mysql2date('U', $post_date_gmt, false) > mysql2date('U', $now, false)) {
                    update_post_meta($post_id, '_scheduled_status', $post_status);
                    $post_status = 'future';
                } else {
                    // if a post is being transitioned from scheduled to published/private, apply scheduled status
                    if ($_post) {
	                    if ('future' == $_post->post_status) {  // stored status is future
	                        if ($_status = get_post_meta($post_id, '_scheduled_status', true)) {
	                            $post_status = $_status;
	                            $post_status_obj = get_post_status_object($post_status);
	                        }
	
	                        delete_post_meta($post_id, '_scheduled_status');
	                    }
	                }
	            }
            }
        }

        if (empty($_post)) {
            return $post_status;
        }

        // Allow Publish / Submit button to trigger our desired workflow progression instead of Publish / Pending status.
        // Apply this change only if stored post is not already published or scheduled.
        // Also skip retain normal WP editor behavior if the newly posted status is privately published or future.
        if (
            (('pending' == $selected_status) && !in_array($stored_status, ['publish', 'private', 'future']) 
            && empty($stored_status_obj->public) && empty($stored_status_obj->private))
            ||
            (empty($doing_rest) && ('publish' == $selected_status))
        ) {
            // Gutenberg REST gives no way to distinguish between Publish and Save request. Treat as Publish (next workflow progression) if any of the following:
            //  * user cannot set pending status
            //  * already set to pending status

            $doing_rest = defined('REST_REQUEST') && (!presspermit_empty_REQUEST('meta-box-loader') || presspermit()->doingREST());

            if (
                empty($save_as_pending) /* Pending status was not explicitly selected by dropdown / checkbox */
                || ! defined('REST_REQUEST')
                || ! presspermit()->doingREST()
                || (('publish' == $selected_status) && !PPS::haveStatusPermission('set_status', $_post->post_type, 'pending')) 
            ) {
                // Users who have publish capability do not need defaultStatusProgression() functionality, so do not get the status="_pending" dropdown option to indicate an explicit Save as Pending request
                if ('pending' == $selected_status) {
                    $type_obj = get_post_type_object($_post->post_type);
                    $can_publish = ($type_obj) ? !empty($current_user->allcaps[$type_obj->cap->publish_posts]) : false;
                }

                if (empty($save_as_pending) && ($doing_rest && ($selected_status != $stored_status || (('pending' == $selected_status) && !$can_publish))) || (!presspermit_empty_POST() && !presspermit_empty_POST('publish'))) { //} && ! empty( $_POST['pp_submission_status'] ) ) { 
                    $post_type = ($post_id) ? '' : PWP::findPostType();

                    if ('private' != get_post_field('post_status', $post_id)) {
                    	// Submission status inferred using same logic as UI generation (including permission check)
                    	$post_status = PPS::defaultStatusProgression($post_id, ['return' => 'name', 'post_type' => $post_type]);
                    }
                }

                $filtered_status = apply_filters('presspermit_selected_moderation_status', $post_status, $post_id);

                if (($filtered_status != $post_status) 
                && PPS::haveStatusPermission('set_status', $_post->post_type, $filtered_status)
                ) {
                    $post_status = $filtered_status;
                }
            }

        } elseif(in_array($post_status, ['publish', 'private'])) {
            if ( $default_privacy = presspermit()->getTypeOption('default_privacy', $_post->post_type)) {
                if (get_post_status_object($default_privacy)) {                    
                    if ( $stored_status = get_post_meta($post_id, '_pp_original_status') ) {
                        $stored_status_obj = get_post_status_object($stored_status);
                    }

                    if (empty($stored_status_obj) || (empty($stored_status_obj->public) && empty($stored_status_obj->private))) {
                        $post_status = $default_privacy;

                        delete_post_meta($post_id, '_pp_original_status');
                    }
                }
            }

            // Final permission check to cover all other custom statuses (draft, publish and private status capabilities are already checked by WP)
        } elseif (!empty($_post) && !$is_administrator && ($post_status != $stored_status) 
        && !in_array($post_status, ['draft', 'publish', 'private']) 
        && !PPS::haveStatusPermission('set_status', $_post->post_type, $post_status)
        ) {
            
            $post_status = ($stored_status) ? $stored_status : 'draft';
        }

        return $post_status;
    }

    // If a public or private status is selected, change it to the specified force_visibility status
    public static function flt_force_visibility($status)
    {
        if (!$status_obj = get_post_status_object($status))
            return $status;

        static $done;                          // @todo: review (3/2019)
        if (!empty($done)) return $status;  // Important: if other plugin code inserts additional posts in response, don't filter those
        $done = true;

        if ($status_obj->public || $status_obj->private) {
            if (!$post_id = PWP::getPostID()) {
                return $status;
            }
            
            $_post = get_post($post_id);

            if (presspermit_empty_POST() || empty($_post) || !is_object($_post)) {
                return $status;
            }

            if (!presspermit_empty_POST()) {
                if (!presspermit_empty_POST('post_password')) {
                    return $status;
                }
            } elseif ($_post && $_post->post_password) {
                return $status;
            }

            if ($forced_default = presspermit()->getTypeOption('default_privacy', $_post->post_type)) {
                if (presspermit()->getTypeOption('force_default_privacy', $_post->post_type) || PWP::isBlockEditorActive($_post->post_type)) {
                    return $forced_default;
                }
            }

            if ($is_hierarchical = is_post_type_hierarchical($_post->post_type)) {
                // Since force_visibility is always a propagating condition and the parent setting may be in flux too, 
                // check setting for parent instead of post
                if (!presspermit_empty_POST() && presspermit_is_POST('parent_id')) {
                    $parent_id = apply_filters('pre_post_parent', presspermit_POST_int('parent_id'));
                } elseif ($_post) {
                    $parent_id = $_post->post_parent;
                }
            }

            if (!$is_hierarchical || !empty($parent_id)) {
                // also poll force_visibility for non-hierarchical types to support PPCE forcing default visibility
                $attributes = PPS::attributes();

                $_args = ($is_hierarchical) 
                ? ['id' => $parent_id, 'assign_for' => 'children'] 
                : ['default_only' => true, 'post_type' => $_post->post_type];
                
                if ($force_status = $attributes->getItemCondition('post', 'force_visibility', $_args)) {
                    $status = $force_status;
                }
            }
        }

        return $status;
    }

    // called by AdminFilters::mnt_save_object
    // This handler is meant to fire whenever an object is inserted or updated.
    public static function actSavePost($post_id, $object = '')
    {
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            || (presspermit_is_REQUEST('action', 'untrash'))
            || ('revision' == $object->post_type)  // operations in this function do not apply to revision save
        ) {
            return;
        }

        if (defined('REVISIONARY_VERSION')) {
            global $revisionary;
            if (!empty($revisionary->admin->revision_save_in_progress)) {
                $revisionary->admin->revision_save_in_progress = false;
                return;
            }
        }

        if (is_post_type_hierarchical($object->post_type)) {
            $set_subpost_visibility = false;
            
            if (presspermit()->doingREST()) {
                $rest = \PublishPress\Permissions\REST::instance();
                $set_subpost_visibility = isset($rest->params['pp_subpost_visibility']) ? $rest->params['pp_subpost_visibility'] : false;
            } elseif (presspermit_empty_POST() || PWP::isBlockEditorActive($object->post_type)) {
                return;
            } else {
                $set_subpost_visibility = isset($_POST['ch_visibility']) ? sanitize_key($_POST['ch_visibility']) : false;
            }

            if (false !== $set_subpost_visibility) {
                $set_subpost_visibility = sanitize_key($set_subpost_visibility);
                
                require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/ItemSave.php');
                ItemSave::post_update_force_visibility($object, ['children' => $set_subpost_visibility]);
            }
        }
    }
}
