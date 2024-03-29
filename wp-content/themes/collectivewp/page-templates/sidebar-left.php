<?php
/**
 * Template Name: Sidebar Left
 * Template Post Type: post, page
 *
 * This template displays a page with a sidebar on the left side of the screen.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wd_s
 */

use function WebDevStudios\wd_s\print_comments;
use function WebDevStudios\wd_s\main_classes;

get_header(); ?>

	<div class="<?php echo esc_attr( main_classes( [ 'left-sidebar' ] ) ); ?>">
		<main id="main" class="content-container">

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->

		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>
