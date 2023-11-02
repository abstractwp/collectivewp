<?php
/**
 * Template part for displaying vendors.
 *
 * @package    wd_s
 * @subpackage theme
 * @author     Thong Dang
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

use function WebDevStudios\wd_s\get_trimmed_excerpt;
?>

<article <?php post_class( 'post-container vendor-item' ); ?>>
	<div class="featured-image">
		<?php the_post_thumbnail( 'medium' ); ?>
	</div>
	<div class="vendor-body">
		<?php
		if ( ! is_single() ) :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		else :
			the_title( '<h1 class="entry-title">', '</h1>' );
		endif;
		?>
		<?php
		$wd_s_vendor_terms = get_the_terms( get_the_ID(), 'vendor-category' );

		if ( $wd_s_vendor_terms && ! is_wp_error( $wd_s_vendor_terms ) ) {
			echo '<p class="vendor-term">';
			foreach ( $wd_s_vendor_terms as $wd_s_vendor_term ) {
				echo '<span>' . esc_html( $wd_s_vendor_term->name ) . '</span>';
			}
			echo '</p>';
		}

		if ( is_single() ) :
			?>
		<div class="meta-field">
			<span class="address block"><strong>Address:</strong> <?php the_field( 'address' ); ?></span>
			<strong>Phone:</strong> <a href="tel:<?php the_field( 'phone' ); ?>"><?php the_field( 'phone' ); ?></a>
			<span class="minimum-project block"><strong>Minimum project: </strong><?php the_field( 'minimum_project' ); ?></span>
		</div>
			<?php
	endif;

		if ( ! is_single() ) :
			echo esc_html(
				get_trimmed_excerpt(
					[
						'length' => 30,
						'post'   => get_the_ID(),
					]
				)
			);
		else :
			the_content();
		endif;
		?>
	</div>
	<?php
	if ( ! is_single() ) :
		?>
		<div class="meta-field">
			<span class="address block"><strong>Address:</strong> <?php the_field( 'address' ); ?></span>
			<strong>Phone:</strong> <a href="tel:<?php the_field( 'phone' ); ?>"><?php the_field( 'phone' ); ?></a>
			<span class="minimum-project block"><strong>Minimum project: </strong><?php the_field( 'minimum_project' ); ?></span>
		</div>
		<?php
	endif;
	?>
</article><!-- #post-## -->
