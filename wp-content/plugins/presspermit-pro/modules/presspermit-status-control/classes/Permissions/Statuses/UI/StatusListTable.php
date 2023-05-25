<?php
namespace PublishPress\Permissions\Statuses\UI;



require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/StatusQuery.php');

class StatusListTable extends \WP_List_Table
{
    var $site_id;
    var $attribute;
    var $attrib_type;
    var $role_info;

    private static $instance = null;

    public static function instance($attrib_type) {
        if ( is_null(self::$instance) ) {
            self::$instance = new StatusListTable($attrib_type);
        }

        return self::$instance;
    }

    public function __construct($attrib_type)  // PHP 5.6.x and some PHP 7.x configurations prohibit restrictive subclass constructors
    {
        $screen = get_current_screen();

        // clear out empty entry from initial admin_header.php execution
        global $_wp_column_headers;
        if (isset($_wp_column_headers[$screen->id]))
            unset($_wp_column_headers[$screen->id]);

        add_filter("manage_{$screen->id}_columns", [$this, 'get_columns'], 0);

        parent::__construct([
            'singular' => 'status',
            'plural' => 'statuses'
        ]);

        $this->attribute = 'post_status';
        $this->attrib_type = $attrib_type;
    }

    function ajax_user_can()
    {
        return current_user_can('pp_define_post_status');
    }

    function prepare_items()
    {
        global $groupsearch;

        $args = [];

        // Query the user IDs for this page
        $pp_attrib_search = new StatusQuery($this->attribute, $this->attrib_type, $args);

        $this->items = $pp_attrib_search->get_results();

        $this->set_pagination_args([
            'total_items' => $pp_attrib_search->get_total(),
        ]);
    }

    function no_items()
    {
        esc_html_e('No matching statuses were found.', 'presspermit-pro');
    }

    function get_views()
    {
        return [];
    }

    function get_bulk_actions()
    {
        return [];
    }

    function get_columns()
    {
        $c = [
            'status' => esc_html__('Status')
        ];

        if (defined('PRESSPERMIT_COLLAB_VERSION') && ('moderation' == $this->attrib_type))
            $c['order'] = esc_html__('Order', 'presspermit-pro');

        $c = array_merge($c, [
            'cap_map' => esc_html__('Capability Mapping', 'presspermit-pro'),
            'post_types' => esc_html__('Post Types', 'presspermit-pro'),
            'enabled' => esc_html__('Capabilities'),
        ]);

        return $c;
    }

	protected function row_actions( $actions, $always_visible = false ) {
		$action_count = count( $actions );

		if ( ! $action_count ) {
			return '';
		}

		$mode = get_user_setting( 'posts_list_mode', 'list' );

		if ( 'excerpt' === $mode ) {
			$always_visible = true;
		}

		echo '<div class="' . ( $always_visible ? 'row-actions visible' : 'row-actions' ) . '">';

		$i = 0;

		foreach ( $actions as $action => $link ) {
			++$i;
			$sep = ( $i < $action_count ) ? ' | ' : '';
			echo "<span class='" . esc_attr($action) . "'>" . $link . esc_html($sep) . "</span>";
		}

		echo '</div>';

		echo '<button type="button" class="toggle-row"><span class="screen-reader-text">' . esc_html__( 'Show more details' ) . '</span></button>';
	}

    function display_tablenav($which)
    {
    }

    function get_sortable_columns()
    {
        $c = [];

        return $c;
    }

    function display_rows()
    {
        $class = '';

        foreach ($this->items as $cond_object) {
            $this->single_row($cond_object);
        }
    }

