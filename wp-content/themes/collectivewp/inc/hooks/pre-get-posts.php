<?php
/**
 * Update post query hooks.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Snippet Name: modify resources query on archive page
 * Snippet URL: https://www.wpcustoms.net/snippets/modify-custom-post-type-query-on-archive-page/
 *
 * @param object $query query object.
 */
function resources_archive_query( $query ) {
	$is_resource_tax = false;
	if ( taxonomy_exists( 'resources-type' ) ) {
		$is_resource_tax = true;
	}
	if ( taxonomy_exists( 'resources-industry' ) ) {
		$is_resource_tax = true;
	}
	if ( taxonomy_exists( 'resources-topic' ) ) {
		$is_resource_tax = true;
	}

	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_post_type_archive( 'resources' ) || $is_resource_tax ) {
		$query->set( 'posts_per_page', 12 );
	}

	if ( is_category( 'thoughts' ) ) {
		$query->set( 'posts_per_page', 11 );
	}

	if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$query->set(
			'post_type',
			[ 'post', 'resources' ]
		);
	}
}

add_action( 'pre_get_posts', __NAMESPACE__ . '\resources_archive_query' );
