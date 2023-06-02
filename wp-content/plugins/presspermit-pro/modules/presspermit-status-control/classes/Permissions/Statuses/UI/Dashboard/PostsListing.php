<?php
namespace PublishPress\Permissions\Statuses\UI\Dashboard;

class PostsListing
{
    var $post_ids = [];

    function __construct()
    {
        // This script executes on the 'init' action if is_admin() for 'edit.php' and ajax action 'inline-save', if the post type is enabled for PP filtering. 
        //

        if (presspermit_empty_REQUEST('post_status') && presspermit_empty_REQUEST('author')) {
            add_action('presspermit_user_init', [$this, 'maybe_force_all_posts_view']);
        }

        add_filter('views_' . PWP::findPostType(), [$this, 'flt_views_stati']);
        add_action('admin_print_footer_scripts', [$this, 'act_modify_inline_edit_ui']);
        add_filter('presspermit_hide_quickedit', [$this, 'flt_hide_quickedit'], 10, 2);

        add_action('the_post', [$this, 'act_log_displayed_posts']);

        if (defined('PUBLISHPRESS_VERSION') && defined('PRESSPERMIT_COLLAB_VERSION')) {
            add_action('wp_loaded', [$this, 'act_publishpress_compat']);
        }

        if (!presspermit_empty_REQUEST('pp_submission_done')) {
            add_action('admin_notices', [$this, 'act_submission_notice']);
        }

        add_action('admin_head', [$this, 'actApplyPendingCaptionJS']);
    }

    // Since we are providing WYSIWYCE, don't default non-Editors to "Mine" view
    function maybe_force_all_posts_view() {
        $user = presspermit()->getUser();

        if (!$post_type = presspermit_REQUEST_key('post_type')) {
            $post_type = 'post';
        }

        if ($type_obj = get_post_type_object($post_type)) {
            if (!empty($type_obj->cap->edit_others_posts) && empty($user->allcaps[$type_obj->cap->edit_others_posts])) {
                $_REQUEST['all_posts'] = 1;
            }
        }
    }

    // If Pending status label is customized, apply it to Posts listing
    // @todo: js file with localize_script()
    function actApplyPendingCaptionJS() {
        $label_changes = [];

        foreach(['pending' => esc_html__('Pending')] as $status => $default_label) { // support label changes to multiple statuses
            $status_obj = get_post_status_object($status);

            if ($status_obj && ($status_obj->label != $default_label)) {
                $label_changes[$status]= (object)['old_label' => $default_label, 'new_label' => $status_obj->label];
            }
        }

        if (!$label_changes) {
            return;
        }
        ?>
        <style type="text/css">
            span.post-state{display:none;}
        </style>

        <script type="text/javascript">
            /* <![CDATA[ */
            jQuery(document).ready(function ($) {
                <?php foreach($label_changes as $status => $obj):?>
                $("span.post-state:contains('<?php echo esc_attr($obj->old_label); ?>')").html('<?php echo esc_attr($obj->new_label); ?>');
                $("select[name='_status'] option[value='<?php echo esc_attr($status); ?>']").html('<?php echo esc_attr($obj->new_label); ?>');
                $("td.column-status:contains('<?php echo esc_attr($obj->old_label); ?>')").html('<?php echo esc_attr($obj->new_label); ?>'); // PublishPress status column
                <?php endforeach;?>

                $("span.post-state").show();
            });
            /* ]]> */
        </script>

        <?php
    }

    // status display in Edit Posts table rows
    public static function fltDisplayPostStates($post_states)
    {
        global $post, $wp_post_statuses;

        if (empty($post) || in_array($post->post_status, ['publish', 'private', 'pending', 'draft']))
            return $post_states;

        if ('future' == $post->post_status) {  // also display eventual visibility of scheduled post (if non-public)
            if ($scheduled_status = get_post_meta($post->ID, '_scheduled_status', true)) {
                if ('publish' != $scheduled_status) {
                    if ($_scheduled_status_obj = get_post_status_object($scheduled_status))
                        $post_states[] = $_scheduled_status_obj->label;
                }
            }
        } elseif (presspermit_empty_GET('post_status') || (presspermit_GET_key('post_status') != $post->post_status)) {  // if filtering for this status, don't display caption in result rows
            $status_obj = (!empty($wp_post_statuses[$post->post_status])) ? $wp_post_statuses[$post->post_status] : false;
            if ($status_obj) {
                if ($status_obj->private || (!empty($status_obj->moderation)))
                    $post_states[] = $status_obj->label;
            }
        }

        return $post_states;
    }

