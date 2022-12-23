<?php
/**
 * The template used for displaying Buttons in the scaffolding library.
 *
 * @package collectivewp
 */

use function collectivewp\print_scaffolding_section;

?>

<section class="section-scaffolding">

	<h2 class="scaffolding-heading" id="<?php esc_html_e( 'buttons', 'collectivewp' ); ?>"><?php esc_html_e( 'Buttons', 'collectivewp' ); ?></h2>
	<?php
		// Button.
		print_scaffolding_section(
			[
				'title'       => 'Button',
				'description' => 'Display a button.',
				'usage'       => '<button class="button" href="#">Click Me</button>',
				'output'      => '<button class="button">Click Me</button>',
			]
		);
		?>
</section>
