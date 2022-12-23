<?php
/**
 * Add a scaffolding nav for easier access.
 *
 * @package collectivewp
 */

namespace collectivewp;

/**
 * Add a scaffolding nav for easier access.
 *
 * @author JC Palmes
 */
function print_theme_scaffolding_nav() {
	?>
	<nav class="scaffolding-nav">
		<span><?php echo esc_html__( 'Scroll to:', 'collectivewp' ); ?></span>
		<a href="#globals" class="link"><?php echo esc_html__( 'Globals', 'collectivewp' ); ?></a>
		<a href="#typography" class="link"><?php echo esc_html__( 'Typography', 'collectivewp' ); ?></a>
		<a href="#media" class="link"><?php echo esc_html__( 'Media', 'collectivewp' ); ?></a>
		<a href="#icons" class="link"><?php echo esc_html__( 'Icons', 'collectivewp' ); ?></a>
		<a href="#buttons" class="link"><?php echo esc_html__( 'Buttons', 'collectivewp' ); ?></a>
		<a href="#forms" class="link"><?php echo esc_html__( 'Forms', 'collectivewp' ); ?></a>
		<a href="#elements" class="link"><?php echo esc_html__( 'Elements', 'collectivewp' ); ?></a>
	</nav><!-- .scaffolding-nav -->
	<?php
}
