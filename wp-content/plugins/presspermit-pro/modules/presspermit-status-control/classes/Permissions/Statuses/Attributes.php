<?php
namespace PublishPress\Permissions\Statuses;

/**
 * Attributes
 *
 * @package PressPermit
 * @author Kevin Behrens
 * @copyright Copyright (c) 2019 PublishPress
 *
 * Custom status capabilities are implemented by defining 'post_status' as an Attribute.
 * 
 * For any attribute, the generic equivalent of "status" is "condition"
 * 
 * This class also provides for retrieval of 'force_visibility' conditions, 
 * which are stored in the conditions DB table
 * 
 */
class Attributes
{
    // Custom status capabilities are implemented by defining 'post_status' as an Attribute.

    var $attributes = [];               // attributes[attribute] = object with the following properties: conditions, label, taxonomies
    var $condition_cap_map = [];        // condition_cap_map[basic_cap_name][attribute][condition] = condition_cap_name
    var $condition_metacap_map = [];    // condition_metacap_map[object_type][basic_metacap_name][attribute][condition] = condition_cap_name
    var $all_custom_condition_caps = [];
    var $pattern_role_cond_caps = [];
    var $processed_statuses = [];
    var $all_privacy_caps;
    var $all_moderation_caps;

    function __construct()
    {
        add_filter('presspermit_pattern_role_caps', [$this, 'fltLogPatternRoleCondCaps']);
        add_filter('presspermit_exclude_arbitrary_caps', [$this, 'fltExcludeArbitraryCaps']);
        add_filter('presspermit_get_typecast_caps', [$this, 'fltGetTypecastCaps'], 10, 3);
        add_filter('presspermit_administrator_caps', [$this, 'fltAdministratorCaps']);
        add_filter('presspermit_base_cap_replacements', [$this, 'fltBaseCapReplacements'], 10, 3);
    }

    function fltBaseCapReplacements($replace_caps, $reqd_caps, $object_type)
    {
        static $busy;

        if (!empty($busy)) {
            return $replace_caps;
        }

        $busy = true;

        if (isset($this->all_privacy_caps[$object_type]) && is_array($this->all_privacy_caps[$object_type])) {
            if ($cond_caps = array_intersect_key(
                $this->all_privacy_caps[$object_type], 
                array_fill_keys($reqd_caps, true)
            )) {
                // note: for author's editing access to their own post, private and custom private post_status caps 
                // will be removed, but _published caps will remain
                $replace_caps = array_merge($cond_caps, $replace_caps);
            }
        }

        foreach($this->condition_cap_map as $type_cap => $cond_caps) {
            if (!empty($cond_caps['post_status'])) {
                if ($base_caps = \PublishPress\Permissions\PostFilters::getBaseCaps([$type_cap], $object_type)) {
                    if (array_diff([$type_cap], $base_caps)) {
                        $replace_caps = array_merge($replace_caps, array_fill_keys($cond_caps['post_status'], reset($base_caps)));
                    }
                }
            }
        }

        $busy = false;

        return $replace_caps;
    }

    function fltAdministratorCaps($caps)
    {
        return array_merge($caps, array_fill_keys(array_keys(Arr::flatten($this->all_custom_condition_caps)), true));
    }

    function fltGetTypecastCaps($caps, $arr_name, $type_obj)
    {
        $base_role_name = $arr_name[0];
        $source_name = $arr_name[1];
        $object_type = $arr_name[2];
        $attribute = (!empty($arr_name[3])) ? $arr_name[3] : '';
        $condition = (!empty($arr_name[4])) ? $arr_name[4] : '';

        // If the typecast role assignment is for a specific condition (i.e. custom post_status), only add caps for that condition
        if ($attribute) {
            // -------- exclude "published_" and "private_" caps from typecasting except for custom private post status -----------

            // NOTE: post_status casting involves both read and edit caps (whichever are in pattern rolecaps).
            if ('post_status' == $attribute) {
                $status_obj = get_post_status_object($condition);

                if (!$status_obj || (('private' != $condition) && !PPS::postStatusHasCustomCaps($condition))) {
                    return [];
                }

                // Due to complications with the publishing metabox and WP edit_post() handler, 'publish' and 'private' 
                // caps need to be included with custom privacy caps. However, it's not necessary to include the deletion 
                // caps. Withhold them unless an Editor role is assigned for standard statuses, unless constant is defined.
                if (!defined('PP_LEGACY_STATUS_CAPS')) {
                    $caps = array_diff_key(
                        $caps, 
                        array_fill_keys(['delete_published_posts', 'delete_private_posts', 'delete_others_posts'], true)
                    );
                }

                if (empty($status_obj) || !$status_obj->private) {
                    $caps = array_diff_key(
                        $caps, 
                        array_fill_keys([
                            'edit_published_posts', 
                            'publish_posts', 
                            'read_private_posts', 
                            'edit_private_posts', 
                            'delete_published_posts', 
                            'delete_private_posts'
                        ], true)
                    );
                }

                if ($status_obj->private && presspermit()->getOption('custom_privacy_edit_caps') 
                && !defined('PP_LEGACY_STATUS_EDIT_CAPS')
                ) {
                    $caps = array_diff_key($caps, array_fill_keys(['edit_published_posts'], true));
                }
            }
            // ---------------------------------------------------------------------------------------------------------

            $match_caps = $caps;
            if (isset($caps[PRESSPERMIT_READ_PUBLIC_CAP]))
                $match_caps['read_post'] = 'read_post';

            if (isset($caps['edit_posts']))
                $match_caps['edit_post'] = 'edit_post';

            if (isset($caps['delete_posts']))
                $match_caps['delete_post'] = 'delete_post';

            $caps = $this->getConditionCaps($match_caps, $object_type, $attribute, $condition, ['merge_caps' => $caps]);

        } elseif ('term_taxonomy' != $source_name) {
            $plural_name = (isset($type_obj->plural_name)) ? $type_obj->plural_name : $object_type . 's';

            // also cast all condition caps which are in the pattern role
            if (!empty($this->pattern_role_cond_caps[$base_role_name])) {
                foreach (array_keys($this->pattern_role_cond_caps[$base_role_name]) as $cap_name) {
                    $cast_cap_name = str_replace('_posts', "_{$plural_name}", $cap_name);
                    $caps[$cast_cap_name] = $cast_cap_name;
                }
            }
        }

        return $caps;
    }

