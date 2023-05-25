<?php
namespace PublishPress\Permissions\Circles\UI;

class GroupEdit
{
    public static function circlesUI($group_type, $group_id)
    {
        if (!in_array($group_type, presspermit()->groups()->getGroupTypes(), true) 
        || !$circle_types = Circles::getCircleTypes()
        ) {
            return;
        }

        $pp = presspermit();

        ?>
        <style type="text/css">
        #pp_circle_activation tr {float: left;}
        #pp_circle_activation td {padding-right: 70px;}
        </style>
        <?php

        echo '<div id="pp_circle_activation" class="pp-group-box pp-group_post-types">'
            . '<h3>';

        esc_html_e('Circle Activation', 'presspermit-pro');

        echo '</h3>';

        $all_types = $pp->getEnabledPostTypes([], 'object');
        $circles = Circles::getGroupCircles($group_type, $group_id);

        $types_csv = implode(",", array_merge(array_keys($all_types), ['user']));
        echo "<input type='hidden' name='reviewed_circle_types' value=" . esc_attr($types_csv) . " />";

        echo '<table>';

        $circle_captions = ['read' => esc_html__('Visibility Circle', 'presspermit-pro'), 'edit' => esc_html__('Editorial Circle', 'presspermit-pro')];

        $disabled_circle_types = [];

        // If a WP role has exemption capability assigned, don't offer to make it a circle
        if ('pp_group' == $group_type) {
            if ($metagroup = presspermit()->groups()->isMetagroup('wp_role', $group_id)) {
                if ($role = get_role($metagroup->metagroup_id) ) {
                    foreach ($circle_types as $_circle_type) {
                        if (!empty($role->capabilities["pp_exempt_{$_circle_type}_circle"])) {
                            $disabled_circle_types[$_circle_type] = "pp_exempt_{$_circle_type}_circle";
                        }
                    }
                }
            }
        }

        foreach ($circle_types as $_circle_type) {
            echo '<tr><td style="vertical-align:top;padding-bottom:10px;">';

            $is_circle = isset($circles[$group_id][$_circle_type]);

            $caption = sprintf(__('This group is a %s', 'presspermit-pro'), $circle_captions[$_circle_type]);
            
            if (!empty($disabled_circle_types[$_circle_type])) {
                $checked = 'disabled=disabled';
            } else {
                $checked = ($is_circle) ? 'checked="checked"' : '';
            }

            echo "<div class='agp-vspaced_input'><span style='white-space:nowrap'>"
            . "<input type='checkbox' name='is_" . esc_attr($_circle_type) . "_circle' value='1' id='is_" . esc_attr($_circle_type) . "_circle' class='is_circle' " . esc_attr($checked) . "> "
            . "<label for='is_" . esc_attr($_circle_type) . "_circle'>";
            
            if (!empty($disabled_circle_types[$_circle_type])) {
                echo "<span style='text-decoration:line-through'>" . esc_html($caption) . "</span>";
            } else {
                echo esc_html($caption);
            }

            echo '</label></span></div>';

            if (!empty($disabled_circle_types[$_circle_type])) {

                printf(
                    esc_html__('Members of this role are exempted from all %s restrictions, due to the %s capability.', 'presspermit-pro'),
                    esc_html($circle_captions[$_circle_type]),
                    '<strong>' . esc_html($disabled_circle_types[$_circle_type]) . '</strong>'
                );

                echo '</td></tr>';

                continue;
            }

            $hide = ($is_circle) ? '' : 'display:none';
            ?>
            <table class="form-table" style="<?php echo esc_attr($hide);?>">
                <tbody>
                <tr>
                    <td style="max-width:215px">
                        <?php
                        echo '<h4 style="margin:0.1em">' . esc_html__('apply limitation for post types:', 'presspermit-pro') . '</h4>';

                        $hide_types = count($all_types) > 7;

                        $ordered_types = [];
                        foreach (array_keys($all_types) as $post_type) {
                            $ordered_types[$post_type] = $all_types[$post_type]->labels->singular_name;
                        }
                        asort($ordered_types);

                        $ordered_types['user'] = esc_html__('User');

                        $enabled_list = [];
                        if ($is_circle) {
                            $i = 0;
                            foreach ($ordered_types as $post_type => $caption) {
                                if (isset($circles[$group_id][$_circle_type][$post_type])) {
                                    $i++;
                                    if ($i == 6) {
                                        $enabled_list[] = esc_html__('more...', 'presspermit-pro');
                                        break;
                                    } else
                                        $enabled_list[] = $caption;
                                }
                            }

                            if ($enabled_list) : ?>
                                <div>
                                    <?php echo esc_html(implode(', ', $enabled_list)); ?>
                                </div>
                            <?php
                            endif;
                        }
                        ?>

                        <div style="<?php if (!$hide_types) echo 'display:none';?>">
                            <a href='#show-circle-types'><?php esc_html_e('edit', 'presspermit-pro'); ?></a>
                        </div>

                        <div class="circle-post-types"<?php if ($hide_types) echo " style='display:none'"; ?>>

                            <p><a href='#hide-circle-types'><?php esc_html_e('hide', 'presspermit-pro'); ?></a></p>

                            <?php
                            foreach (array_keys($ordered_types) as $post_type) {
                                // default new activations to all post types
                                $checked = (isset($circles[$group_id][$_circle_type][$post_type]) || !$is_circle) 
                                ? 'checked="checked"' 
                                : '';
                                
                                $type_caption = ('user' == $post_type) ? esc_html__('User') : $all_types[$post_type]->labels->singular_name;

                                echo "<input type='checkbox' name='" . esc_attr($_circle_type) . "_circle_post_types[]' value='" . esc_attr($post_type) . "' id='" . esc_attr($_circle_type) . "_circle_post_type_" . esc_attr($post_type) . "' " . esc_attr($checked) . "> "
                                . "<label for='" . esc_attr($_circle_type) . "_circle_post_type_" . esc_attr($post_type) . "'>" 
                                . esc_html($type_caption)
                                . '</label><br />';
                            }
                            ?>
                            <div style="margin-top:5px;margin-left:10px"><input type="checkbox" class="ppc-check-all"/><?php esc_html_e('(toggle all)', 'presspermit');?></div>
                        </div>

                    </td>
                </tr>
                </tbody>
            </table>
            <?php

            echo '</td>';
        } // end foreach circle_type

        echo '</tr></table>';

        if ($pp->getOption('display_hints')) {
            echo '<div class="pp-current-roles-note pp-hint pp-no-hide" style="margin-top:5px;margin-left:0">';
            esc_html_e('Prevent viewing or editing of posts not authored by a fellow Circle Group member. Users may be a member of multiple circles. Note that an appropriate role assignment (either WP role or supplemental) is still required.', 'presspermit-pro');
            
            if (empty($metagroup)) {
                echo '<div style="margin-top:10px">';
                esc_html_e('Users may be exempted by adding the pp_exempt_read_circle or pp_exempt_edit_circle capability to their primary WP role.', 'presspermit-pro');
                echo '</div>';
            }
            
            echo '</div>';
        }

        echo '</div>';
    }
}
