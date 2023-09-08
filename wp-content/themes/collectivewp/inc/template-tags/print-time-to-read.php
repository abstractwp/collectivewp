<?php
/**
 * Custom template tags for this theme.
 *
 * @package    wd_s
 * @subpackage throughts
 * @author     Thong Dang
 */

namespace WebDevStudios\wd_s;

/**
 * Prints HTML with author information for the current post.
 *
 * @author WebDevStudios
 *
 * @param string $content The post content.
 */
function print_time_to_read( $content ) {

	$count_words = str_word_count( wp_strip_all_tags( $content ) );
	$read_time   = ceil( $count_words / 200 );
	$prefix      = ' minute to read';
	if ( $read_time > 1 ) {
		$prefix = ' minutes to read';
	}
	?>
	<span class="post-time-to-read">
		<?php echo esc_html( $read_time . $prefix ); ?>
	</span>

	<?php
}
