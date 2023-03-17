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

