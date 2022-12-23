<?php
namespace PublishPress\Capabilities;

/*
 * PublishPress Capabilities [Free]
 * 
 * Capabilities Screen UI: Custom Statuses interface
 * 
 * Note that the free version handles only the status selection capabilities. 
 * Custom status editing and deletion capability control is Pro functionality.
 * 
 */
class CustomStatusCapsUI {
    var $type_caps = [];
    var $postmeta_caps_displayed = false;

    function __construct() {
        add_filter('pp_capabilities_extra_post_capability_tabs', [$this, 'fltAddStatusCapsTab']);
        add_action('publishpress-caps_manager_postcaps_section', [$this, 'drawUI']);

        add_filter('publishpress_caps_manager_typecaps', [$this, 'fltTypeCaps']);
        add_filter('publishpress_caps_manage_additional_caps', [$this, 'fltAdditionalCaps']);

        add_action('admin_print_footer_scripts', [$this, 'actPostMetaCapStyles']);
    }

    function actPostMetaCapStyles() {
        if ($this->postmeta_caps_displayed):?>
            <style type="text/css">
            div.cme-cap-type-tables-delete {margin-bottom:100px;}
            </style>
        <?php endif;
    }

    function fltTypeCaps($type_caps) {
        return array_merge($type_caps, $this->type_caps);
    }

    // Make sure Additional Caps checkboxes don't include any status change capabilities for statuses that have distinct permissions disabled.
    // Any available status change capabilities will be shown in the Custom Statuses box.
    function fltAdditionalCaps($caps) {
        foreach(array_keys($caps) as $cap_name) {
            if (0 === strpos($cap_name, 'status_change_')) {
               unset($caps[$cap_name]);
            }
        }

        return $caps;
    }

    function fltAddStatusCapsTab($tabs) {
        $tabs['custom-status'] = esc_html__('Custom Statuses', 'capabilities-pro');
        return $tabs;
    }