    function act_submission_notice()
    {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php
                if (!empty($_REQUEST['pp_submission_done'])) {
                    if ($status_obj = get_post_status_object(sanitize_key($_REQUEST['pp_submission_done']))) {
                    $type_obj = get_post_type_object(PWP::findPostType());
                        $type_label = ($type_obj) ? strtolower($type_obj->labels->singular_name) : esc_html__('post', 'ppx');
                    printf(
                            esc_html__('Your %1$s was successfully submitted, but you cannot make further edits at this time. The current status of the %1$s is %2$s.', 'presspermit-pro'), 
                            esc_html($type_label), 
                            esc_html($status_obj->label)
                    );
                }
                }
                ?>
            </p>
        </div>
        <?php
    }

    function act_publishpress_compat()
    {
        global $publishpress;
        if (!empty($publishpress->custom_status)) {
            remove_action('manage_posts_custom_column', [$publishpress->custom_status, '_filter_manage_posts_custom_column']);
            remove_action('manage_pages_custom_column', [$publishpress->custom_status, '_filter_manage_posts_custom_column']);
            add_action('manage_posts_custom_column', [$this, 'flt_manage_posts_custom_column']);
            add_action('manage_pages_custom_column', [$this, 'flt_manage_posts_custom_column']);
        }
    }

    function flt_manage_posts_custom_column($column_name)
    {
        if ($column_name == 'status') {
            global $post;

            $builtin_stati = [
                'publish' => esc_html__('Published'),
                'draft' => esc_html__('Draft'),
                'future' => esc_html__('Scheduled'),
                'private' => esc_html__('Private'),
                'pending' => esc_html__('Pending Review'),
                'trash' => esc_html__('Trash'),
            ];

            if (array_key_exists($post->post_status, $builtin_stati)) {
                echo esc_html($builtin_stati[$post->post_status]) . ' ';  // If an invalid second 'publish' status is stored to PublishPress configuration, ensure at least a space between.
            }
        }
    }

    function act_log_displayed_posts($_post)
    {
        $this->post_ids[] = $_post->ID;
    }

    function flt_hide_quickedit($hide, $type_obj)
    {
        return !PPS::havePermission('moderate_any');
    }

    function flt_views_stati($views)
    {
        $post_type = PWP::findPostType();
        $type_stati = PWP::getPostStatuses(['show_in_admin_all_list' => true, 'post_type' => $post_type]);

        $views = array_intersect_key($views, array_flip($type_stati));

        // also remove filtered stati from "All" count 
        $num_posts = array_intersect_key(wp_count_posts($post_type, 'readable'), $type_stati);

        $total_posts = array_sum((array)$num_posts);

        $class = !isset($views['mine']) && presspermit_empty_REQUEST('post_status', 'show_sticky') ? ' class="current"' : '';
        $allposts = (strpos($views['all'], 'all_posts=1')) ? $allposts = '&all_posts=1' : '';

        $views['all'] = "<a href='edit.php?post_type=$post_type{$allposts}'$class>" 
        . sprintf(
            _nx(
                'All <span class="count">(%s)</span>', 
                'All <span class="count">(%s)</span>', 
                (int) $total_posts, 
                'posts'
            ), 
            number_format_i18n($total_posts)
        ) 
        . '</a>';

        return $views;
    }