    function fltLogPatternRoleCondCaps($pattern_role_caps)
    {
        foreach (array_keys($pattern_role_caps) as $role_name) {
            // log condition caps for the "post" type
            if (isset($this->all_custom_condition_caps['post'])) {
                $this->pattern_role_cond_caps[$role_name] = array_intersect_key(
                    $pattern_role_caps[$role_name], $this->all_custom_condition_caps['post']
                );
            } else {
                $this->pattern_role_cond_caps[$role_name] = [];
            }
        }
        return $pattern_role_caps;
    }

    // prevent condition caps from being included when a pattern role is assigned without any condition specification
    function fltExcludeArbitraryCaps($exclude_caps)
    {
        return array_merge($exclude_caps, Arr::flatten($this->all_custom_condition_caps));
    }

    function is_metacap($caps)
    {
        return (bool)array_intersect((array)$caps, ['read_post', 'read_page', 'edit_post', 'edit_page', 'delete_post', 'delete_page']);
    }

    function process_status_caps()
    {
        global $wp_post_statuses;

        if (empty($this->all_custom_condition_caps)) {
            $this->all_custom_condition_caps = ['post' => []];
            $this->all_privacy_caps = ['post' => []];
            $this->all_moderation_caps = ['post' => []];
        }

        if (isset($this->attributes['post_status'])) {
            foreach (array_keys($this->attributes['post_status']->conditions) as $cond) {
                if (isset($this->processed_statuses[$cond])) continue;

                $this->processed_statuses[$cond] = true;

                $status_obj = (!empty($wp_post_statuses[$cond])) ? $wp_post_statuses[$cond] : false;

                foreach (presspermit()->getEnabledPostTypes([], 'object') as $object_type => $type_obj) {
                    // convert 'edit_restricted_posts' to 'edit_restricted_pages', etc.
                    $plural_name = (isset($type_obj->plural_name)) ? $type_obj->plural_name : $object_type . 's';

                    // Map condition caps to post meta caps( 'edit_post', 'delete_post', etc. ) because:
                    //  (1) mapping to expanded caps is problematic b/c for private posts, 'edit_private_posts' is required but 'edit_posts' is not
                    //  (2) WP converts type-specific meta caps back to basic metacap equivalent before calling 'map_meta_cap'
                    foreach (
                        $this->attributes['post_status']->conditions[$cond]->metacap_map 
                        as $base_cap_property => $condition_cap_pattern
                    ) {
                        // If the type object has "edit_restricted_posts" defined, use it.
                        $replacement_cap = (isset($type_obj->cap->$condition_cap_pattern)) 
                        ? $type_obj->cap->$condition_cap_pattern 
                        : str_replace('_posts', "_{$plural_name}", $condition_cap_pattern);

                        $this->condition_metacap_map[$object_type][$base_cap_property]['post_status'][$cond] = $replacement_cap;

                        switch ($base_cap_property) {
                            case 'read_post':
                                $type_cap = PRESSPERMIT_READ_PUBLIC_CAP;
                                break;
                            case 'edit_post':
                                $type_cap = $type_obj->cap->edit_posts;
                                break;
                            case 'delete_post':
                                if (isset($type_obj->cap->delete_posts))
                                    $type_cap = $type_obj->cap->delete_posts;
                                else
                                    $type_cap = str_replace('edit_', 'delete_', $type_obj->cap->edit_posts);
                                break;
                            default:
                                $type_cap = $base_cap_property;
                        }
                        $this->all_custom_condition_caps[$object_type][$replacement_cap] = $type_cap;

                        if (!empty($status_obj->private))
                            $this->all_privacy_caps[$object_type][$replacement_cap] = $type_cap;

                        if (!empty($status_obj->moderation))
                            $this->all_moderation_caps[$object_type][$replacement_cap] = $type_cap;
                    }

                    foreach (
                        $this->attributes['post_status']->conditions[$cond]->cap_map 
                        as $base_cap_property => $condition_cap_pattern
                    ) {
                        // If the type object has "edit_restricted_posts" defined, use it.
                        $replacement_cap = (isset($type_obj->cap->$condition_cap_pattern)) 
                        ? $type_obj->cap->$condition_cap_pattern 
                        : str_replace('_posts', "_{$plural_name}", $condition_cap_pattern);

                        $cap_name = (isset($type_obj->cap->$base_cap_property)) 
                        ? $type_obj->cap->$base_cap_property 
                        : $base_cap_property;

						// Previous versions defined edit_others capability as "edit_others_{$status_name}_attachments" for all post types
                        if (!isset($this->condition_cap_map[$cap_name]['post_status'][$cond]) || defined('PRESSPERMIT_LEGACY_STATUS_CAP_DEFINITIONS')) {
                            $this->condition_cap_map[$cap_name]['post_status'][$cond] = $replacement_cap;
                        }

                        $this->all_custom_condition_caps[$object_type][$replacement_cap] = $cap_name;

                        if (!empty($status_obj->private)) {
                            $this->all_privacy_caps[$object_type][$replacement_cap] = $cap_name;
                        }

                        if (!empty($status_obj->moderation)) {
                            $this->all_moderation_caps[$object_type][$replacement_cap] = $cap_name;
                        }
                    }
                } // end foreach object type
            } // end foreach condition

            $moderation_statuses = get_post_stati(['moderation' => true, '_builtin' => false], 'names');

            // Support use of PublishPress-defined status change capability where type-specific status capabilities are not enabled
            foreach($this->condition_cap_map as $cap_name => $conditions) {
                if (0 === strpos($cap_name, 'set_') && !empty($conditions['post_status'])) {
                    foreach($moderation_statuses as $status) {
                        if (!isset($this->condition_cap_map[$cap_name]['post_status'][$status]) && !in_array($status, ['draft', 'future'])) {
                            $_status = str_replace('-', '_', $status);
                            $this->condition_cap_map[$cap_name]['post_status'][$status] = "status_change_{$_status}";
                        }
                    }
                }
            }
        }
    }

