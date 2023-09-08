<?php
/**
 * Re-order admin sidebar menu.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

add_filter( 'custom_menu_order', '__return_true' );

/**
 * Re-order admin sidebar menu.
 */
function custom_menu_order() {
	return [
		'index.php', // Home.
		'wsal-auditlog', // WP Activity Log.
		'edit.php', // Posts.
		'edit.php?post_type=page', // Pages.
		'edit.php?post_type=resources', // Resources.
		'upload.php', // Media.
		'gf_edit_forms', // Form.
		'edit-comments.php', // Comments.
	];
}
add_filter( 'menu_order', __NAMESPACE__ . '\custom_menu_order', 1 );
