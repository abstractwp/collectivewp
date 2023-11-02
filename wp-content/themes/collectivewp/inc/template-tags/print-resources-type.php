<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

/**
 * Prints HTML with type information for the current resource.
 *
 * @author Thong Dang
 *
 * @param array $post_id the post id to show resource type.
 */
function print_resources_type( $post_id ) {
	$types = get_the_terms( $post_id, 'resources-type' );
	if ( ! empty( $types ) ) :
		?>
	<span class="types">
		<?php
		foreach ( $types as $type ) {
			echo '<a href="' . esc_url( get_term_link( $type->term_id ) ) . '" class="resource-type">' . esc_html( $type->name ) . '</a>'; // phpcs: ignore.
		}
		?>
	</span>

		<?php
	endif;
}
