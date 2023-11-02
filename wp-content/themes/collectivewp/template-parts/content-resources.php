<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package wd_s
 */

use function WebDevStudios\wd_s\print_resources_type;
use function WebDevStudios\wd_s\print_entry_footer;
use function WebDevStudios\wd_s\get_trimmed_excerpt;
use function WebDevStudios\wd_s\get_dualtone_colors;
use function WebDevStudios\wd_s\get_theme_colors;

?>

<article <?php post_class( 'resource-container' ); ?>>
	<header class="entry-header">
		<?php
		if ( has_post_thumbnail() ) :
			$wd_s_dualtone = get_dualtone_colors();
			the_post_thumbnail( 'resource-thumb', [ 'class' => 'resource-img dualtone-' . $wd_s_dualtone[ array_rand( $wd_s_dualtone ) ] ] );
		else :
			$wd_s_colors = get_theme_colors();
			echo '<div class="resource-img placeholder-img pbg-' . $wd_s_colors[ array_rand( $wd_s_colors )] . '"></div>'; // phpcs:ignore.
		endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		print_resources_type( get_the_ID() );
		echo esc_html(
			get_trimmed_excerpt(
				array(
					'post'   => get_the_ID(),
					'length' => 25,
				)
			)
		);
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
