<?php
/**
 *  Return dualtone array.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Return dualtone array.
 *
 * @author Thong Dang
 */
function get_dualtone_colors() {
	// Render doutone filter.
	if ( function_exists( 'wp_get_global_styles_svg_filters' ) ) {
		$filters = wp_get_global_styles_svg_filters();
		if ( ! empty( $filters ) ) {
			echo $filters; // phpcs:ignore
		}
	}

	return [ 'secondary-primary', 'secondary-tertiary', 'secondary-yellow', 'secondary-gray', 'primary-base', 'primary-tertiary', 'primary-yellow', 'primary-gray', 'yellow-gray', 'yellow-tertiary' ];
}
