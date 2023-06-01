<?php
/*
Plugin Name: CollectiveWP Functions
Description: Project specific functions plugin for this site
Version: 0.1.0
Author: Abstract WP
Author URI: https://www.abstractwp.com
Text Domain: collectivewp
Domain Path: /languages
*/

define( 'COLLECTIVEWP_PATH', plugin_dir_path( __FILE__ ) );
include( COLLECTIVEWP_PATH . 'includes/linkedin-functions.php');

/**
 * Disable core block features.
 */
function disable_core_block_features() {
	remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	remove_theme_support( 'core-block-patterns' );
	add_filter( 'use_widgets_block_editor', '__return_false' );
}
add_action( 'plugins_loaded', 'disable_core_block_features' );

/*
 * Blacklist specific Gutenberg blocks
 *
 * @author Misha Rudrastyh
 * @link https://rudrastyh.com/gutenberg/remove-default-blocks.html#blacklist-blocks
 */
add_filter( 'allowed_block_types_all', 'collectivewp_blacklist_blocks' );

function collectivewp_blacklist_blocks( $allowed_blocks ) {
	// get all the registered blocks.
	$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	// then disable some of them.
	unset( $blocks[ 'core/navigation' ] );
	unset( $blocks[ 'core/site-logo' ] );
	unset( $blocks[ 'core/site-title' ] );
	unset( $blocks[ 'core/site-tagline' ] );
	unset( $blocks[ 'core/query' ] );
	unset( $blocks[ 'core/posts-list' ] );
	unset( $blocks[ 'core/avatar' ] );
	unset( $blocks[ 'core/post-title' ] );
	unset( $blocks[ 'core/post-excerpt' ] );
	unset( $blocks[ 'core/post-featured-image' ] );
	unset( $blocks[ 'core/post-content' ] );
	unset( $blocks[ 'core/post-author' ] );
	unset( $blocks[ 'core/post-date' ] );
	unset( $blocks[ 'core/post-terms' ] );
	unset( $blocks[ 'core/post-navigation-link' ] );
	unset( $blocks[ 'core/read-more' ] );
	unset( $blocks[ 'core/comments-query-loop' ] );
	unset( $blocks[ 'core/post-comments-form' ] );
	unset( $blocks[ 'core/loginout' ] );
	unset( $blocks[ 'core/term-description' ] );
	unset( $blocks[ 'core/query-title' ] );
	unset( $blocks[ 'core/post-author-biography' ] );
	unset( $blocks[ 'core/comments' ] );

	// return the new list of allowed blocks.
	return array_keys( $blocks );

}

/**
 * Remove embed blocks.
 */
function collectivewp_deny_embed_variations_blocks() {
	wp_enqueue_script( 'deny-list-blocks', plugin_dir_url( __FILE__ ) . 'js/list-blocks.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' )
);
}
add_action( 'enqueue_block_editor_assets', 'collectivewp_deny_embed_variations_blocks' );

/**
 * Disable Default Dashboard Widgets, Yoast, Gravity Forms
 *
 * @link https://digwp.com/2014/02/disable-default-dashboard-widgets/
 *
 */
function collectivewp_disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// wp..
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_activity' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_right_now' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_recent_comments' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_incoming_links' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_plugins' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_site_health' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'wsal' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'cookiebot_status' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'fluentsmtp_reports_widget' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'wpe_dify_news_feed' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_primary' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_secondary' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_quick_press' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_recent_drafts' ]);

	// bbpress
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'bbp-dashboard-right-now' ]);
	// yoast seo
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'yoast_db_widget' ]);
	// gravity forms
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'rg_forms_dashboard' ]);
}
add_action('wp_dashboard_setup', 'collectivewp_disable_default_dashboard_widgets', 999);

/**
 * Remove screen options
 *
 * @link https://www.wpbeginner.com/wp-tutorials/how-to-disable-the-screen-options-button-in-wordpress/
 */
function collectivewp_remove_screen_options() {
	return false;
}
add_filter('screen_options_show_screen', 'collectivewp_remove_screen_options');