    /**
     * Generate HTML for a single row on the PP Role Groups admin panel.
     *
     * @param object $user_object
     * @param string $row_class Optional. Class to add to tr element.
     * @param int $num_users Optional. User count to display for this group.
     * @return string
     */
    function single_row($cond_obj)
    {
        static $base_url;
        static $disabled_conditions;

        $attrib = $this->attribute;
        $attrib_type = $this->attrib_type;

        if (!isset($base_url)) {
            $base_url = apply_filters('presspermit_conditions_base_url', 'admin.php');
            $disabled_conditions = presspermit()->getOption("disabled_{$attrib}_conditions");
        }

        $cond = $cond_obj->name;

        // Set up the hover actions for this user
        $actions = [];

        static $can_manage_cond;
        if (!isset($can_manage_cond))
            $can_manage_cond = current_user_can('pp_define_post_status');

        if ($is_publishpress = !empty($cond_obj->pp_custom)) {
            unset($disabled_conditions[$cond]);
        }

        // Check if the group for this row is editable
        if ($can_manage_cond && !in_array($cond, ['private', 'future']) && empty($disabled_conditions[$cond])) {
            $edit_link = $base_url . "?page=presspermit-status-edit&amp;action=edit&amp;status={$cond}";
            $actions['edit'] = '<a href="' . $edit_link . '">' . PWP::__wp('Edit') . '</a>';
        }

        if (in_array($cond, ['pending', 'future']) || (!empty($cond_obj->moderation) && $is_publishpress)) {
            if (!PPS::postStatusHasCustomCaps($cond))
                $actions['enable'] = "<a class='submitdelete' href='" . wp_nonce_url($base_url . "?page=presspermit-statuses&amp;pp_action=enable&amp;attrib_type=$attrib_type&amp;status=$cond", 'bulk-conditions') . "'>" . esc_html__('Enable Custom Capabilities', 'presspermit-pro') . "</a>";
            else
                $actions['disable'] = "<a class='submitdelete' href='" . wp_nonce_url($base_url . "?page=presspermit-statuses&amp;pp_action=disable&amp;attrib_type=$attrib_type&amp;status=$cond", 'bulk-conditions') . "'>" . esc_html__('Disable Custom Capabilities', 'presspermit-pro') . "</a>";
        } elseif ($cond && empty($cond_obj->builtin)) {
            if (!empty($disabled_conditions[$cond]))
                $actions['enable'] = "<a class='submitdelete' href='" . wp_nonce_url($base_url . "?page=presspermit-statuses&amp;pp_action=enable&amp;attrib_type=$attrib_type&amp;status=$cond", 'bulk-conditions') . "'>" . esc_html__('Enable', 'presspermit-pro') . "</a>";
            else
                $actions['disable'] = "<a class='submitdelete' href='" . wp_nonce_url($base_url . "?page=presspermit-statuses&amp;pp_action=disable&amp;attrib_type=$attrib_type&amp;status=$cond", 'bulk-conditions') . "'>" . esc_html__('Disable', 'presspermit-pro') . "</a>";
        } else
            $actions[''] = '&nbsp;';  // temp workaround to prevent shrunken row

        if (empty($cond_obj->_builtin) && !$is_publishpress && !in_array($cond, ['draft', 'pending', 'future'])) { // || ( ( 'moderation' == $attrib_type ) && ! in_array( $cond, [ 'draft', 'pending', 'pitch' ] ) && get_term_by( 'slug', $cond, 'post_status' ) ) ) {  // post_status taxonomy: PublishPress integration
            $actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url($base_url . "?page=presspermit-statuses&amp;pp_action=delete&amp;attrib_type=$attrib_type&amp;status=$cond", 'bulk-conditions') . "'>" . esc_html__('Delete') . "</a>";
        }

        $actions = apply_filters('presspermit_condition_row_actions', $actions, $attrib, $cond_obj);

        echo "<tr>";

        list($columns, $hidden) = $this->get_column_info();

        foreach ($columns as $column_name => $column_display_name) {
            $class = "$column_name column-$column_name";

            $style = (in_array($column_name, $hidden, true)) ? 'display:none;' : '';

            switch ($column_name) {
                case 'cb':
                    ?>
                    <th scope='row' class='check-column'>
                    <?php
                    if ($actions) {
                        echo "<input type='checkbox' name='pp_conditions[]' id='pp_condition_" . esc_attr($cond) . "' value='" . esc_attr($cond) . "' />";
                    }
                    ?>
                    </th>
                    <?php
                    break;
                case 'status':
                    echo "<td class='" . esc_attr($class) . "' style='" . esc_attr($style) . "'>";

                    // Check if the group for this row is editable
                    if ($can_manage_cond && !in_array($cond, ['private', 'future']) && empty($disabled_conditions[$cond])) {
                        $label = (!empty($cond_obj->status_parent)) ? "&mdash; {$cond_obj->label}" : $cond_obj->label;
                        echo "<strong><a href='" . esc_url($edit_link) . "'>" . esc_html($label) . '</a></strong><br />';
                    } else {
                        echo '<strong>' . esc_html($cond_obj->label) . '</strong>';
                    }

                    $this->row_actions($actions);
                    echo "</td>";
                    break;
                case 'order':
                    $order = (!empty($cond_obj->order)) ? $cond_obj->order : '';

                    if (!empty($cond_obj->status_parent)) {
                        $status_parent_obj = get_post_status_object($cond_obj->status_parent);
                        $status_parent_label = (!empty($status_parent_obj) && !empty($status_parent_obj->label)) ? $status_parent_obj->label : $status_parent;
                    }

                    if ($order && !empty($cond_obj->status_parent)) {
                        $order = "&mdash; $order";
                        $title = sprintf(__('Normal order for workflow progression within the %s branch.', 'presspermit-pro'), $status_parent_label);
                    } else {
                        if (!$order) {
                            if (!empty($cond_obj->status_parent)) {
                                $title = sprintf(__('This status will be available within the %s branch, but not offered as a default next step.', 'presspermit-pro'), $status_parent_label);
                            } else {
                                $title = __('This status will be available in the main workflow, but not offered as a default next step.', 'presspermit-pro');
                            }
                        } else {
                            $title = __('Normal order for workflow progression.', 'presspermit-pro');
                        }
                    }

                    echo "<td class='" . esc_attr($class) . "' title='" . esc_attr($title) . "'>" . esc_html($order) . "</td>";
                    break;
                case 'post_types':
                    echo "<td class='" . esc_attr($class) . "' style='" . esc_attr($style) . "'>";

                    if (!empty($cond_obj->post_type)) {
                        $arr_captions = [];
                        foreach ($cond_obj->post_type as $_post_type) {
                            if ($type_obj = get_post_type_object($_post_type)) {
                                $arr_captions [] = $type_obj->labels->singular_name;
                            }
                        }

                        $types_caption = implode(', ', array_slice($arr_captions, 0, 7));

                        if (count($arr_captions) > 7) {
                            printf(esc_html__('%s, more...', 'presspermit-pro'), esc_html($types_caption));
                        } else {
                            echo esc_html($types_caption);
                        }
                    } else {
                        esc_html_e('All');
                    }

                    echo "</td>";
                    break;
                case 'cap_map':
                    echo "<td class='" . esc_attr($class) . "' style='" . esc_attr($style) . "'><ul>";
                    
                    $maps = [];
                    if (!empty($cond_obj->metacap_map)) {
                        foreach ($cond_obj->metacap_map as $orig => $map) {
                            echo '<li>' . esc_html($orig) . ' > ' . esc_html($map) . '</li>';
                        }
                    }
                    if (!empty($cond_obj->cap_map)) {
                        foreach ($cond_obj->cap_map as $orig => $map) {
                            echo '<li>' . esc_html($orig) . ' > ' . esc_html($map) . '</li>';
                        }
                    }

                    echo "</ul></td>";
                    break;
                case 'enabled':
                    echo "<td class='" . esc_attr($class) . "' style='" . esc_attr($style) . "'>";

                    if (!empty($disabled_conditions[$cond])) {
                        esc_html_e('Disabled', 'presspermit-pro');
                
                    } elseif (in_array($cond, ['pending', 'future']) || ! empty($cond_obj->moderation) || $is_publishpress) {
                        if (!PPS::postStatusHasCustomCaps($cond)) {
                            esc_html_e('(Standard)', 'presspermit-pro');
                        } else {
                            if (!empty($cond_obj->capability_status) && ($cond_obj->capability_status != $cond)) {
                                if ($cap_status_obj = get_post_status_object($cond_obj->capability_status)) {
                                    printf(esc_html__('(same as %s)', 'presspermit-pro'), esc_html($cap_status_obj->label));
                                } else {
                                    esc_html_e('Custom', 'presspermit-pro');
                                }
                            } else {
                                esc_html_e('Custom', 'presspermit-pro');
                            }
                        }
                    } else {
                        esc_html_e('Enabled', 'presspermit-pro');
                    }
                    
                    echo "</td>";
                    break;
                default:
                    echo "<td class='" . esc_attr($class) . "' style='" . esc_attr($style) . "'>";
                    echo esc_html(apply_filters('presspermit_manage_conditions_custom_column', '', $column_name, $attrib, $cond));
                    echo "</td>";
            }
        }
        echo '</tr>';
    }
}
