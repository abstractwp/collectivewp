<?php
/**
 * Plugin Name: PublishPress Capabilities Pro
 * Plugin URI: https://publishpress.com/
 * Description: Manage WordPress role definitions, per-site or network-wide. Organizes post capabilities by post type and operation.
 * Author: PublishPress
 * Author URI: https://publishpress.com/
 * Version: 2.10.1
 * Text Domain: capabilities-pro
 * Domain Path: /languages/
 * Requires at least: 5.5
 * Requires PHP: 7.2.5
 * License: GPLv3
 *
 * Copyright (c) 2023 PublishPress
 *
 * ------------------------------------------------------------------------------
 * Based on Capability Manager
 * Author: Jordi Canals
 * Copyright (c) 2009, 2010 Jordi Canals
 * ------------------------------------------------------------------------------
 *
 * @package 	capabilities-pro
 * @author		PublishPress
 * @copyright   Copyright (C) 2023 PublishPress
 * @license		GNU General Public License version 3
 * @link		https://publishpress.com/
 */
global $wp_version;

$min_php_version = '7.2.5';
$min_wp_version  = '5.5';

$invalid_php_version = version_compare(phpversion(), $min_php_version, '<');
$invalid_wp_version = version_compare($wp_version, $min_wp_version, '<');

if ($invalid_php_version || $invalid_wp_version) {
	return;
}

if (!defined('PP_CAPABILITIES_PRO_LIB_VENDOR_PATH')) {
	define('PP_CAPABILITIES_PRO_LIB_VENDOR_PATH', __DIR__ . '/lib/vendor');
}

$instanceProtectionIncPath = PP_CAPABILITIES_PRO_LIB_VENDOR_PATH . '/publishpress/instance-protection/include.php';
if (is_file($instanceProtectionIncPath) && is_readable($instanceProtectionIncPath)) {
	require_once $instanceProtectionIncPath;
}

if (class_exists('PublishPressInstanceProtection\\Config')) {
	$pluginCheckerConfig = new PublishPressInstanceProtection\Config();
	$pluginCheckerConfig->pluginSlug = 'capabilities-pro';
	$pluginCheckerConfig->pluginName = 'PublishPress Capabilities Pro';
	$pluginCheckerConfig->isProPlugin = true;
	$pluginCheckerConfig->freePluginName = 'PublishPress Capabilities';

	$pluginChecker = new PublishPressInstanceProtection\InstanceChecker($pluginCheckerConfig);
}

$autoloadFilePath = PP_CAPABILITIES_PRO_LIB_VENDOR_PATH . '/autoload.php';
if (
	!class_exists('ComposerAutoloaderInitPublishPressCapabilitiesPro')
	&& is_file($autoloadFilePath)
	&& is_readable($autoloadFilePath)
) {
	require_once $autoloadFilePath;
}


$includeFilebRelativePath = '/publishpress/publishpress-instance-protection/include.php';
if (file_exists(__DIR__ . '/vendor' . $includeFilebRelativePath)) {
	require_once __DIR__ . '/vendor' . $includeFilebRelativePath;
}

add_action('plugins_loaded', function () {

	if (!defined('CAPSMAN_VERSION')) {
		define('CAPSMAN_VERSION', '2.10.1');
		define('PUBLISHPRESS_CAPS_VERSION', CAPSMAN_VERSION);
	}

	if (!defined('PUBLISHPRESS_CAPS_PRO_VERSION')) {
		define('PUBLISHPRESS_CAPS_PRO_VERSION', CAPSMAN_VERSION);
		define('PUBLISHPRESS_CAPS_EDD_ITEM_ID', 44811);
	}

	foreach ((array)get_option('active_plugins') as $plugin_file) {
		if (false !== strpos($plugin_file, 'capsman.php')) {
			add_action('admin_notices', function () {
				echo '<div id="message" class="error fade" style="color: black">' . esc_html__('<strong>Error:</strong> PublishPress Capabilities cannot function because another copy of the plugin is active.', 'capsman-enhanced') . '</div>';
			});
			return;
		}
	}

	if (defined('CME_FILE')) {
		return;
	}

	define('CME_FILE', __FILE__);
	define('PUBLISHPRESS_CAPS_ABSPATH', __DIR__);

	require_once(dirname(__FILE__) . '/includes/functions.php');

	// ============================================ START PROCEDURE ==========

	add_action('init', '_cme_init');
	add_action('plugins_loaded', '_cme_act_pp_active', 1);

	add_action('init', '_cme_cap_helper', 49);  // Press Permit Cap Helper, registered at 50, will leave caps which we've already defined
	//add_action( 'wp_loaded', '_cme_cap_helper_late_init', 99 );	// now instead adding registered_post_type, registered_taxonomy action handlers for latecomers
	// @todo: do this in PP Core also

	// *** Pro includes ***

	require_once(dirname(__FILE__) . '/includes-pro/load.php');

	if (is_admin()) {
		// @todo: refactor
		require_once(dirname(__FILE__) . '/includes/functions-admin.php');

		global $capsman_admin;
		require_once(dirname(__FILE__) . '/includes/admin-load.php');
		$capsman_admin = new PP_Capabilities_Admin_UI();

		require_once(dirname(__FILE__) . '/includes-pro/functions-admin.php');
		require_once(dirname(__FILE__) . '/includes-pro/admin-load.php');
		new \PublishPress\Capabilities\AdminFiltersPro();
	}

	if (is_multisite()) {
		require_once(dirname(__FILE__) . '/includes/network.php');
	}

	do_action('publishpress_capabilities_loaded');
}, -10);
