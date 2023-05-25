<?php
namespace PublishPress\Permissions\Statuses\UI;

class StatusAdmin
{
    public static function status_edit_ui($status, $args = [])
    {
        $defaults = ['new' => false, 'attrib_type' => ''];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $attrib_type = sanitize_key($attrib_type);

        $status_obj = get_post_status_object($status);

        $status_label = (!empty($status_obj->label)) ? $status_obj->label : '';
        $status_types = (!empty($status_obj) && !empty($status_obj->post_type)) ? $status_obj->post_type : [];

        if ('moderation' == $attrib_type) {
            $ordered_workflow_statuses = PPS::orderStatuses(PWP::getPostStatuses(['moderation' => true], 'object'));

            if (PPS::postStatusHasCustomCaps($status)) {
                $capability_status = (!empty($status_obj) && isset($status_obj->capability_status)) ? $status_obj->capability_status : $status;
            } else {
                $capability_status = '';
            }

            $status_order = (!empty($status_obj) && isset($status_obj->order)) ? $status_obj->order : '';
            $status_parent = (!empty($status_obj) && isset($status_obj->status_parent)) ? $status_obj->status_parent : '';
        }

        $name_disabled = ($new) ? '' : ' disabled ';
        $label_disabled = ('future' == $status) ? ' disabled ' : '';

        $is_pubpress_status = !empty($status_obj->pp_custom);
        $pubpress_disable_edit = ($is_pubpress_status) ? ' disabled ' : '';

        if ($is_pubpress_status):?>
            <div class="activating"><p>
                    <?php
                    global $publishpress;
                    if (!empty($publishpress) && !empty($publishpress->custom_status)) {
                        if ($status_term = $publishpress->custom_status->get_custom_status_by('slug', $status_obj->name)) {
                            $url = admin_url("admin.php?action=edit-status&term-id={$status_term->term_id}&page=pp-modules-settings&module=pp-custom-status-settings");
                        } else {
                            $url = admin_url("admin.php?action=edit-status&page=pp-modules-settings&module=pp-custom-status-settings");
                        }
                        printf(esc_html__('This status is %sdefined by PublishPress%s.', 'presspermit-pro'), '<a href="' . esc_url($url) . '">', '</a>');
                    } else {
                        esc_html_e('This is a PublishPress status, but the PublishPress plugin seems to be inactive.', 'presspermit-pro');
                    }
                    ?>
                </p></div>
        <?php endif; ?>

        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row"><label for="status_name"><?php esc_html_e('Slug', 'presspermit-pro'); ?></label></th>
                <td><input type="text" name="status_name" id="status_name" value="<?php echo esc_attr($status); ?>"
                           placeholder="<?php esc_attr_e('(Latin alphanumeric, maximum 20 characters)', 'presspermit-pro'); ?>"
                           class="regular-text" <?php echo esc_attr($name_disabled); ?> /></td>
            </tr>

            <?php if ('moderation' == $attrib_type): ?>
                <tr class="form-field">
                    <th><label for="status_capability_status"><?php esc_html_e('Capability Mapping', 'presspermit-pro') ?></label></th>
                    <td>
                        <select name="status_capability_status" id="status_capability_status" autocomplete="off">
                            <option value="" <?php if ('' == $capability_status) echo ' selected '; ?>><?php esc_html_e('(Default Capabilities)', 'presspermit-pro'); ?></option>
                            
                            <option value="<?php echo esc_attr($status); ?>" <?php if ($status == $capability_status) echo ' selected '; ?>><?php esc_html_e('Custom Capabilities for this Status', 'presspermit-pro'); ?></option>
                            <?php
                            foreach ($ordered_workflow_statuses as $other_status => $other_status_obj) :
                                if ($status == $other_status) {
                                    continue;
                                }
                                if (!empty($other_status_obj->status_parent) && ($other_status_obj->status_parent != $status_parent)) {
                                    continue;
                                }
                                $selected = ($other_status == $capability_status) ? ' selected ' : '';
                                ?>
                                <?php
                                if (($other_status == $status_parent)
                                || (PPS::postStatusHasCustomCaps($other_status)
                                    && (!isset($other_status_obj->capability_status) || ($other_status_obj->capability_status == $other_status))
                                    )
                                ) :?>
                                    <option value="<?php echo esc_attr($other_status); ?>" <?php echo esc_attr($selected); ?>>
                                    <?php 
                                    if ($other_status == $status_parent) {
                                        printf(esc_html__('same as WORKFLOW BRANCH PARENT (%s)', 'presspermit-pro'), esc_html($other_status_obj->label));

                                    } else {
                                        printf(esc_html__('same as %s status', 'presspermit-pro'), esc_html($other_status_obj->label));
                                    }
                                    ?>
                                    </option>
                                <?php endif;
                            endforeach;
                            ?>
                        </select>
                        <div class="pp-subtext"><?php esc_html_e('Simplify administration by making related statuses share capability requirements.', 'presspermit-pro'); ?></div>
                    </td>
                </tr>
            <?php endif; ?>

            <tr class="form-field">
                <th><label for="status_label"><?php esc_html_e('Label', 'presspermit-pro') ?></label></th>
                <td><input type="text" name="status_label" id="status_label" <?php echo esc_attr($pubpress_disable_edit); ?>
                           value="<?php echo esc_attr(stripslashes($status_label)); ?>"
                           class="regular-text" <?php echo esc_attr($label_disabled); ?> /></td>
            </tr>

            <?php if (('moderation' == $attrib_type) && ('future' != $status)) :
                $save_as_label = (!empty($status_obj) && !empty($status_obj->labels->save_as)) ? $status_obj->labels->save_as : '';
                ?>
                <tr class="form-field">
                    <th><label for="status_save_as_label"><?php esc_html_e('Save As Label', 'presspermit-pro') ?></label></th>
                    <td><input type="text" name="status_save_as_label" id="status_save_as_label"
                               value="<?php echo esc_attr(stripslashes($save_as_label)); ?>" class="regular-text"/></td>
                </tr>
                <?php
                $button_label = (!empty($status_obj) && !empty($status_obj->labels->publish)) ? $status_obj->labels->publish : '';
                ?>
                <tr class="form-field">
                    <th><label for="status_publish_label"><?php esc_html_e('Submit Button Label', 'presspermit-pro') ?></label></th>
                    <td><input type="text" name="status_publish_label" id="status_publish_label"
                               value="<?php echo esc_attr(stripslashes($button_label)); ?>" class="regular-text"/></td>
                </tr>

                <tr class="form-field">
                    <th><label for="status_parent"><?php esc_html_e('Workflow Branch', 'presspermit-pro') ?></label></th>
                    <td>
                        <select name="status_parent" id="status_parent" autocomplete="off">
                            <option value=""><?php esc_html_e('(Main Workflow)', 'presspermit-pro'); ?></option>
                            <?php
                            foreach ($ordered_workflow_statuses as $other_status => $other_status_obj) :
                                if (($other_status == $status) || !empty($other_status_obj->status_parent)) continue;
                                $selected = ($other_status == $status_parent) ? 'selected=selected' : '';
                                ?>
                                <option value="<?php echo esc_attr($other_status); ?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($other_status_obj->label); ?></option>
                            <?php endforeach;
                            ?>
                        </select>
                    </td>
                </tr>

                <tr class="form-field">
                    <?php
                    $status_parent_obj = get_post_status_object($status_parent);
                    $status_parent_caption = (!empty($status_parent_obj) && !empty($status_parent_obj->label)) ? $status_parent_obj->label : $status_parent;
                    $caption = ($status_parent) ? sprintf(__('Workflow Order <br />(in %s branch)', 'presspermit-pro'), esc_html($status_parent_caption)) : esc_html__('Workflow Order', 'presspermit-pro');
                    ?>
                    <th><label for="status_order"><?php echo esc_html($caption); ?></label></th>
                    <?php
                    $placeholder = (empty($status_order)) ? __('(none)', 'presspermit-pro') : '';
                    $title = __('If no order is set, this status will be available, but not offered as a default next step.', 'presspermit-pro');
                    ?>
                    <td><input type="text" name="status_order"
                               id="status_order" placeholder="<?php echo esc_attr($placeholder);?>" title="<?php echo esc_attr($title);?>" 
                               value="<?php echo esc_attr(stripslashes($status_order)); ?>" class="regular-text"/>
                        <?php if ($status_parent): ?>
                            <div class="pp-subtext pp-no-hide"><?php esc_html_e('Note: Branch ordering starts at 1, separate from main workflow order.', 'presspermit-pro'); ?></div>
                        <?php else: ?>
                            <div class="pp-subtext pp-no-hide"><?php esc_html_e('To make this status later in the workflow sequence, give it a higher number. To exclude it from workflow automation, give it a zero value.', 'presspermit-pro'); ?></div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr class="pp-status-post-types">
                <th><label for="types_label"><?php esc_html_e('Post Types', 'presspermit-pro') ?></label></th>
                <td style="align:left">

                    <?php
                    $types = get_post_types(['public' => true, 'show_ui' => true], 'object', 'or');

                    $omit_types = apply_filters('presspermit_unfiltered_post_types', ['wp_block']); // @todo: review wp_block filtering
                    $omit_types = array_merge($omit_types, ['nav_menu', 'attachment', 'revision']);
                    $types = array_diff_key($types, array_fill_keys((array)$omit_types, true));

                    $option_name = 'pp_status_post_types';

                    $enabled = !empty($status_types) ? (array)$status_types : [];
                    ?>
                    <div>
                        <?php if ($locked_status = in_array($status, ['pending', 'future', 'draft'])) : ?>
                            <input type="hidden" name="<?php echo 'pp_status_all_types'; ?>" value="1"/>
                        <?php
                        endif;

                        $all_enabled = empty($enabled) || $locked_status;
                        $disabled = ($locked_status) ? ' disabled ' : '';
                        ?>

                        <div class="agp-vspaced_input">
                            <label for="<?php echo 'pp_status_all_types'; ?>">
                                <input name="<?php echo 'pp_status_all_types'; ?>" type="checkbox"
                                       id="<?php echo 'pp_status_all_types'; ?>"
                                       value="1" <?php checked('1', $all_enabled);?> <?php echo esc_attr($disabled); ?> />
                                <?php esc_html_e('(All Types)', 'presspermit-pro'); ?>
                            </label>
                        </div>
                        <?php

                        $hint = '';

                        if (!$locked_status) {
                        $disabled = ($all_enabled) ? ' disabled ' : '';

                        if ((defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status')) && defined('PRESSPERMIT_COLLAB_VERSION') && !empty($status_obj->pp_custom)) {
                            if (!empty($publishpress->modules->custom_status->options->post_types)) {
                                $types = array_intersect_key($types, array_intersect($publishpress->modules->custom_status->options->post_types, ['on']));

                                $display_hint = true;
                            }
                        }

                        foreach ($types as $key => $obj) {
                            $id = $option_name . '-' . $key;
                            $name = $option_name . "[$key]";
                            ?>
                            <div class="agp-vspaced_input">
                                <label for="<?php echo esc_attr($id); ?>" title="<?php echo esc_attr($key); ?>">
                                    <input name="<?php echo esc_attr($name); ?>" type="hidden" value="0"/>
                                    <input name="<?php echo esc_attr($name); ?>" type="checkbox"
                                       class="pp_status_post_types" <?php echo esc_attr($disabled); ?> id="<?php echo esc_attr($id); ?>"
                                       value="1" <?php checked('1', in_array($key, $enabled, true)); ?> />

                                    <?php
                                    if (isset($obj->labels_pp))
                                        echo esc_html($obj->labels_pp->name);
                                    elseif (isset($obj->labels->name))
                                        echo esc_html($obj->labels->name);
                                    else
                                        echo esc_html($key);
                                ?>
                                </label>
                            </div>
                        <?php
                        } // end foreach src_otype
                    }
                    ?>
                    </div>

                    <?php if (!empty($display_hint)) : ?>
                        <br/><p>
                            <?php
                            printf(
                                esc_html__('Note: Post Types must also be enabled in %1$sPublishPress settings%2$s', 'presspermit-pro'), 
                                "<a href='" . esc_url(admin_url('admin.php?page=pp-modules-settings&module=pp-custom-status-settings')) . "'>", 
                                '</a>'
                            );
                            ?>
                        </p>
                    <?php endif ?>
                </td>
            </tr>
        </table>

        <script type="text/javascript">
            /* <![CDATA[ */
            function ucwords(str) {
                // http://kevin.vanzonneveld.net
                // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
                // +   improved by: Waldo Malqui Silva
                // +   bugfixed by: Onno Marsman
                // +   improved by: Robin
                // +      input by: James (http://www.james-bell.co.uk/)
                // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
                // *     example 1: ucwords('kevin van  zonneveld');
                // *     returns 1: 'Kevin Van  Zonneveld'
                return (str + '').replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function ($1) {
                    return $1.toUpperCase();
                });
            }

            jQuery(document).ready(function ($) {
                $('#status_name').on('focusout', function()
                {
                    if (!$('#status_label').val()) {
                        $('#status_label').val(ucwords($(this).val().replace(/[_-]/g, ' ')));
                    }

                    $(this).val($(this).val().replace(/[ \t]+/g, '_').toLowerCase().replace(/[^a-z0-9_\-]/gi, '').substring(0, 20));
                });
            });
            /* ]]> */
        </script>

        <?php
    } // end function

} // end class