    function drawUI ($args = []) {
        global $capsman, $cme_cap_helper, $current_user, $publishpress;

        $defaults = [
            'current' => '', 
            'rcaps' => [], 
            'is_administrator' => false, 
            'pp_metagroup_caps' => [], 
            'default_caps' => [], 
            'custom_types' => [], 
            'defined' => [], 
            'unfiltered' => [], 
            'type_caps' => [],
            'active_tab_id' => '',
        ];
        
        foreach(array_keys($defaults) as $var) {
            $$var = (isset($args[$var])) ? $args[$var] : $defaults[$var];
        }

        $this->type_caps = $type_caps;

        $list_statuses = [];

        $statuses = get_post_stati(['internal' => false, 'public' => false, 'private' => false], 'object');

        // This function is only called if PublishPress and its Custom Statuses module are active        
        $pp_terms = get_terms('post_status', ['hide_empty' => false]);
        foreach ($pp_terms as $term) {
            if (is_object($term)) {
                $list_statuses[$term->slug] = true;
            }
        }

        $ordered_statuses = $publishpress->custom_status->get_custom_statuses();

        // Post types that are configured by PublishPress to support custom statuses (this is NOT a per-status configuration)
        $custom_status_post_types = array_intersect($publishpress->modules->custom_status->options->post_types, ['on']);

        $postmeta_statuses = [];

        if (Pro::customStatusPostMetaPermissions()) {
            if ($attributes = \PublishPress\Permissions\Statuses::attributes()) {
                if (!empty($attributes->attributes['post_status'])) {
                    foreach($ordered_statuses as $status_term) {
                        $status_obj = get_post_status_object($status_term->slug);
                        if (empty($status_obj) || empty($status_obj->moderation)) {
                            continue;
                        }

                        if (!empty($attributes->attributes['post_status']->conditions[$status_term->slug])) {
                            $postmeta_statuses[$status_term->slug] = get_post_status_object($status_term->slug);
                        }
                    }

                    // custom moderation status registered by PublishPress Permissions
                    if (!empty($attributes->attributes['post_status']->conditions['approved'])) {
                        $postmeta_statuses['approved'] = get_post_status_object('approved');
                        $ordered_statuses[99999] = (object) ['slug' => 'approved', 'name' => $postmeta_statuses['approved']->label];
                    }
                }
            }
        }

        $do_postmeta_permissions = Pro::customStatusPostMetaPermissions();

        $id = 'cme-cap-type-tables-custom-status';
        $div_display = ($id == $active_tab_id) ? 'block' : 'none';

        echo '<div id="' . esc_attr($id) . '" style="display:' . esc_attr($div_display) . '">';

        echo "<h3><a href='" . esc_url_raw(admin_url("admin.php?page=pp-modules-settings&module=pp-custom-status-settings")) . "' target='_blank'>"
        . esc_html__('Custom Status Capabilities', 'capabilities-pro') 
        . '</a></h3>';

        echo '<table class="widefat cme-typecaps">';

        if ($do_postmeta_permissions) {
            do_action('presspermit_post_filters');
            $status_cap_mapper = \PublishPress\Permissions\Statuses\CapabilityFilters::instance();
        }

        $item_type = 'post';

        $cap_property_prefixes = ['set', 'edit', 'edit_others', 'delete', 'delete_others'];

        $cap_tips = array( 
            'set' =>            __( 'Can assign this status (for selected post types)', 'capabilities-pro' ),
            'edit' =>           __( 'Can edit posts of this status (for selected post types)', 'capabilities-pro' ),
            'edit_others' =>    __( 'Can edit other\'s posts of this status (for selected post types)', 'capabilities-pro' ),
            'delete' =>         __( 'Can delete posts of this status (for selected post types)', 'capabilities-pro' ),
            'delete_others' =>  __( 'Can delete other\'s posts of this status (for selected post types)', 'capabilities-pro' ),
        );

        $last_status_has_postmeta_caps = false;

        foreach($ordered_statuses as $status_term) {
            $status = $status_term->slug;
            $status_obj = get_post_status_object($status);

            if (!empty($status_obj->public) || !empty($status_obj->private) || in_array($status, ['draft', 'future'])) {
                continue;
            }

            // If PostMeta permissions are enforced, post types that have distinct permissions, support custom statuses and don't have this status disabled
            $status_post_types = [];

            // Only control type-specific caps for set / edit / delete if they will be enforced
            if ($do_postmeta_permissions && !empty($postmeta_statuses[$status])) {
                foreach( $defined['type'] as $post_type => $type_obj ) {
                    // Does this post type require distinct type-specific capabilities?
                    if ( in_array( $post_type, $unfiltered['type'] ) ) {
                        continue;
                    }

                    if (function_exists('presspermit') && !in_array($post_type, presspermit()->getEnabledPostTypes())) {
                        continue;
                    }

                    // Do PublishPress module settings enable custom statuses for this post type?
                    if (empty($custom_status_post_types[$post_type])) {
                        continue;
                    }
                    
                    if (!Pro::customStatusPostMetaPermissions($post_type, $status)) {
                        continue;
                    }

                    // @todo: is this redundant?
                    // Do PublishPress Permissions settings disable custom statuses for this post type?
                    if (\PublishPress\Permissions\Statuses::customStatusesEnabled($post_type)) {
                        $status_post_types[$post_type] = $type_obj;
                    }
                }
            }

            // column header (Set / Edit / Edit Others / Delete / Delete Others)
            if ($do_postmeta_permissions && !empty($postmeta_statuses) && (!empty($postmeta_statuses[$status]) || $last_status_has_postmeta_caps || empty($first_row_done))) {
                $tr_class = ($last_status_has_postmeta_caps) ? 'pp-capabilities-status-header' : '';
                $status_header = "<tr class='" . esc_attr($tr_class) . "'><th></th>";

                // label cap properties
                foreach( $cap_property_prefixes as $prefix ) {
                    if (($prefix != 'set') && empty($postmeta_statuses[$status])) {
                        $status_header .= '<th></th>';
                        continue;
                    }

                    $tip = ( isset( $cap_tips[$prefix] ) ) ? $cap_tips[$prefix] : '';

                    $status_header .= "<th title='" . esc_attr($tip) . "' class='post-cap'>";
                    $status_header .= '<a href="#toggle_status_meta">' . ucwords(str_replace( '_', '<br />', $prefix )) . '</a>';
                    $status_header .= '</th>';
                }
            
                $status_header .= '</tr>';
            } else {
                $status_header = '<tr class="pp-capabilities-status-spacer"></tr>';
            }

            $first_row_done = true;
            $last_status_has_postmeta_caps = false;

            $postmeta_class = ($do_postmeta_permissions) ? 'cme-postmeta-status' : '';
            $status_ui = "<tr class='cme_status $postmeta_class cme_status_{$status}'>";

            $td_class = ($do_postmeta_permissions && !empty($postmeta_statuses)) ? ' status-label-advanced' : '';
            $status_ui .= "<td class='status-label{$td_class}'><a href='#toggle_status_caps'>" . esc_html($status_obj->label) . "</a></td>";

            foreach( $cap_property_prefixes as $prefix ) {
                $prop = "{$prefix}_{$status}_posts";

                $status_col_ui = "<td class='status-caps status-caps-{$prefix}'>";

                if ('set' == $prefix) {
                    $td_classes = ['post-cap', 'cme_status_set_basic'];
                    $cap_slug = str_replace('-', '_', $status);
                    $cap_name = "status_change_{$cap_slug}";
                    $status_change_cap = $cap_name;

                    if (!empty($pp_metagroup_caps[$cap_name])) {
                        $td_classes []= 'cm-has-via-pp';
                    }
                    
                    if ($is_administrator || current_user_can($cap_name)) {
                        if (!empty($pp_metagroup_caps[$cap_name])) {
                            $title_text = sprintf( __( '%s: assigned by Permission Group', 'capabilities-pro' ), $cap_name );
                        } else {
                            $title_text = $cap_name;
                        }
                        
                        $disabled = '';
                        $checked = checked(1, ! empty($rcaps[$cap_name]), false );
                        
                        $td_class = ( $td_classes ) ? 'class="' . implode(' ', $td_classes) . '"' : '';
                        $style = (!empty($postmeta_statuses[$status])) ? ' style="margin-bottom: 10px;"' : '';
                        $status_col_ui .= '<input type="checkbox" ' . $td_class . 'title="' . esc_attr($title_text) . '" name="caps[' . $cap_name . ']" autocomplete="off" value="1" ' . $checked . $style . ' /> ';
                        
                        $display_status = true;
        
                        $this->type_caps[$cap_name] = true;   // @todo: filter Additional Caps / Plugin Caps
                    }

                    if ( empty($postmeta_statuses[$status]) ) {
                        $status_ui .= '</td>';

                        // Escaped piecemeal upstream; cannot be late-escaped until UI construction logic is reworked
                        echo $status_header . $status_ui . $status_col_ui;
                        $status_ui = '';
                    }

                    $this->type_caps[$cap_name] = true;
                }

                if ($is_post_meta_status = !empty($postmeta_statuses[$status])) {
                    $status_col_ui .= '<table>';
                    $displayed_status_col = false;

                    foreach($defined['type'] as $post_type => $type_obj) {
                        if (empty($status_post_types[$post_type])) {
                            continue;
                        }

                        $display_row = false;
                        
                        $td_classes = [];
                        $checkbox = '';
                        $title_text = '';

                        $row = '<tr>';

                        // if edit_others is same as edit_posts cap, don't display a checkbox for it
                        if ( ($prefix != 'edit_others' || $type_obj->cap->edit_others_posts != $type_obj->cap->edit_posts)
                        && ($prefix != 'delete_others' || $type_obj->cap->delete_others_posts != $type_obj->cap->delete_posts)
                        && (($prefix != 'set') || !empty($type_obj->cap->set_posts_status))
                        ) {
                            if ($prefix == 'set') {
                                $cap = $type_obj->cap->set_posts_status;
                            } else {
                                $basic_type_property = "{$prefix}_posts";
                                $cap = (in_array($prefix, ['edit_others', 'delete_others'])) ? $type_obj->cap->$basic_type_property : "{$prefix}_post";
                            }

                            $caps = Pro::getStatusCaps($cap, $post_type, $status);
                            $caps = array_diff($caps, [$cap]);
                            $cap_name = reset($caps);

                            if ($cap_name) {
                                $td_classes []= "post-cap";
                                
                                if (!empty($pp_metagroup_caps[$cap_name])) {
                                    $td_classes []='cm-has-via-pp';
                                }
                                
                                if ($is_administrator || current_user_can($cap_name)) {
                                    if (!empty($pp_metagroup_caps[$cap_name])) {
                                        $title_text = sprintf( __( '%s: assigned by Permission Group', 'capabilities-pro' ), $cap_name );
                                    } else {
                                        $title_text = $cap_name;
                                    }
                                    
                                    $disabled = (('set' == $prefix) && empty($rcaps[$status_change_cap])) ? 'disabled' : '';
                                    $checked = checked(1, ! empty($rcaps[$cap_name]), false );

                                    $checkbox = '<input type="checkbox" title="' . esc_attr($title_text) . '" name="caps[' . esc_attr($cap_name) . ']" autocomplete="off" value="1" ' . $checked . ' ' . $disabled . ' /> ' . esc_html($type_obj->label);
                                    
                                    $this->type_caps [$cap_name] = true;
                                    $display_row = true;

                                    $this->postmeta_caps_displayed = true;
                                    $last_status_has_postmeta_caps = true;
                                }
                            }
                        } elseif(!empty($type_obj->cap->$prop)) {
                            $title_text = sprintf( esc_attr__('shared capability: %s', 'capabilities-pro'), esc_attr($type_obj->cap->$prop));
                        }
                        
                        if (isset($rcaps[$cap_name]) && empty($rcaps[$cap_name])) {
                            $td_classes []= "cap-neg";
                        }
                        
                        $td_class = ( $td_classes ) ? implode(' ', $td_classes): '';
                        
                        $row .= "<td class='" . esc_attr($td_class) . " title='" . esc_attr($title_text) . ">$checkbox";

                        if ( false !== strpos( $td_class, 'cap-neg' ) )
                            $row .= '<input type="hidden" class="cme-negation-input" name="caps[' . $cap_name . ']" value="" />';

                        $row .= '</td>';
                        $row .= '</tr>';

                        if ( $display_row ) {
                            // Escaped piecemeal upstream; cannot be late-escaped until UI construction logic is reworked
                            echo $status_header . $status_ui . $status_col_ui;
                            $status_header = $status_ui = $status_col_ui = '';

                            // Escaped piecemeal upstream; cannot be late-escaped until UI construction logic is reworked
                            echo $row;
                            $displayed_status_col = true;
                        }
                    }

                    if ($displayed_status_col) {
                        echo '</table>';
                    }

                    if ('set' == $prefix) {
                        $url = add_query_arg('status', $status, admin_url('admin.php?page=presspermit-status-edit&action-edit'));
                        echo "<div class='types'><a href='" . esc_url($url) . "' target='_blank'>" . esc_html__('Post Types...', 'capabilities-pro') . "</a></div>";
                    }

                } elseif ('set' !== $prefix) {
                    continue;
                }

                if (!$status_ui) {
                    if (!empty($display_row)) {
                        echo '<div class="row-spacer">';
                    }
                    echo '</td>';
                }
            } // endforeach cap properties

            // status row
            if (!empty($display_status)) {
                if ($status_ui) {
                    if (!$is_post_meta_status && $do_postmeta_permissions && !empty($postmeta_statuses)):?>
                        <tr><th class="row-spacer"></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                    <?php endif;

                    // Escaped piecemeal upstream; cannot be late-escaped until UI construction logic is reworked
                    echo $status_ui;
                }

                if (!$is_post_meta_status && $do_postmeta_permissions && !empty($postmeta_statuses)) :?>
                    <td></td><td></td><td></td><td></td>
                <?php endif;

                echo '</tr>';
            }
            
        } // endforeach statuses
        ?>

        <script type="text/javascript">
        /* <![CDATA[ */
        jQuery(document).ready( function($) {
            $('input.cme_status_set_basic').click( function() {
                $(this).next('table').find('td.post-cap input').attr('disabled', !$(this).prop('checked'));
            });
        });
        /* ]]> */
        </script>

        </tr>
        <?php if (defined('PRESSPERMIT_ACTIVE')):?>
        <tr>
        <?php if (!empty($display_row)):?>
            <td class="cme-status-footer cme-custom-status-hints" colspan="7">
            <p>
            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=presspermit-statuses&attrib_type=moderation'));?>" target="_blank"><?php esc_html_e('Enable / Disable Custom Permissions', 'capabilities-pro');?></a>
            </p>
            </td>
        <?php elseif($do_postmeta_permissions && empty($postmeta_statuses)):?>
            <td class="cme-status-footer cme-custom-status-hints" colspan="2">
            <p>
            <a href="<?php echo esc_url_raw(admin_url('admin.php?page=presspermit-statuses&attrib_type=moderation'));?>" target="_blank"><?php esc_html_e('Customize Permissions', 'capabilities-pro');?></a>
            </p>
            </td>
        <?php else:?>
            <td colspan="2">
            <?php if (function_exists('presspermit') && !presspermit()->moduleActive('status-control') && pp_capabilities_get_permissions_option('display_hints') ) :?>
            <div class='cme-custom-status-hints'>
            <div class='cme-show-status-hint'>
            <?php
            printf(esc_html__('%sAdvanced status control...%s', 'capabilities-pro'), "<a href='#pp-advanced-status-control' target='_blank'>", '</a>');
            ?>
            </div>
            <div class='pp-status-control-notice' style='display:none'>
            <?php
            $url = admin_url('admin.php?page=presspermit-settings');
            printf(esc_html__('For more control, enable the PublishPress Permissions %sStatus Control module%s', 'capabilities-pro'), "<a href='" . esc_url_raw($url) . "' target='_blank'>", '</a>' );?>
            </div>
            </div>
            <?php endif;?>
            </td>
        <?php endif;?>
        </tr>
        <?php endif;?>

        </table>

        <?php 
        // clicking on post type name toggles corresponding checkbox selections
        ?>
        <script type="text/javascript">
        /* <![CDATA[ */
        jQuery(document).ready( function($) {
            $('a[href="#toggle_status_caps"]').click( function() {
                var chks = $(this).closest('tr').find('input');
                $(chks).prop( 'checked', ! $(chks).first().is(':checked') );
                return false;
            });

            $('a[href="#toggle_status_meta"]').click( function() {
                var tdIndex = $(this).closest('th').index() - 1;
                var chks = $(this).closest('tr').next().find("td.status-caps:eq(" + tdIndex + ") table tr td.post-cap input");
                $(chks).prop( 'checked', ! $(chks).first().is(':checked') );
                return false;
            });

            $('div.cme-show-status-hint').click(function() {
                $(this).hide();
                $('div.pp-status-control-notice').show();
                return false;
            });
        });
        /* ]]> */
        </script>

        </div>

        <?php
    }
}
