<?php
namespace PublishPress\Permissions;

class Statuses {
    private static $instance = null;
    private static $attributes = null;

    public static function instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new Statuses();
        }

        return self::$instance;
    }

    private function __construct()
    {
        
    }

    public static function getCustomStatuses($args = [])
    {
        global $wp_post_statuses;

        $defaults = ['post_type' => '', 'ignore_moderation_statuses' => false, 'ignore_private_stati' => false];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $post_type = sanitize_key($post_type);

        $custom_stati = [];
        foreach ($wp_post_statuses as $status => $st) {
            if (
            (!$ignore_moderation_statuses && !empty($st->moderation) && empty($st->_builtin) && !in_array($status, ['pending', 'draft', 'future'])) 
            || (!$ignore_private_stati && !empty($st->private) && ('private' != $status))
            ) {
                $custom_stati [] = $status;
            }
        }

        return $custom_stati;
    }

    public static function customStatiUsed($args = [])
    {
        global $wpdb, $wp_post_statuses;

        $defaults = ['post_type' => '', 'ignore_moderation_statuses' => false, 'ignore_private_stati' => false, 'ignore_status' => []];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $custom_stati = self::getCustomStatuses($args);

        if (!empty($args['ignore_status'])) {
            $custom_stati = array_diff($custom_stati, (array)$args['ignore_status']);
        }

        $status_csv = implode("','", array_map('sanitize_key', $custom_stati));

        if ($post_type) {
            $post_exists = (int)$wpdb->get_var(
                $wpdb->prepare(
                    "SELECT ID FROM $wpdb->posts WHERE post_status IN ('$status_csv') AND post_type = %s LIMIT 1",
                    $post_type
                )
            );
        } else {
            $post_exists = (int)$wpdb->get_var(
                "SELECT ID FROM $wpdb->posts WHERE post_status IN ('$status_csv') LIMIT 1"
            );
        }

        return $post_exists;
    }

    public static function privacyStatusesDisabled() {
        // This replaces constant PPS_NATIVE_CUSTOM_STATI_DISABLED (previously defined dynamically in StatusesHooks::actRegistrations())
        return !presspermit()->getOption('privacy_statuses_enabled');
        
        // additional previous conditions:
        /*&& defined('PUBLISHPRESS_VERSION') && version_compare(PUBLISHPRESS_VERSION, '1.19', '>=') && PWP::isBlockEditorActive()*/
    }

    public static function customStatusesEnabled($post_type = '', $ignore_status = [])
    {
        global $wp_post_statuses;

        $ignore_status = (array)$ignore_status;

        foreach ($wp_post_statuses as $status => $st) {
            if (!in_array($status, $ignore_status, true) && ((!empty($st->moderation) && !in_array($status, ['private', 'future'])) 
            || (!empty($st->private) && ('private' != $status))) && empty($st->_builtin)) {

                if (!$post_type || !isset($st->post_type) 
                || (is_array($st->post_type) && (!$st->post_type || in_array($post_type, $st->post_type)))
                ) {    
                    return true;
                }
            }
        }

        return false;
    }

    public static function defaultStatusOrder()
    {
        return [
            'draft' => 0,
            'pitch' => 2,
            'assigned' => 5,
            'in-progress' => 7,
            'pending' => 10,
            'pending-review' => 10,  // @todo
            'approved' => 18,
        ];
    }

    public static function attributes()
    {
        if ( is_null(self::$attributes) ) {
            if (!did_action('pp_registrations')) {
                require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Attributes.php');
                self::$attributes = new Statuses\Attributes();
                self::$attributes->process_status_caps();
            }
        }

        return self::$attributes;
    }

    public static function registerConditions($wp_status_objects)
    {
        // register each custom post status as an attribute condition with mapped caps
        foreach ($wp_status_objects as $status => $status_obj) {
            if (!empty($status_obj->private)) {
                self::registerCondition('force_visibility', $status, ['label' => $status_obj->label]);

                $suppress_edit_caps = defined('PP_SUPPRESS_PRIVACY_EDIT_CAPS') || !PPS_CUSTOM_PRIVACY_EDIT_CAPS;

                $_status = (isset($status_obj->capability_status) && ($status != $status_obj->capability_status)) 
                ? $status_obj->capability_status 
                : $status;
                
                $metacap_map = ($suppress_edit_caps) 
                ? ['read_post' => "read_{$_status}_posts", 'edit_post' => "edit_private_posts", 'delete_post' => "delete_private_posts"] 
                : ['read_post' => "read_{$_status}_posts", 'edit_post' => "edit_{$_status}_posts", 'delete_post' => "delete_{$_status}_posts"];
                
                $cap_map = ($suppress_edit_caps) ? [] : ['set_posts_status' => "set_posts_{$_status}"];

                self::registerCondition('post_status', $status, [
                    'label' => $status_obj->label,
                    'metacap_map' => $metacap_map,
                    'cap_map' => $cap_map,
                    'pattern_role_availability_requirement' => [
                        'edit_posts' => 'edit_published_posts', 
                        'delete_posts' => 'delete_published_posts'
                    ],
                ]);
            }
        }
    }

    // args:
    //   label = translated string
    //   cap_map = ['base_cap_property' => restriction_cap_pattern] where restriction_cap_pattern may contain "_posts" 
    //      (will be converted to plural name of obj type)
    //
    //   metacap_map = ['meta_cap' => restriction_cap_pattern]
    //
    //   exemption_cap = base cap property corresponding to a capability whose presence in a role indicates the role 
    //      should be credited with all caps for this status 
    //      (i.e. if a role has $cap->publish_posts, it also has all 'restricted_submission' caps) 
    public static function registerCondition($attribute, $condition, $args = [])
    {
        $defaults = ['label' => $condition, 'cap_map' => [], 'metacap_map' => []];
        $args = array_merge($defaults, $args);

        $attributes = self::attributes();

        if (!isset($attributes->attributes[$attribute]))
            return;

        $args['name'] = $condition;
        $attributes->attributes[$attribute]->conditions[$condition] = (object)$args;
    }

   /*
    * args:
    *  label = translated string
    */
    public static function registerAttribute($attribute, $source_name = 'post', $args = [])
    {
        $defaults = ['label' => $attribute, 'taxonomies' => []];
        $args = array_merge($defaults, $args);
        $args['conditions'] = [];
        $args['source_name'] = $source_name;

        self::attributes()->attributes[$attribute] = (object)$args;
    }

   /*
    * set_conditions[attribute][condition] = true
    * args : ['force_flush' => false]
    */ 
    public static function setItemCondition(
        $attribute, $scope, $item_source, $item_id, $set_conditions, $assign_for = 'item', $args = [])
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/DB/AttributesUpdate.php');

        return Statuses\DB\AttributesUpdate::set_item_condition(
            $attribute, $scope, $item_source, $item_id, $set_conditions, $assign_for, $args
        );
    }

    /*
     * args : ['inherited_only' => false]
     */ 
    public static function clearItemCondition($attribute, $scope, $item_source, $item_id, $assign_for, $args = [])
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/DB/AttributesUpdate.php');

        return Statuses\DB\AttributesUpdate::clear_item_condition(
            $attribute, $scope, $item_source, $item_id, $assign_for, $args
        );
    }

    public static function postStatusHasCustomCaps($status)
    {
        return !empty(self::attributes()->attributes['post_status']->conditions[$status]);
    }

    public static function filterAvailablePostStatuses($statuses, $post_type, $post_status)
    {
        if (!$post_type) {
            return $statuses;
        }

        // convert integer keys to slugs
        foreach ($statuses as $status => $obj) {
            if (is_numeric($status)) {
                $statuses[$obj->name] = $obj;
                unset($statuses[$status]);
            }

            if (!empty($obj->post_type) && !in_array($post_type, $obj->post_type)) {
                unset($statuses[$obj->name]);
            }
        }

        $can_set_status = self::getUserStatusPermissions('set_status', $post_type, $statuses);

        $can_set_status[$post_status] = true;

        return array_intersect_key($statuses, array_filter($can_set_status));
    }

    public static function defaultStatusProgression($post_id = 0, $args = [])
    {
        $defaults = ['return' => 'object', 'moderation_statuses' => [], 'can_set_status' => [], 'force_main_channel' => false, 'post_type' => '', 'default_by_sequence' => null, 'skip_current_status_check' => false];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $post_type = sanitize_key($post_type);

        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Workflow.php');

        if (!$status_obj = Statuses\Workflow::getNextStatusObject($post_id, $args)) {
            $status_obj = get_post_status_object('draft');
        }

        return ('name' == $return) ? $status_obj->name : $status_obj;
    }

    public static function orderStatuses($statuses = false, $args = [])
    {
        // defaults: 'min_order' => 0, 'status_parent' => false, 'ignore_status' => [], 'include_status' => []
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Workflow.php');
        return Statuses\Workflow::orderStatuses($statuses, $args);
    }

    public static function getStatusChildren($status, $statuses = false)
    {
        if (!get_post_status_object($status)) {
            return [];
        }

        if (false === $statuses) {
            $statuses = PWP::getPostStatuses(['internal' => false], 'object');
        } else {
            $statuses = (array)$statuses;
        }

        $return = [];

        foreach ($statuses as $other_status_obj) {
            if (!empty($other_status_obj->status_parent) && ($status == $other_status_obj->status_parent)) {
                $key = (isset($other_status_obj->slug)) ? $other_status_obj->slug : strtolower($other_status_obj->name);
                $return [$key] = $other_status_obj;
            }
        }

        return $return;
    }

    public static function havePermission($perm_name, $args = [])
    {
        $defaults = ['force_refresh' => false];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $user = presspermit()->getUser();

        if (!isset($user->cfg[$perm_name])) {
            $user->cfg[$perm_name] = [];
        }

        if (!$force_refresh) {
            // requested values already cached
            return $user->cfg[$perm_name];
        }

        switch ($perm_name) {
            case 'moderate_any':
                $return = !empty($user->allcaps['pp_moderate_any']);
                break;
            default:
        }

        $user->cfg[$perm_name] = $return;
        return $return;
    }

    public static function haveStatusPermission($perm_name, $post_type, $post_status, $args = [])
    {
        $perms = self::getUserStatusPermissions($perm_name, $post_type, $post_status, $args);
        return !empty($perms[$post_status]);
    }

    public static function getUserStatusPermissions($perm_name, $post_type, $check_statuses, $args = [])
    {
        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/Workflow.php');
        return Statuses\Workflow::getUserStatusPermissions($perm_name, $post_type, $check_statuses, $args);
    }

    public static function publishpressStatusesActive($post_type = '', $args = [])
    {
        global $publishpress;
        $args = (array)$args;

        if (!defined('PUBLISHPRESS_VERSION')) {
            return false;
        }

        if (!empty($publishpress) && !empty($publishpress->custom_status) && !empty($publishpress->custom_status->options)) {
            $optval = $publishpress->custom_status->options;

        } else {
            // (note: PublishPress modules may not be loaded yet.)
            $optval = get_option('publishpress_custom_status_options');
        }

        if (!empty($optval)) {
            // We need PublishPress Statuses module active 
            if (isset($optval->enabled) && (empty($optval->enabled) || ('off' == $optval->enabled))) {
                return false;
            }

            // For Gutenberg, we need status dropdown enabled
            if (empty($args['skip_status_dropdown_check'])) {
                if (PWP::isWp5() && isset($optval->always_show_dropdown) && (empty($optval->always_show_dropdown) 
                || 'off' == $optval->always_show_dropdown)
                ) {
                    return false;
                }
            }

            if ($post_type) {
                if (!empty($optval->post_types) && isset($optval->post_types[$post_type]) 
                && (empty($optval->post_types[$post_type]) || ('off' == $optval->post_types[$post_type]))
                ) {
                    return false;
                }
            }
        }

        return true;
    }
}
