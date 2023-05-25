<?php
namespace PublishPress\Permissions\Statuses\UI\Dashboard;

class PostEdit 
{
    function __construct() {
        // Classic Editor support
        //
        // This script executes on the 'init' action if is_admin() and $pagenow is 'post-new.php' or 'post.php' and the block editor is not active.
        //

        add_action('add_meta_boxes', [$this, 'act_comments_metabox'], 10, 2);
        add_action('add_meta_boxes', [$this, 'act_replace_publish_metabox'], 10, 2);

        add_action('admin_head', [$this, 'act_object_edit_scripts'], 99);  // needs to load after post.js to unbind handlers

        add_action('admin_print_footer_scripts', [$this, 'act_force_visibility_js'], 99);

        if (presspermit_is_REQUEST('message', 6)) {
            add_filter('post_updated_messages', [$this, 'flt_post_updated_messages'], 50);
    	}
    }

    public function post_submit_meta_box($post, $args = [])
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/Dashboard/PostEditSubmitMetabox.php');
        PostEditSubmitMetabox::post_submit_meta_box($post, $args);
    }

    public function act_replace_publish_metabox($post_type, $post)
    {
        global $wp_meta_boxes;

        if (!in_array($post_type, presspermit()->getEnabledPostTypes(), true)) {
            // Still apply Permissions > Post Statuses customizations to status order 
			// and availability based on post type and workflow branch relationships
			require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/PostEdit.php');
            add_filter('pp_custom_status_list', ['PublishPress\Permissions\Statuses\UI\PostEdit', 'flt_publishpress_status_list'], 50, 2);
            return;
        }

        if ('attachment' != $post_type) {
            if (!empty($wp_meta_boxes[$post_type]['side']['core']['submitdiv'])) {
                $wp_meta_boxes[$post_type]['side']['core']['submitdiv']['callback'] = [$this, 'post_submit_meta_box'];
            }
        }
    }

    public function flt_post_updated_messages($messages)
    {
        if (!presspermit()->isContentAdministrator()) {
            if ($type_obj = presspermit()->getTypeObject('post', PWP::findPostType())) {
                if (!current_user_can($type_obj->cap->publish_posts)) {
                    global $post;

                    if ($post) {
                        if ($status_obj = get_post_status_object($post->post_status)) {
                            $messages['post'][6] = esc_attr(sprintf(__('Post set as %s', 'presspermit'), $status_obj->label));
                            $messages['page'][6] = esc_attr(sprintf(__('Page set as %s', 'presspermit'), $status_obj->label));
                        }
                    }
                }
            }
        }

        return $messages;
    }

    public function act_object_edit_scripts()
    {
        global $typenow;

        $stati = [];
        foreach (['public', 'private', 'moderation'] as $prop) {
            foreach (PWP::getPostStatuses([$prop => true, 'post_type' => $typenow], 'object') as $status => $status_obj) {
	            // Safeguard: Fall back on native WP object if our copy was corrupted. 
	            // @todo: confirm this is not needed once Class Editor status caption refresh issues are resolved.
	            if (empty($status_obj->labels->name)) {
	                $status_obj = get_post_status_object($status);
	            }
            
                $stati[$prop][] = [
                    'name' => $status, 
                    'label' => (!empty($status_obj->labels->name)) ? $status_obj->labels->name : $status_obj->label, 
                    'save_as' => isset($status_obj->labels->save_as) ? $status_obj->labels->save_as : ''
                ];
            }
        }

        $draft_obj = get_post_status_object('draft');

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';

        wp_enqueue_script(
            'presspermit-object-edit', 
            PRESSPERMIT_STATUSES_URLPATH . "/common/js/object-edit{$suffix}.js", 
            ['jquery', 'jquery-form'], 
            PRESSPERMIT_STATUSES_VERSION, 
            true
        );

        wp_localize_script('presspermit-object-edit', 'ppObjEdit', [
            'pubStati' => json_encode($stati['public']),
            'pvtStati' => json_encode($stati['private']),
            'modStati' => json_encode($stati['moderation']),
            'draftSaveAs' => $draft_obj->labels->save_as,
            'nowCaption' => esc_html__('Current Time', 'presspermit-pro'),
            'update' => esc_html__('Update'),
            'schedule' => esc_html__('Schedule'),
            'published' => esc_html__('Published'),
            'privatelyPublished' => esc_html__('Privately Published'),
            'publish' => esc_html__('Publish'),
            'publishSticky' => esc_html__('Published, Sticky')
        ]);

        global $wp_scripts;
        $wp_scripts->in_footer [] = 'presspermit-object-edit';  // otherwise it will not be printed in footer (@todo review)
    }

    // ensure Comments metabox for custom published / private stati
    public function act_comments_metabox($post_type, $post)
    {
        global $wp_meta_boxes;
        if (isset($wp_meta_boxes[$post_type]['normal']['core']['commentsdiv']))
            return;

        if ($post_status_obj = get_post_status_object($post->post_status)) {
            if (('publish' == $post->post_status || 'private' == $post->post_status) 
            && post_type_supports($post_type, 'comments')
            ) {
                add_meta_box('commentsdiv', PWP::__wp('Comments'), 'post_comment_meta_box', $post_type, 'normal', 'core');
            }
        }
    }

    public function act_force_visibility_js()
    {
        global $post;

        if (empty($post) || $post->post_password)
            return;

        $current_status_obj = get_post_status_object($post->post_status);
        $attribute_defs = PPS::attributes();

        $_args = is_post_type_hierarchical($post->post_type) ? ['id' => $post->ID] : ['default_only' => true];
        $_args['post_type'] = $post->post_type;

        // causes PPCE filter to return object indicating if forced status is due to a "Subpage visibility" setting or a forced default privacy
        $_args['return_meta'] = true;

        if (!$force = $attribute_defs->getItemCondition('post', 'force_visibility', $_args))
            return;

        if (!is_object($force)) {
            $force = (object)['force_status' => $force, 'force_basis' => 'direct'];
        }

        if ('publish' == $force->force_status)
            $status_label = esc_html__('Public');
        else {
            $force_status_object = get_post_status_object($force->force_status);
            $status_label = $force_status_object->label;
        }

        $post_type_object = get_post_type_object($post->post_type);

        $force_caption = sprintf(__('Visibility forced to %1$s', 'presspermit-pro'), $status_label, $post_type_object->labels->singular_name);

        $vis = ('publish' == $force->force_status) ? 'public' : $force->force_status;

// @todo: move to .js
        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            jQuery(document).ready(function ($) {
                <?php if ( $current_status_obj->public || $current_status_obj->private ) : ?>
                $('#visibility-radio-<?php echo esc_attr($vis); ?>').prop('checked', true);
                $('#post-visibility-display').html($('#visibility-radio-<?php echo esc_attr($vis); ?>').next('label').html());
                updateStatusDropdownElements();
                updateStatusCaptions();
                <?php endif;?>

                $('input[name="visibility"][value!="<?php echo esc_attr($vis)?>"][value!="public"][value!="password"]').prop('disabled', true).siblings('label').addBack().attr('title', '<?php echo esc_attr($force_caption); ?>');
                $('input[name="visibility"][value="public"]').siblings('label').attr('title', '<?php echo esc_attr($force_caption); ?>'); <?php /* can't disable public option because it's needed to select unpublished stati, but do alter its title */ ?>

                <?php if ( 'default' != $force->force_basis ) :  /* if the visibility forcing stems from the default privacy setting for the post type, still allow subpages to be custom-forced to a different privacy */?>
                $('input[name="ch_visibility"][value!="<?php echo esc_attr($vis);?>"]').prop('disabled', true).siblings('label').addBack().attr('title', '<?php echo esc_attr($force_caption); ?>');
                <?php endif; ?>
            });
            /* ]]> */
        </script>
        <?php
    } // end function act_force_visibility_js

}
