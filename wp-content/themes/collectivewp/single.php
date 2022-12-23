<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package collectivewp
 */

use function collectivewp\print_comments;
use function collectivewp\main_classes;

get_header(); ?>

	<main id="main" class="<?php echo esc_attr( main_classes( [] ) ); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', get_post_type() );

			the_post_navigation();

			print_comments();

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php get_footer(); ?>