    // @todo: move to .js
    // add "keep" checkboxes for custom private stati; set checked based on current or scheduled post status
    // add conditions UI to inline edit
    function act_modify_inline_edit_ui()
    {
        $pp = presspermit();

        $screen = get_current_screen();
        $post_type_object = get_post_type_object($screen->post_type);
        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            jQuery(document).ready(function ($) {
                <?php
                $isContentAdministrator = presspermit()->isContentAdministrator();
                $moderation_statuses = [];
                global $typenow;

                $_stati = $pp->admin()->orderTypes(
                    PWP::getPostStatuses(
                        ['_builtin' => false, 'moderation' => true, 'post_type' => $typenow], 
                        'object'
                    ), 
                    ['order_property' => 'order']
                );
                
                foreach ($_stati as $status => $status_obj) {
                    $set_status_cap = "set_{$status}_posts";

                    $check_cap = (!empty($post_type_object->cap->$set_status_cap)) 
                    ? $post_type_object->cap->$set_status_cap 
                    : $post_type_object->cap->publish_posts;

                    if ($isContentAdministrator || PPS::havePermission('moderate_any') || current_user_can($check_cap)) {
                        $moderation_statuses[$status] = $status_obj;
                    }
                }

                $pvt_stati = [];
                $_stati = $pp->admin()->orderTypes(
                    PWP::getPostStatuses(
                        ['private' => true, 'post_type' => $typenow], 
                        'object'
                    ), 
                    ['order_property' => 'label']
                );
                
                foreach ($_stati as $status => $status_obj) {
                    $set_status_cap = "set_{$status}_posts";

                    $check_cap = (!empty($post_type_object->cap->$set_status_cap)) 
                    ? $post_type_object->cap->$set_status_cap 
                    : $post_type_object->cap->publish_posts;

                    if ($isContentAdministrator || current_user_can($check_cap)) {
                        $pvt_stati[$status] = $status_obj;
                    }
                }
                ?>

                <?php foreach( $moderation_statuses as $status => $status_obj ) :?>
                if (!$('select[name="_status"] option[value="<?php echo esc_attr($status);?>"]').length) {
                    $('<option value="<?php echo esc_attr($status);?>"><?php echo esc_html($status_obj->label);?></option>').insertBefore('select[name="_status"] option[value="pending"]');
                }
                <?php endforeach;?>

                <?php if (!PPS::privacyStatusesDisabled()): ?>
                    if ($('select[name="_status"] option[value="-1"]').length) {
                        <?php foreach( $pvt_stati as $status => $status_obj ) :?>
                        if (!$('select[name="_status"] option[value="<?php echo esc_attr($status);?>"]').length) {
                            $('<option value="<?php echo esc_attr($status);?>"><?php echo esc_html($status_obj->label);?></option>').insertAfter('select[name="_status"] option[value="private"]');
                        }
                        <?php endforeach;?>
                    }
                <?php endif;

                // also support forcing of default privacy for non-hierarchical types
                $is_hierarchical = is_post_type_hierarchical($typenow);

                if ( $is_hierarchical || ($pp->getTypeOption('default_privacy', $typenow) && ($pp->getTypeOption('force_default_privacy', $typenow) || PWP::isBlockEditorActive($typenow))) ) {
                global $posts;

                
                if ( !empty($posts) ) {
	                $attributes = PPS::attributes();
	
	                foreach( array_keys($posts) as $key ) :
		                if (!in_array($posts[$key]->ID, $this->post_ids)) continue;
		
		                $force_vis = $attributes->getItemCondition(
		                    'post',
		                    'force_visibility',
		                    ['id' => $posts[$key]->ID, 'assign_for' => 'item', 'default_only' => !$is_hierarchical, 'post_type' => $typenow]
		                );
		
		                if ( $is_hierarchical ) :
		                	$child_status = $attributes->getItemCondition('post', 'force_visibility', ['id' => $posts[$key]->ID, 'assign_for' => 'children']);
		                ?>
		                    $('#inline_<?php echo (int) $posts[$key]->ID;?> div._status').after('<div class="_status_sub"><?php echo esc_html($child_status);?></div>');
		                <?php endif; ?>
		                
		                $('#inline_<?php echo (int) $posts[$key]->ID;?> div._status').after('<div class="_force_vis"><?php echo esc_html($force_vis);?></div>');
	                <?php
	                endforeach;
                }
                ?>

                $("tr.bulk-edit-page label.inline-edit-status").parent().after('<div class="inline-edit-group"><label class="inline-edit-status-sub alignleft">'
                + '<span class="title"><?php printf(esc_html__('Subpage Vis.', 'presspermit-pro'), esc_html($post_type_object->label));?></span>'
                + '<span class="pp_child_select-open" style="margin-left: 0.1em">[</span>'
                + '<select name="_status_sub" title="<?php printf(esc_html__('Force visibility of sub-%s', 'presspermit-pro'), esc_html($post_type_object->label));?>" autocomplete="off"></select>'
                + '<span class="pp_child_select-close">]</span></label><span>'
                + '<label for="pp_propagate_visibility" class="alignleft" style="display:none; margin-top:0.5em"><input type="checkbox" name="pp_propagate_visibility" id="pp_propagate_visibility" checked disabled />'
                + ' <?php printf(esc_html__('existing sub-%s', 'presspermit-pro'), esc_html($post_type_object->label));?></label>'
                + '</span></div>');

                var elems = '';
                if (!$('select[name="_status_sub"] option[value="<?php echo esc_attr($status);?>"]').length) {
                    elems = elems + '<option value="publish"><?php echo esc_html(PWP::__wp('Public'));?></option>';
                    <?php foreach( $pvt_stati as $status => $status_obj ) :?>
                    elems = elems + '<option value="<?php echo esc_attr($status);?>"><?php echo esc_html($status_obj->label);?></option>';
                    <?php endforeach;?>
                }
                $("select[name='_status_sub']").html('<option value=""><?php esc_html_e('(manual)', 'presspermit-pro');?></option>' + elems);
                $('.inline-edit-status-sub select').prepend('<option value="-1"><?php esc_html_e('&mdash; No Change &mdash;');?></option>');
                $('.inline-edit-status-sub select option[value="-1"]').prop('selected', true);

                $("label.inline-edit-status-sub span.title").append('<span class="pp_disclaimer" title="<?php esc_attr_e('Status may also be altered by category or term', 'presspermit-pro');?>"> * </span>');
                $("select[name='_status_sub']").siblings('span').attr('title', $("select[name='_status_sub']").attr('title'));

                $(document).on('click', 'select[name="_status_sub"]', function (e) {
                    $('input[name="pp_propagate_visibility"]').parent().toggle($(this).val() != -1 && $(this).val() != '');
                });

                <?php } // endif hier ?>

                $(document).on('click', '.inline-edit-row input[name="keep_custom_privacy"]', function()
                {
                    $("input[name='keep_private']").prop("checked", false);
                    $('input.inline-edit-password-input').val('').prop('disabled', true);
                });

                $(document).on('click', '.inline-edit-row input[name="keep_private"]', function()
                {
                    $("input[name='keep_custom_privacy']").prop("checked", false);
                });

                $(document).on('focusin', '.inline-edit-row input.ptitle', function()
                {
                    <?php
                    $pp = presspermit();
                    $scheduled_stati = [];

                    if (!empty($pp->listed_ids[$screen->post_type])) {
                        global $wpdb;
                        $id_csv = implode("','", array_keys($pp->listed_ids[$screen->post_type]));

                        if ($results = $wpdb->get_results(
                            "SELECT meta_value, post_id FROM $wpdb->postmeta WHERE meta_key = '_scheduled_status' AND post_id IN ('$id_csv')"
                        )) {
                            foreach ($results as $row) {
                                $scheduled_stati[$row->meta_value][$row->post_id] = true;
                            }
                        }
                    }
                    
                    ?>
                    var rowData, status;
                    id = inlineEditPost.getId(this);
                    rowData = $('#inline_' + id);
                    status = $('._status', rowData).text();
                    keep_status = '';
                    var pvt_stati = ["<?php echo implode('", "', array_map('esc_attr', array_keys($pvt_stati))); ?>"];

                    <?php if (!PPS::privacyStatusesDisabled()): ?>
                        <?php // append elements for pvt stati ?>
                        if (!$('#edit-' + id + ' input[name="keep_custom_privacy"]').length) {
                            var elems = '';
                            <?php foreach( $pvt_stati as $status => $status_obj ) :
                            if ('private' == $status) continue;
                            ?>
                            elems = elems + '<label class="alignleft">&nbsp;&nbsp;<input type="radio" name="keep_custom_privacy" value="<?php echo esc_attr($status);?>"><span class="checkbox-title"> <?php echo esc_html($status_obj->label);?>&nbsp;&nbsp;</span></label>';
                            <?php endforeach; ?>

                            if (elems) {
                                <?php if ( !$post_type_object->hierarchical ) : ?>
                                $('#edit-' + id + ' label.inline-edit-private').before('<br /><br />');
                                <?php endif; ?>

                                $('#edit-' + id + ' label.inline-edit-private').after(elems);
                            }
                        }
                    <?php endif;?>

                    if (-1 !== jQuery.inArray(status, pvt_stati)) {
                        keep_status = status;
                    }
                    <?php if( $scheduled_stati ) : ?>
                    else if ('future' == status) {
                        var id_val = parseInt(id);

                        <?php foreach( $scheduled_stati as $status => $pvt_ids ) : ?>
                        var pvt_ids = new Array(<?php echo esc_attr(implode(', ', array_keys($pvt_ids))); ?>);

                        if (-1 !== jQuery.inArray(id_val, pvt_ids))
                            keep_status = '<?php echo esc_attr($status); ?>';
                        <?php endforeach; ?>
                    }
                    <?php endif; ?>

                    if (keep_status) {
                        if ('private' != keep_status) {
                            $("input[name='keep_private']").prop("checked", false);
                            $("input[name='keep_custom_privacy'][value='" + keep_status + "']").prop("checked", true);
                        }
                    }

                    var current_val = $('._status_sub', rowData).text();
                    $('select[name="_status_sub"] option[value="' + current_val + '"]').prop('selected', true);

                    var current_val = $('._force_vis', rowData).text();
                    var inputs = $("input[name='keep_private'],input[name='keep_custom_privacy']");
                    $(inputs).prop('disabled', current_val != '');
                    if (current_val != '') {
                        $(inputs).parent().attr('title', '<?php esc_html_e('Visibility locked', 'presspermit-pro'); ?>');
                    } else {
                        $(inputs).parent().attr('title', '');
                    }
                });
            });
            //]]>
        </script>
        <?php
    } // end function modify_inline_edit_ui
}
