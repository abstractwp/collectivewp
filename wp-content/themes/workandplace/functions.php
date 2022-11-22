<?php
/**
 * Get parent theme settings.
 */
function abstractwp_copy_parent_theme_mods () {
	$parent = wp_get_theme();
	$options = get_option( 'theme_mods_' . $parent->parent()->template );
	update_option( 'theme_mods_' . $parent->stylesheet, $options );
}

add_action('after_switch_theme', 'abstractwp_copy_parent_theme_mods');

/**
 * Enqueue scripts and styles.
 */
function abstractwp_styles() {
	wp_enqueue_style( 'abstractwp2021-child-style', get_stylesheet_uri(), array( 'twenty-twenty-one-style' ), wp_get_theme()->get('Version') );
}

add_action( 'wp_enqueue_scripts', 'abstractwp_styles');

/**
 * Add style fixed for know issues.
 * We only apply fixed for 2021 theme.
 */
function abstractwp_editor_style_bugs_fixed() {
	$screen = get_current_screen();
	if ( is_plugin_active( 'twentig/twentig.php' ) && $screen->is_block_editor  ) { // phpcs:ignore.
		echo '<style type="text/css">
			:root .editor-styles-wrapper {' . twentig_twentyone_generate_color_variables() . '}
			.editor-styles-wrapper .wp-block-quote.is-large:not(.is-style-plain) p,
			.editor-styles-wrapper .wp-block-quote.is-style-large:not(.is-style-plain) p {
				font-size: 1.5em;
				font-style: italic;
				line-height: 1.6;
			}
		</style>'; // phpcs:ignore.
	}
}

add_action( 'admin_head', 'abstractwp_editor_style_bugs_fixed' );
