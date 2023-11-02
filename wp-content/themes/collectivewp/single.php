<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wd_s
 */

use function WebDevStudios\wd_s\print_comments;
use function WebDevStudios\wd_s\main_classes;

get_header(); ?>

	<main id="main" class="<?php echo esc_attr( main_classes( [ 'has-padding-top' ] ) ); ?>">

		<?php
		while ( have_posts() ) :
			the_post();

			if ( is_singular( 'resources' ) ) :
				get_template_part( 'template-parts/content', get_post_type() . '-single' );
			else :
				get_template_part( 'template-parts/content', get_post_type() );
			endif;

			if ( is_singular( 'post' ) ) :
				// Previous/next post navigation.
				$wd_s_next = is_rtl() ? '<span class="dashicons dashicons-arrow-left-alt"></span>' : '<span class="dashicons dashicons-arrow-right-alt"></span>';
				$wd_s_prev = is_rtl() ? '<span class="dashicons dashicons-arrow-right-alt"></span>' : '<span class="dashicons dashicons-arrow-left-alt"></span>';

				$wd_s_next_label     = esc_html__( 'Next post', 'wd_s' );
				$wd_s_previous_label = esc_html__( 'Previous post', 'wd_s' );

				the_post_navigation(
					array(
						'next_text' => '<p class="meta-nav">' . $wd_s_next_label . $wd_s_next . '</p><p class="post-title">%title</p>',
						'prev_text' => '<p class="meta-nav">' . $wd_s_prev . $wd_s_previous_label . '</p><p class="post-title">%title</p>',
					)
				);
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->

<?php get_footer(); ?>
