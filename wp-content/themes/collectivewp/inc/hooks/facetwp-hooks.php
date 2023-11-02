<?php
/**
 * Update facetwp hooks.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Hide count on dropdown.
 */
add_filter( 'facetwp_facet_dropdown_show_counts', '__return_false' );
