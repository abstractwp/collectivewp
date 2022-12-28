<?php
/**
 * Whitelist specific Gutenberg blocks.
 *
 * @package collectivewp
 */

namespace collectivewp;

/*
 * Whitelist specific Gutenberg blocks (paragraph, heading, image and lists)
 *
 * @author thong dang
 * @link https://developer.wordpress.org/reference/hooks/allowed_block_types_all/
 */
function wpdocs_allowed_block_types ( $block_editor_context, $editor_context ) {
	if ( ! empty( $editor_context->post ) ) {
		return array(
			'core/group',
			'core/column',
			'core/paragraph',
			'core/heading',
			'core/list',
		);
	}

	return $block_editor_context;
}

add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\wpdocs_allowed_block_types', 10, 2 );
