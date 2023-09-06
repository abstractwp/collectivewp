<?php
/**
 * Template part for displaying posts.
 *
 * @package    wd_s
 * @subpackage throughts
 * @author     Thong Dang
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

use function WebDevStudios\wd_s\print_post_author;
use function WebDevStudios\wd_s\get_trimmed_excerpt;
use function WebDevStudios\wd_s\get_theme_colors;

$wd_s_colors = get_theme_colors();

$wd_s_color           = $wd_s_colors[ array_rand( $wd_s_colors ) ];
$wd_s_throught_bg_cls = '';
$wd_s_bg_colors       = $wd_s_colors;

unset( $wd_s_bg_colors[ array_search( $wd_s_color, $wd_s_colors ) ] ); // phpcs:ignore.
unset( $wd_s_bg_colors[ array_search( 'contrast', $wd_s_colors ) ] ); // phpcs:ignore.

$wd_s_throught_bg_cls = 'pbg-' . $wd_s_bg_colors[ array_rand( $wd_s_bg_colors ) ];
?>

<article <?php post_class( [ 'post-container', 'throught', $wd_s_throught_bg_cls ] ); ?>>

	<header class="entry-header">
		<?php
		if ( has_post_thumbnail() ) :
			the_post_thumbnail( 'full' );
		else :
			echo '<div class="placeholder-img pbg-' . $wd_s_color . '"></div>'; // phpcs:ignore.
		endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
	<?php
		echo '<span class="date">' . esc_html( get_the_time( 'F j' ) ) . '</span>';

		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

		echo '<div class="meta-desc">';

		$wd_s_read_more = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="readmore">Read more &rarr;</a>';
		echo '<div class="excerpt">' . esc_html(
			get_trimmed_excerpt(
				[
					'length' => 30,
					'post'   => get_the_ID(),
				]
			)
		) . $wd_s_read_more . '</div>'; // phpcs:ignore.
		print_post_author( array( 'author_text' => 'Words by' ) );
		echo '</div>';
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
