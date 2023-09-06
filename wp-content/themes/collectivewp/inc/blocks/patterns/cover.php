<?php
/**
 * Cover block patterns.
 *
 * @package wd_s
 * @phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
 */

use function WebDevStudios\wd_s\get_pattern_asset;

register_block_pattern(
	'wd_s/wide-cover',
	array(
		'title'      => __( 'Wide Cover', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":500,"align":"wide"} -->
		<div class="wp-block-cover alignwide" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed, ullamcorper at metus. Class aptent taciti sociosqu ad litora torquent per conubia.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/full-width-cover',
	array(
		'title'      => __( 'Full Width Cover', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":500,"align":"full"} -->
		<div class="wp-block-cover alignfull" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/fullscreen-cover',
	array(
		'title'      => __( 'Fullscreen Cover', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} -->
		<div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed, ullamcorper at metus. Class aptent taciti sociosqu ad litora torquent per conubia.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/fullscreen-cover-with-card',
	array(
		'title'      => __( 'Fullscreen Cover with Card', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":0,"minHeight":100,"minHeightUnit":"vh","isDark":false,"align":"full"} --><div class="wp-block-cover alignfull is-light" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-0 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:group {"backgroundColor":"base","textColor":"contrast"} --><div class="wp-block-group has-contrast-color has-base-background-color has-text-color has-background"><!-- wp:heading --><h2>' . esc_html_x( 'Write a heading', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed, ullamcorper at metus. Class aptent taciti sociosqu ad litora torquent per conubia. Venenatis nec convallis magna, eu congue velit. Aliquam tempus mi nulla porta luctus.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/fullscreen-cover-with-heading-above-card',
	array(
		'title'      => __( 'Fullscreen Cover with Heading Above Card', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} -->
		<div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"60px"}}}} --><h2 class="has-text-align-center" style="margin-bottom:60px">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:group {"backgroundColor":"base","textColor":"contrast"} --><div class="wp-block-group has-contrast-color has-base-background-color has-text-color has-background"><!-- wp:paragraph --><p><strong>' . esc_html_x( 'Write a lead paragraph.', 'Block pattern content', 'wd_s' ) . ' Lorem ipsum dolor sit amet, commodo erat adipiscing elit.</strong></p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed, ullamcorper at metus class aptent taciti sociosqu ad. Mauris dui tellus mollis quis varius, sit amet ultrices in leo. Cras et purus sit amet velit congue convallis nec id diam. Sed gravida enim sed convallis porttitor.</p><!-- /wp:paragraph --></div><!-- /wp:group --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/cover-with-2-text-columns',
	array(
		'title'      => __( 'Cover with 2 Text Columns', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"align":"full"} --><div class="wp-block-cover alignfull"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns"><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#ffffff80"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-column has-border-color" style="border-color:#ffffff80;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="has-large-font-size">' . esc_html_x( 'First item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. </p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#ffffff80"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-column has-border-color" style="border-color:#ffffff80;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:heading {"level":3,"fontSize":"large"} --><h3 class="has-large-font-size">' . esc_html_x( 'Second item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Integer enim risus suscipit eu iaculis sed, ullamcorper at metus. Venenatis nec convallis magna eu congue.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/2-columns-with-cover',
	array(
		'title'      => __( '2 Columns with Cover', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"twStack":"md","twTextAlign":"center"} --><div class="wp-block-columns alignwide has-link-color tw-cols-stack-md has-text-align-center"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square1.jpg' ) . '","dimRatio":60,"minHeight":500,"twStretchedLink":true} --><div class="wp-block-cover tw-stretched-link" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square1.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'First item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square2.jpg' ) . '","dimRatio":60,"minHeight":500,"twStretchedLink":true} --><div class="wp-block-cover tw-stretched-link" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square2.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'Second item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Sed do eiusmod ut tempor.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/2-columns-with-cover-bottom-aligned-text',
	array(
		'title'      => __( '2 Columns with Cover: Bottom Aligned Text', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide","twStack":"md","style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns alignwide tw-cols-stack-md"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square1.jpg' ) . '","dimRatio":60,"minHeight":500,"contentPosition":"bottom left"} --><div class="wp-block-cover has-custom-content-position is-position-bottom-left" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square1.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'First item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore.</p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square2.jpg' ) . '","dimRatio":60,"minHeight":500,"contentPosition":"bottom left"} --><div class="wp-block-cover has-custom-content-position is-position-bottom-left" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square2.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} -->	<h3 class="has-x-large-font-size">' . esc_html_x( 'Second item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Integer enim risus, suscipit eu iaculis sed, ullamcorper at metus. Venenatis nec convallis magna eu congue velit.</p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/2-columns-with-cover-x-2-top-aligned-text',
	array(
		'title'      => __( '2 Columns with Cover x 2: Top Aligned Text', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Write a heading that captivates your audience', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square1.jpg' ) . '","dimRatio":60,"contentPosition":"top left","className":"is-style-tw-rounded-corners","style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-cover has-custom-content-position is-position-top-left is-style-tw-rounded-corners" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square1.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.4"}}} --><p style="font-style:normal;font-weight:700;line-height:1.4">Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor. </p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square2.jpg' ) . '","dimRatio":60,"contentPosition":"top left","className":"is-style-tw-rounded-corners","style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-cover has-custom-content-position is-position-top-left is-style-tw-rounded-corners" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square2.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.4"}}} --><p style="font-style:normal;font-weight:700;line-height:1.4">Integer enim risus suscipit eu iaculis sed ullamcorper at metus. Venenatis nec convallis magna eu congue velit.</p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:columns {"style":{"spacing":{"blockGap":"24px","margin":{"top":"24px"}}}} --><div class="wp-block-columns" style="margin-top:24px"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square3.jpg' ) . '","dimRatio":60,"contentPosition":"top left","className":"is-style-tw-rounded-corners","style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-cover has-custom-content-position is-position-top-left is-style-tw-rounded-corners" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square3.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.4"}}} --><p style="font-style:normal;font-weight:700;line-height:1.4">Duis enim elit porttitor id feugiat at blandit at erat. Proin varius libero sit amet tortor. </p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square4.jpg' ) . '","dimRatio":60,"contentPosition":"top left","className":"is-style-tw-rounded-corners","style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}}} --><div class="wp-block-cover has-custom-content-position is-position-top-left is-style-tw-rounded-corners" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square4.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.4"}}} --><p style="font-style:normal;font-weight:700;line-height:1.4">Fusce sed magna eu ligula commodo hendrerit fringilla ac purus integer sagittis. </p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/2-columns-with-cover-x-2-full-width',
	array(
		'title'      => __( '2 Columns with Cover x 2: Full Width', 'wd_s' ),
		'categories' => array( 'cover' ),
		'content'    => '<!-- wp:columns {"align":"full","style":{"spacing":{"blockGap":"0px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"twStack":"md","twTextAlign":"center"} --><div class="wp-block-columns alignfull has-link-color tw-cols-stack-md has-text-align-center"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square1.jpg' ) . '","dimRatio":60,"minHeight":500,"twHover":"opacity"} --><div class="wp-block-cover" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square1.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'First item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square2.jpg' ) . '","dimRatio":60,"minHeight":500,"twHover":"opacity"} --><div class="wp-block-cover" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square2.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'Second item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Integer enim risus.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:columns {"align":"full","style":{"spacing":{"blockGap":"0px","margin":{"top":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|background"}}}},"twStack":"md","twTextAlign":"center"} --><div class="wp-block-columns alignfull has-link-color tw-cols-stack-md has-text-align-center" style="margin-top:0px"><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square3.jpg' ) . '","dimRatio":60,"minHeight":500,"twHover":"opacity"} --><div class="wp-block-cover" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square3.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'Third item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Mauris dui tellus mollis.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:cover {"url":"' . get_pattern_asset( 'square4.jpg' ) . '","dimRatio":60,"minHeight":500,"twHover":"opacity"} --><div class="wp-block-cover" style="min-height:500px"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'square4.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"level":3,"fontSize":"x-large"} --><h3 class="has-x-large-font-size">' . esc_html_x( 'Fourth item', 'Block pattern content', 'wd_s' ) . '</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Nunc vehicula at rhoncus ultrices.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><a href="#">' . esc_html_x( 'Learn more', 'Block pattern content', 'wd_s' ) . '</a></p><!-- /wp:paragraph --></div></div><!-- /wp:cover --></div><!-- /wp:column --></div><!-- /wp:columns -->',
	)
);
