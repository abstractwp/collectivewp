<?php
/**
 * Testimonials coursel Blocks.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

$wd_s_testimonials = get_field( 'testimonials' );
$wd_s_block_title  = get_field( 'title' );
echo '<div class="testimonial-block">';

if ( $wd_s_testimonials ) :
	echo '<div class="left-container"><div class="left-content">';
	if ( $wd_s_testimonials ) :
		echo '<h3>' . esc_html( $wd_s_block_title ) . '</h3>';
		endif;
		echo '<div><button type="button" class="slick-prev"><svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.4286 23.8454L12.5714 16.9882L19.4286 10.1311" stroke="#14353C" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';
		echo '<button type="button" class="slick-next"><svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.5714 23.8454L19.4286 16.9882L12.5714 10.1311" stroke="#14353C" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></button></div>';

	echo '</div></div>';
	echo '<div class="testimonial-slider-container">';
		echo '<div class="testimonial-slider">';
	foreach ( $wd_s_testimonials as $wd_s_testimonial ) :
		?>
			<div class="testimonial-item">
				<div class="testimonial-container">
					<div class="bg-cover"></div>
					<div class="t-content">
						<div class="t-description">
							<p><?php echo esc_html( $wd_s_testimonial['content'] ); ?></p>
						</div>
						<div class="t-meta">
							<p class="title"><?php echo esc_html( $wd_s_testimonial['author'] ); ?></p>
							<p class="position"><?php echo esc_html( $wd_s_testimonial['job_title'] ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		endforeach;
		echo '</div>';
	echo '</div>';
endif;
echo '</div>';