/**
 * Add or Remove links from the Admin Bar
 *
 * @link https://digwp.com/2011/04/admin-bar-tricks/#add-remove-links
 *
 * @link https://www.isitwp.com/remove-wordpress-logo-admin-bar/
 *
 * @link https://wordpress.stackexchange.com/questions/200296/how-to-remove-customize-from-admin-menu-bar-after-wp-4-3/201646
 *
 */
function collectivewp_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' ); // remove comments
	$wp_admin_bar->remove_menu( 'wp-logo' ); // remove WordPress menu
	$wp_admin_bar->remove_menu( 'updates' ); // remove updates
	$wp_admin_bar->remove_menu( 'new-content' ); // remove add new
	$wp_admin_bar->remove_menu( 'customize' ); // remove customizer
}
add_action( 'wp_before_admin_bar_render', 'collectivewp_admin_bar_render' );

/**
 * Remove the Howdy Text in WordPress
 *
 * @link https://wpintensity.com/change-howdy-text-wordpress/
 *
 */
function collectivewp_remove_howdy( $wp_admin_bar ) {
	if (is_admin()) {
		$my_account = $wp_admin_bar->get_node('my-account');
		$newtitle   = str_replace( 'Howdy,', '', $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}
}
add_filter( 'admin_bar_menu', 'collectivewp_remove_howdy',25 );

/**
 * Remove BuddyPress admin
 */
function hide_bp_notice() {
	remove_action( 'bp_admin_init', 'bp_core_activation_notice', 1010 );
}
add_action('init', 'hide_bp_notice');

function custom_login_url() {
	$login_page = get_page_by_path('login');
	if ( ! isset( $login_page ) ) {
		return;
	}
	return home_url( '/login/' );
}
add_filter( 'login_url', 'custom_login_url', PHP_INT_MAX );

function custom_login_redirect( $redirect_to, $request, $user ) {
	if ( is_wp_error( $user ) )
		return $redirect_to;

	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		if ( in_array( 'administrator', $user->roles ) ) {
			// Redirect administrators to wp-admin dashboard
			return admin_url();
		} else {
			// Redirect users to bbPress forum
			return bbp_get_user_profile_url( $user->ID );
		}
	}

	return $redirect_to;
}
add_filter( 'login_redirect', 'custom_login_redirect', 10, 3 );

function custom_bbpress_forum_title( $title ) {
	// Modify the title as needed
	$new_title = 'Connects';

	return $new_title;
}
add_filter( 'bbp_get_forum_archive_title', 'custom_bbpress_forum_title' );

function custom_bbpress_archive_title( $title ) {
	// Modify the title as needed
	$new_title = 'Connects';

	return $new_title;
}
add_filter( 'bbp_get_dynamic_forum_archive_title', 'custom_bbpress_archive_title' );

function custom_bbpress_text( $translated_text, $text, $domain ) {
	if ( $domain === 'bbpress' ) {
		switch ( $text ) {
			case 'Forum':
				$translated_text = 'Connect';
				break;
			case 'Forums':
				$translated_text = 'Connects';
				break;
			case 'forum':
				$translated_text = 'connect';
				break;
			case 'forums':
				$translated_text = 'connects';
				break;
			case 'This forum is empty.':
				$translated_text = 'This connect is empty.';
				break;
			case 'Forum Attributes':
				$translated_text = 'Connect Attributes';
				break;
			case 'Forum Moderators':
				$translated_text = 'Connect Moderators';
				break;
			case 'All Forums':
				$translated_text = 'All Connects';
				break;
			case 'Oh, bother! No forums were found here.':
				$translated_text = 'Oh, bother! No connects were found here.';
		}
	}

	return $translated_text;
}
add_filter( 'gettext', 'custom_bbpress_text', 10, 3 );

/**
 * Hide adminbar on frontend for non-admins.
 */
function remove_admin_bar_for_non_admins() {
	if ( ! current_user_can( 'manage_options' ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'remove_admin_bar_for_non_admins' );

/**
 * Removes the Comments menu item from the admin sidebar.
 */
function disable_comments_admin_menu() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'disable_comments_admin_menu' );

/**
 * Removes the Comments menu from the admin toolbar.
 */
function disable_comments_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'disable_comments_admin_bar' );
