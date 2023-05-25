<?php
namespace PublishPress\Permissions\Statuses\UI\Dashboard;

class PostAdmin
{
    function __construct() {
        global $pagenow;

        // This script executes on the 'init' action if is_admin() and $pagenow is 'post-new.php' or 'post.php'
        //
        // It is also loaded for static calls to set_status_labels() within edit.php and AJAX requests with action 'inline-save'
        //
        // Note that the set_status_labels() method remains active with Gutenberg.

        if ((!defined('DOING_AJAX') || !DOING_AJAX) && (!in_array($pagenow, ['post.php', 'post-new.php']) || !PWP::isBlockEditorActive())) {
            add_action('admin_print_footer_scripts', [$this, 'act_supplement_js_captions'], 99);

            if (defined('PUBLISHPRESS_VERSION') && defined('PRESSPERMIT_COLLAB_VERSION')) {
                add_action('admin_enqueue_scripts', [$this, 'act_publishpress_compat'], 50);
                add_action('admin_notices', [$this, 'act_publishpress_restore_status_display'], 50);
            }
        }
    }

    public function act_publishpress_compat()
    {
        global $post;

        if (class_exists('PP_Custom_Status')) {
            $post_type = (!empty($post)) ? $post->post_type : get_post_field('post_type', PWP::getPostID());

            if (in_array($post_type, presspermit()->getEnabledPostTypes(), true)) {
                wp_dequeue_script('publishpress-custom_status');
                wp_dequeue_style('publishpress-custom_status');
            }
        }
    }

    public function act_publishpress_restore_status_display()
    {
        ?>
        <style type="text/css">
            /* Restore post status dropdown (PublishPress hides by default) **/
            label[for=post_status],
            #post-status-display,
            #publish {
                display: inline;
            }
        </style>
        <?php
    }

    public function act_supplement_js_captions()
    {
        global $typenow, $wp_scripts;

        ?>
        <script type="text/javascript">
        /* <![CDATA[ */
            var postL10n;

        if (typeof (postL10n) != 'undefined') {
            <?php foreach( 
                array_merge(
                    PWP::getPostStatuses(['public' => true, 'post_type' => $typenow], 'object'),
                    PWP::getPostStatuses(['private' => true, 'post_type' => $typenow], 'object')
                ) as $_status => $_status_obj 
            ) {
                if ( !in_array($_status, ['auto-draft', 'publish']) ) :
                ?>
                postL10n['<?php echo esc_attr($_status); ?>'] = '<?php echo esc_html($_status_obj->labels->visibility); ?>';
                postL10n['<?php echo esc_attr($_status);?>Sticky'] = '<?php printf(esc_html__('%s, Sticky'), esc_html($_status_obj->label)); ?>';
                <?php endif;?>
                <?php
            } // end foreach
            ?>
        }
        /* ]]> */
        </script>
        <?php
    } // end function

    public static function set_status_labels()
    {
        global $wp_post_statuses;

        foreach (array_keys($wp_post_statuses) as $status) {
            if (empty($wp_post_statuses[$status]->labels))
                $wp_post_statuses[$status]->labels = (object)[];
        }

        $wp_post_statuses['publish']->labels->publish = esc_attr(PWP::__wp('Publish'));
        $wp_post_statuses['future']->labels->publish = esc_attr(PWP::__wp('Schedule'));

        if (empty($wp_post_statuses['pending']->labels->publish))
            $wp_post_statuses['pending']->labels->publish = esc_attr(PWP::__wp('Submit for Review'));

        $wp_post_statuses['draft']->labels->save_as = esc_attr(PWP::__wp('Save Draft'));

        if (empty($wp_post_statuses['pending']->labels->caption))
            $wp_post_statuses['pending']->labels->caption = PWP::__wp('Pending Review');

        $wp_post_statuses['private']->labels->caption = PWP::__wp('Privately Published');
        $wp_post_statuses['auto-draft']->labels->caption = PWP::__wp('Draft');


        // ================ Apply stored labels for custom statuses =============
        $custom_stati = array_intersect_key(
            (array)get_option("presspermit_custom_conditions_post_status"), 
            $wp_post_statuses
        );

        foreach ($custom_stati as $status => $status_args) {
            if (!empty($status_args['moderation'])) {
                if (defined('PP_NO_MODERATION'))
                    continue;
            }

            if (!empty($status_args['label'])) {
                $label = $status_args['label'];
            } elseif (!empty($wp_post_statuses[$status]->label)) {
                $label = $wp_post_statuses[$status]->label;
            } else {
                $label = ucwords($status);
            }

            $sing = sprintf(__('%s <span class="count">()</span>', 'presspermit-pro'), $label);
            $plur = sprintf(__('%s <span class="count">()</span>', 'presspermit-pro'), $label);

            $status_args['label_count'] = _n_noop(
                str_replace('()', '(%s)', $sing), 
                str_replace('()', '(%s)', $plur)
            );

            $wp_post_statuses[$status]->label = $label;
            $wp_post_statuses[$status]->labels->caption = $label;

            $wp_post_statuses[$status]->label_count = _n_noop(
                str_replace('()', '(%s)', $sing), 
                str_replace('()', '(%s)', $plur)
            );

            if (!isset($wp_post_statuses[$status]->labels)) $wp_post_statuses[$status]->labels = (object)[];

            if (!empty($status_args['save_as_label'])) {
                $wp_post_statuses[$status]->labels->save_as = $status_args['save_as_label'];
            }

            if (!empty($status_args['publish_label'])) {
                $wp_post_statuses[$status]->labels->publish = $status_args['publish_label'];
            }
        }

        // ================ Apply default label for custom statuses ==============
        foreach ($wp_post_statuses as $status => $args) {
            if (!isset($args->labels))
                $args->labels = (object)[];

            if (!isset($args->labels->name))
                $args->labels->name = (!empty($args->label)) ? $args->label : $status;

            if (!isset($args->labels->caption))
                $args->labels->caption = $args->labels->name;

            $label_name = $args->labels->name;

            if (empty($args->labels->count))
                $args->labels->count = (!empty($args->label_count)) ? $args->label_count : [$label_name, $label_name];

            if (empty($args->labels->publish)) {
                if ('approve' == $status) {
                    $lbl = esc_html__('Approve', 'presspermit-pro');
                } elseif ('assigned' == $status) {
                    $lbl = esc_html__('Assign', 'presspermit-pro');
                } elseif ('in-progress' == $status) {
                    $lbl = esc_html__('Mark In Progress', 'presspermit-pro');
                } else {
                    $lbl = sprintf(__('Submit as %s', 'presspermit-pro'), $label_name);
                }
                $args->labels->publish = esc_attr($lbl, $label_name);
            }

            if (empty($args->labels->save_as) && !in_array($status, ['publish', 'private']) && empty($args->public) && empty($args->private)) {
                $args->labels->save_as = esc_attr(sprintf(__('Save as %s'), $label_name));
            }

            if (empty($args->labels->visibility)) {
                if ('publish' == $status) {
                    $args->labels->visibility = esc_html__('Public');

                } elseif ($args->public) {
                    $args->labels->visibility = (!defined('WPLANG') || ('en_EN' == WPLANG)) 
                    ? esc_attr(sprintf(__('Public (%s)'), $label_name)) 
                    : $label_name;  // not currently customizable by Edit Status UI
                
                } elseif ($args->private) {
                    $args->labels->visibility = $label_name;
                }
            }

            $wp_post_statuses[$status] = $args;
        }
    }
}
