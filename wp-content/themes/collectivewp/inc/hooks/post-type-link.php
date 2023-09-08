<?php
/**
 * Update post query hooks.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Override resource link to PDF file.
 *
 * @param string $link default permelink.
 * @param object $post the post data.
 */
function resource_pdf_permalink( $link, $post ) {

	if ( class_exists( 'ACF' ) ) {
		$pdf_url = get_field( 'pdf-url', $post->ID );
		if ( $pdf_url ) {
			$url = esc_url( filter_var( $pdf_url, FILTER_VALIDATE_URL ) );
			return $url ? $url : '';
		}
	}

	return $link;
}

add_filter( 'post_type_link', __NAMESPACE__ . '\resource_pdf_permalink', 10, 2 );
