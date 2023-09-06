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

use function WebDevStudios\wd_s\print_post_date;
use function WebDevStudios\wd_s\print_post_author;
use function WebDevStudios\wd_s\print_entry_footer;
use function WebDevStudios\wd_s\get_trimmed_excerpt;
use function WebDevStudios\wd_s\get_dualtone_colors;
use function WebDevStudios\wd_s\get_theme_colors;
use function WebDevStudios\wd_s\print_time_to_read;

?>

<article <?php post_class( 'post-container' ); ?>>

	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			if ( 'post' === get_post_type() ) :
				$wd_s_author_args = [];
				if ( has_category( 'thoughts' ) ) {
					$wd_s_author_args['author_text'] = esc_html__( 'Written by', 'wd_s' );
				}
				?>
				<div class="entry-meta">
					<?php
					if ( has_category( 'thoughts' ) ) {
						print_post_author( $wd_s_author_args );
						print_post_date();
					} else {
						print_post_date();
						print_post_author( $wd_s_author_args );
					}

					$wd_s_avatar = get_avatar( get_the_author_meta( 'ID' ), 72 );


					if ( has_category( 'thoughts' ) && $wd_s_avatar ) {
						echo '<div class="avatar-container"><div class="avatar-bubble-outer"><div class="avatar-bubble">' . $wd_s_avatar . '</div></div></div>'; // phpcs:ignore.

						print_time_to_read( get_the_content() );
					}
					?>
				</div><!-- .entry-meta -->
				<?php
				the_post_thumbnail( 'full' );
			endif;
		else :
			if ( 'post' === get_post_type() ) :
				if ( has_post_thumbnail() ) :
					$wd_s_dualtone = get_dualtone_colors();
					the_post_thumbnail( 'blog-thumb', [ 'class' => 'dualtone-' . $wd_s_dualtone[ array_rand( $wd_s_dualtone ) ] ] );
				else :
					$wd_s_colors = get_theme_colors();
					echo '<div class="placeholder-img pbg-' . $wd_s_colors[ array_rand( $wd_s_colors )] . '"></div>'; // phpcs:ignore.
				endif;
			endif;
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if ( 'post' === get_post_type() && ! is_single() ) :
			echo esc_html(
				get_trimmed_excerpt(
					[
						'length' => 30,
						'post'   => get_the_ID(),
					]
				)
			);
		else :
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. */
						esc_html__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'wd_s' ),
						[
							'span' => [
								'class' => [],
							],
						]
					),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
			);
		endif;

		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_attr__( 'Pages:', 'wd_s' ),
				'after'  => '</div>',
			]
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		if ( has_category( 'thoughts' ) ) :
			$wd_s_author_desc = get_the_author_meta( 'description' );

			if ( '' !== $wd_s_author_desc ) :
				echo '<div class="author-box"><strong>' . esc_html( get_the_author() ) . '</strong>';
				echo '<p>' . esc_html( get_the_author_meta( 'description' ) ) . '</p></div>';
			endif;
		else :
			print_entry_footer();
		endif;
		if ( 'post' === get_post_type() && ! is_single() ) :
			?>
			<div class="entry-meta">
				<?php print_post_date(); ?>
				<?php print_post_author(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
