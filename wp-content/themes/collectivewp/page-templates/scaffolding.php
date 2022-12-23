<?php
/**
 * Template Name: Scaffolding
 *
 * Template Post Type: page, scaffolding, collectivewp_scaffolding
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package collectivewp
 */

use function collectivewp\main_classes;

get_header(); ?>

	<main id="main" class="<?php echo esc_attr( main_classes( [ 'relative' ] ) ); ?>">

		<?php do_action( 'collectivewp_scaffolding_content' ); ?>

	</main><!-- #main -->

<?php get_footer(); ?>
