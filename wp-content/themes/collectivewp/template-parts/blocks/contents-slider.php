<?php
/**
 * Text coursel Blocks.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wd_s
 */

namespace WebDevStudios\wd_s;

$wd_s_texts = get_field( 'contents' );
echo '<div class="content-block alignwide">';

if ( $wd_s_texts ) :
	echo '<button type="button" class="content-slick-prev"><svg width="19" height="29" viewBox="0 0 19 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 26.182L3 14.3947L16 2.60742" stroke="#14353C" stroke-width="4.33333" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';

	echo '<div class="contents-slider">';
	foreach ( $wd_s_texts as $wd_s_text ) :
		?>
		<div class="text-item">
		<?php echo $wd_s_text['content']; //phpcs:ignore ?>
		</div>
		<?php
		endforeach;
	echo '</div>';

	echo '<button type="button" class="content-slick-next"><svg width="19" height="29" viewBox="0 0 19 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 26.182L16 14.3947L3 2.60742" stroke="#14353C" stroke-width="4.33333" stroke-linecap="round" stroke-linejoin="round"/></svg></button>';
endif;
echo '</div>';
