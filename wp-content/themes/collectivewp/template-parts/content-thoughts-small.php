<?php
/**
 * Template part for displaying posts.
 *
 * @package    wd_s
 * @subpackage theme
 * @author     Thong Dang
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

use function WebDevStudios\wd_s\print_post_author;
use function WebDevStudios\wd_s\get_trimmed_excerpt;
?>

<article <?php post_class( [ 'post-container', 'throught' ] ); ?>>
	<header class="entry-header">
		<?php
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		print_post_author( array( 'author_text' => 'Words by' ) );
		?>
	</header>
	<div class="entry-content">
	<?php
		echo '<div class="meta-desc">';

		$wd_s_read_more = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="readmore">Read more &rarr;</a>';
		echo '<div class="excerpt">' . esc_html(
			get_trimmed_excerpt(
				[
					'length' => 15,
					'post'   => get_the_ID(),
				]
			)
		) . '</div>' . $wd_s_read_more; // phpcs:ignore.
		echo '</div>';
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		echo '<span class="date">' . esc_html( get_the_time( 'F j' ) ) . '</span>';
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
