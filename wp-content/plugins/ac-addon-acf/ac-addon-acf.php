<?php
/*
Plugin Name: 	Admin Columns Pro - Advanced Custom Fields (ACF)
Version: 		3.0.4
Description: 	Supercharges Admin Columns Pro with very cool ACF columns.
Author: 		AdminColumns.com
Author URI: 	https://www.admincolumns.com
Plugin URI: 	https://www.admincolumns.com
Text Domain: 	codepress-admin-columns
*/

use AC\Autoloader;
use AC\Plugin\Version;
use ACA\ACF\AdvancedCustomFields;
use ACA\ACF\Dependencies;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

// Don't run the bootstrap during plugin updates
if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], [ 'update-plugin', 'do-plugin-upgrade' ] ) ) {
	return;
}

require_once __DIR__ . '/classes/Dependencies.php';

define( 'ACA_ACF_VERSION', '3.0.4' );

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), ACA_ACF_VERSION );
	$dependencies->requires_acp( '5.7' );
	$dependencies->requires_php( '5.6.20' );

	if ( ! class_exists( 'acf', false ) ) {
		$dependencies->add_missing_plugin( 'Advanced Custom Fields', 'https://www.advancedcustomfields.com/' );
	}

	if ( $dependencies->has_missing() ) {
		return;
	}

	Autoloader::instance()->register_prefix( 'ACA\ACF', __DIR__ . '/classes/' );

	new AdvancedCustomFields( __FILE__, new Version( ACA_ACF_VERSION ) );
} );