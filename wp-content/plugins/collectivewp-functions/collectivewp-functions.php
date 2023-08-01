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
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'post_status_widget' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'notepad_widget' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'myposts_widget' ]);
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

function restrict_media_library_access( $query ) {
	// Check if the user role is Author
	if ( current_user_can( 'author' ) ) {
			// Set the author parameter to the current user's ID
			$query['author'] = get_current_user_id();
	}
	return $query;
}
add_filter( 'ajax_query_attachments_args', 'restrict_media_library_access' );

function remove_medialibrary_tab($strings) {
	if ( current_user_can('author') ) {
		unset($strings["uploadFilesTitle"]);
		return $strings;
	} else {
		return $strings;
	}
}
add_filter('media_view_strings','remove_medialibrary_tab');

function remove_media_upload_button() {
	if (current_user_can('author')) {
		echo '<style>
			.uploader-inline, .components-form-file-upload { display: none !important; }
		</style>';
	}
}
add_action('admin_head', 'remove_media_upload_button');

/**
 * Filter {@link get_avatar_url()} to use the BuddyPress user avatar URL.
 *
 * @since 2.9.0
 *
 * @param  string $retval      The URL of the avatar.
 * @param  mixed  $id_or_email The Gravatar to retrieve. Accepts a user_id, gravatar md5 hash,
 *                             user email, WP_User object, WP_Post object, or WP_Comment object.
 * @param  array  $args        Arguments passed to get_avatar_data(), after processing.
 * @return string
 */
function bp_core_get_avatar_url_filter( $retval, $id_or_email, $args ) {
	$user = null;

	// Ugh, hate duplicating code; process the user identifier.
	if ( is_numeric( $id_or_email ) ) {
		$user = get_user_by( 'id', absint( $id_or_email ) );
	} elseif ( $id_or_email instanceof WP_User ) {
		// User Object
		$user = $id_or_email;
	} elseif ( $id_or_email instanceof WP_Post ) {
		// Post Object
		$user = get_user_by( 'id', (int) $id_or_email->post_author );
	} elseif ( $id_or_email instanceof WP_Comment ) {
		if ( ! empty( $id_or_email->user_id ) ) {
			$user = get_user_by( 'id', (int) $id_or_email->user_id );
		}
	} elseif ( is_email( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
	}

	// No user, so bail.
	if ( false === $user instanceof WP_User ) {
		return $retval;
	}

	// Set BuddyPress-specific avatar args.
	$user_id = $user->ID;

	$upload_dir = wp_upload_dir();
	$dir        = $upload_dir['basedir'] . '/avatars/' . $user_id;

	if (is_dir($dir)) {
		$files      = scandir( $dir );
		$results    = array();

		foreach ( $files as $file ) {
			if ( '.' !== $file && '..' !== $file ) {
				if ( is_file( $dir . '/' . $file ) ) {
					if ( stristr( $file, 'bpfull' ) ) {
						$results[] = $file;
					}
				}
			}
		}
		if ( count( $results ) ) {
			return site_url( '/wp-content/uploads/avatars/' . $user_id . '/' . $results[0] );
		}
	}

	return $retval;
}
add_filter( 'get_avatar_url', 'bp_core_get_avatar_url_filter', 10, 3 );

/**
 * Use client logo on login page.
 */
function collectivewp_custom_login_logo() {
	$logo_attachment_id = get_theme_mod( 'custom_logo' );

	if ( $logo_attachment_id ) {
		$logo_url_array = wp_get_attachment_image_src( $logo_attachment_id, 'full' );
		$logo_url = $logo_url_array[0];

		echo '<style type="text/css">
			.login h1 a {
				background-image: url(' . $logo_url . ') !important;
				background-size: contain !important;
				width: 75% !important;
			}
		</style>';
	}
}

add_action( 'login_head', 'collectivewp_custom_login_logo' );
