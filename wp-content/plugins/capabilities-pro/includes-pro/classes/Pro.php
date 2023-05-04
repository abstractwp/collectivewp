<?php
namespace PublishPress\Capabilities;

use PublishPress\Capabilities\Factory;

/*
 * PublishPress Capabilities Pro
 * 
 * Pro functions and filter handlers with broad scope
 * 
 */

class Pro
{
    // object references
    private static $instance = null;

    public static function instance($args = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new Pro($args);
        }

        return self::$instance;
    }

    private function __construct()
    {

        if (function_exists('bbp_get_version')) {
            require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/classes/bbPress.php');
            new bbPress();
        }
    }

    static function customStatusPermissionsAvailable() {
        return defined('PUBLISHPRESS_VERSION') && class_exists('PP_Custom_Status');
    }

    static function customStatusPostMetaPermissions($post_type = '', $post_status = '') {
        if (!self::customStatusPermissionsAvailable() || !class_exists('PublishPress\Permissions')) {
            return false;
        }

        if ($post_status) {
            if (!$attributes = \PublishPress\Permissions\Statuses::attributes()) {
                return false;
            }

            if (empty($attributes->attributes['post_status']->conditions[$post_status])) {
                return false;
            }

            if ($post_type) {
                if ($status_obj = get_post_status_object($post_status)) {
                    if (!empty($status_obj->post_type) && !in_array($post_type, $status_obj->post_type)) {
                        return false;
                    }
                }
            }
        }

        $pp = \PublishPress\Permissions::instance();
        return $pp->moduleActive('status-control') && $pp->moduleActive('collaboration');
    }

    static function getStatusCaps($cap, $post_type, $post_status) {
        if (!self::customStatusPostMetaPermissions() || !class_exists('PublishPress\Permissions\Statuses')) {
            return [$cap];
        }

        if (!$attributes = \PublishPress\Permissions\Statuses::attributes()) {
            return [$cap];
        }

        if (!isset($attributes->attributes['post_status']->conditions[$post_status])) {
            return [$cap];
        }

        $caps = [];

        if (isset($attributes->condition_metacap_map[$post_type][$cap]['post_status'][$post_status])) {
            $caps = array_merge($caps, (array) $attributes->condition_metacap_map[$post_type][$cap]['post_status'][$post_status]);
        }

        if (!empty($attributes->condition_cap_map[$cap]['post_status'][$post_status])) {
            $caps = array_merge($caps, (array) $attributes->condition_cap_map[$cap]['post_status'][$post_status]);
        }

        return $caps;
    }

    /**
     * @return EDD_SL_Plugin_Updater
     */
    public function load_updater()
    {
    	require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Factory.php');
    	$container = \PublishPress\Capabilities\Factory::get_container();
		return $container['edd_container']['update_manager'];
    }
    
    public function keyStatus($refresh = false)
    {
        require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/pro-key.php');
        return _cme_key_status($refresh);
    }

    public function keyActive($refresh = false)
    {
        return in_array($this->keyStatus($refresh), [true, 'valid', 'expired'], true);                
    }

} // end class
