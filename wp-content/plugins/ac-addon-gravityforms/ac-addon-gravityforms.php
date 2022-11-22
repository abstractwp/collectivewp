<?php
/*
Plugin Name: 		Admin Columns Pro - Gravity Forms add-on
Version: 			1.2
Description: 		Adds columns to your Gravity Forms submissions
Author: 			AdminColumns.com
Author URI: 		https://admincolumns.com
Text Domain: 		codepress-admin-columns
*/

use AC\Autoloader;
use AC\Plugin\Version;
use ACA\GravityForms\Dependencies;
use ACA\GravityForms\GravityForms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

// Don't run the bootstrap during plugin updates
if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], [ 'update-plugin', 'do-plugin-upgrade', 'update-selected' ] ) ) {
	return;
}

define( 'ACA_GF_VERSION', '1.2' );

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new Dependencies( plugin_basename( __FILE__ ), ACA_GF_VERSION );
	$dependencies->requires_acp( '5.7' );
	$dependencies->requires_php( '5.6.3' );

	if ( ! class_exists( 'GFCommon' ) ) {
		$dependencies->add_missing_plugin( 'Gravity Forms', 'https://www.gravityforms.com/' );
	}

	$minimum_gf_version = '2.5';

	if ( class_exists( 'GFCommon' ) && version_compare( GFCommon::$version, $minimum_gf_version, '<' ) ) {
		$dependencies->add_missing_plugin( 'Gravity Forms', 'https://www.gravityforms.com/', $minimum_gf_version );
	}

	if ( $dependencies->has_missing() ) {
		return;
	}

	Autoloader::instance()->register_prefix( 'ACA\GravityForms', __DIR__ . '/classes/' );

	$addon = new GravityForms( __FILE__, new Version( ACA_GF_VERSION ) );
	$addon->register();
} );