    function getConditionCaps($reqd_caps, $object_type, $attribute, $conditions, $args = [])
    {
        $defaults = ['merge_caps' => []];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        if ($merge_caps) {
            $merge_caps = array_map('sanitize_key', (array) $merge_caps);
        }

        $cond_caps = [];

        $reqd_caps = (array)$reqd_caps;

        foreach ($reqd_caps as $base_cap) {
            foreach ((array)$conditions as $cond) {
                if (!empty($this->condition_cap_map[$base_cap][$attribute][$cond])) {
                    $cond_caps[] = $this->condition_cap_map[$base_cap][$attribute][$cond];
                }

                if (!empty($this->condition_metacap_map[$object_type][$base_cap][$attribute][$cond])) {
                    $cond_caps[] = $this->condition_metacap_map[$object_type][$base_cap][$attribute][$cond];
                }
            }
        }

        if ($merge_caps) {
            $cond_caps = array_merge($cond_caps, $merge_caps);

            // If a status-specific edit_others_{$status}_posts capability is defined, don't also assign / require edit_others_posts
            foreach(['edit_others_posts', 'delete_others_posts'] as $base_cap) {
                if (!empty($this->condition_cap_map[$base_cap][$attribute][$cond])) {
                    if ($type_obj = get_post_type_object($object_type)) {
                        if (!empty($type_obj->cap->$base_cap)) {
                            $cond_caps = array_diff($cond_caps, [$type_obj->cap->$base_cap]);
                        }
                    }
                }
            }
        }

        return array_unique($cond_caps);
    }

   /*
    * returns array [item_id][condition] = true or (if return_array=true) [ 'inherited_from' => $row->inherited_from ]
    * source_name = item source name (i.e. 'post') 
    */
    function getItemCondition($source_name, $attribute, $args = [])
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/AttributesAdmin.php');

        return apply_filters(
            'presspermit_getItemCondition', 
            AttributesAdmin::getItemCondition(
                $source_name, 
                $attribute, 
                $args
            ), 
            $source_name, 
            $attribute, 
            $args
        );
    }
}
