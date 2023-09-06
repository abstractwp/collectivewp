<?php
/**
 * Logos block patterns.
 *
 * @package wd_s
 * @phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
 */

use function WebDevStudios\wd_s\get_pattern_asset;

register_block_pattern(
	'wd_s/logo-2-columns-card-with-link',
	array(
		'title'      => __( 'Logo 2 Columns: Card with Link', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our partners', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns"><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#c6c6c6"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"twStretchedLink":true} --><div class="wp-block-column has-border-color tw-stretched-link" style="border-color:#c6c6c6;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"linkDestination":"custom","className":"tw-mb-4"} --><figure class="wp-block-image tw-mb-4"><a href="#" target="_blank" rel="noreferrer noopener"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></a></figure><!-- /wp:image --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#c6c6c6"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"twStretchedLink":true} --><div class="wp-block-column has-border-color tw-stretched-link" style="border-color:#c6c6c6;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"linkDestination":"custom","className":"tw-mb-4"} --><figure class="wp-block-image tw-mb-4"><a href="#" target="_blank" rel="noreferrer noopener"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></a></figure><!-- /wp:image --><!-- wp:paragraph --><p>Integer enim risus, suscipit eu iaculis sed, ullamcorper at metus. Venenatis nec convallis magna, eu congue velit.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:columns {"style":{"spacing":{"blockGap":"24px","margin":{"top":"24px"}}}} --><div class="wp-block-columns" style="margin-top:24px"><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#c6c6c6"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"twStretchedLink":true} --><div class="wp-block-column has-border-color tw-stretched-link" style="border-color:#c6c6c6;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"linkDestination":"custom","className":"tw-mb-4"} --><figure class="wp-block-image tw-mb-4"><a href="#" target="_blank" rel="noreferrer noopener"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></a></figure><!-- /wp:image --><!-- wp:paragraph --><p>Aliquam tempus mi nulla porta luctus. Sed non neque at lectus bibendum blandit. Morbi fringilla sapien libero.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#c6c6c6"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"twStretchedLink":true} --><div class="wp-block-column has-border-color tw-stretched-link" style="border-color:#c6c6c6;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"linkDestination":"custom","className":"tw-mb-4"} --><figure class="wp-block-image tw-mb-4"><a href="#" target="_blank" rel="noreferrer noopener"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></a></figure><!-- /wp:image --><!-- wp:paragraph --><p>Duis enim elit, porttitor id feugiat at, blandit at erat. Proin varius libero sit amet tortor volutpat diam laoreet.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-3-columns',
	array(
		'title'      => __( 'Logos 3 Columns', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:gallery {"columns":3,"imageCrop":false,"linkTo":"none","style":{"spacing":{"blockGap":"0px"}},"twFixedWidthCols":true} --><figure class="wp-block-gallery has-nested-images columns-3 tw-fixed-cols"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-3-columns-no-gutter',
	array(
		'title'      => __( 'Logos 3 Columns: No Gutter', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true},"style":{"spacing":{"blockGap":"60px"}}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:gallery {"columns":3,"imageCrop":false,"linkTo":"none","style":{"spacing":{"blockGap":"0px"}},"className":"tw-img-border","twFixedWidthCols":true} --><figure class="wp-block-gallery has-nested-images columns-3 tw-img-border tw-fixed-cols"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-heading-on-left',
	array(
		'title'      => __( 'Logos: Heading on Left', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":"48px"}},"twStack":"md"} --><div class="wp-block-columns alignwide tw-cols-stack-md"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading --><h2>' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore et dolore. Integer enim risus suscipit eu iaculis sed ullamcorper.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:gallery {"columns":3,"imageCrop":false,"linkTo":"none","className":"tw-img-border","twFixedWidthCols":true} --><figure class="wp-block-gallery has-nested-images columns-3 tw-img-border tw-fixed-cols"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-4-columns',
	array(
		'title'      => __( 'Logos 4 Columns', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true},"style":{"spacing":{"blockGap":"30px"}}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Trusted by hundreds of companies worldwide', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:gallery {"columns":4,"imageCrop":false,"linkTo":"none","align":"wide","style":{"spacing":{"blockGap":"0px"}},"twFixedWidthCols":true} --><figure class="wp-block-gallery alignwide has-nested-images columns-4 tw-fixed-cols"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-4-columns-inner-border',
	array(
		'title'      => __( 'Logos 4 Columns: Inner Border', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true},"style":{"spacing":{"blockGap":"60px"}}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:gallery {"columns":4,"imageCrop":false,"linkTo":"none","className":"tw-img-border-inner"} --><figure class="wp-block-gallery has-nested-images columns-4 tw-img-border-inner"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-4-columns-border',
	array(
		'title'      => __( 'Logos 4 Columns: Border', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:gallery {"columns":4,"imageCrop":false,"linkTo":"none","align":"wide","className":"tw-img-border","twFixedWidthCols":true} --><figure class="wp-block-gallery alignwide has-nested-images columns-4 tw-img-border tw-fixed-cols"><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image {"sizeSlug":"large","linkDestination":"none"} --><figure class="wp-block-image size-large"><img src="' . get_pattern_asset( 'logo2.svg' ) . '" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-4-columns-card',
	array(
		'title'      => __( 'Logos 4 Columns: Card', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our partners', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:columns {"align":"wide","twStack":"md-2","twTextAlign":"center","style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns alignwide tw-cols-stack-md-2 has-text-align-center"><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Lorem ipsum dolor sit amet, commodo erat adipiscing.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Integer enim risus suscipit eu iaculis sed ullamcorper.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Aliquam tempus mi nulla porta luctus sed non neque.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Duis enim elit porttitor id feugiat at, blandit at erat.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:columns {"align":"wide","twStack":"md-2","twTextAlign":"center","style":{"spacing":{"blockGap":"24px","margin":{"top":"24px"}}}} --><div class="wp-block-columns alignwide tw-cols-stack-md-2 has-text-align-center" style="margin-top:24px"><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Fusce sed magna ligula commodo hendrerit fringilla.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Mauris dui tellus mollis quis varius, sit amet ultrices leo.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Class aptent taciti sociosqu ad litora torquent per.</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"className":"is-style-tw-col-shadow"} --><div class="wp-block-column is-style-tw-col-shadow" style="padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:image {"align":"center","className":"tw-mb-4"} --><div class="wp-block-image tw-mb-4"><figure class="aligncenter"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure></div><!-- /wp:image --><!-- wp:paragraph --><p>Sed gravida enim eu convallis porttitor mauris.</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group -->',
	)
);

register_block_pattern(
	'wd_s/logos-5-columns',
	array(
		'title'      => __( 'Logos in Row', 'wd_s' ),
		'categories' => array( 'logos' ),
		'content'    => '<!-- wp:group {"align":"full","style":{"spacing":{"blockGap":"50px"}},"layout":{"inherit":true}} --><div class="wp-block-group alignfull"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">' . esc_html_x( 'Our clients', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"40px"}},"layout":{"type":"flex","allowOrientation":false,"justifyContent":"center"}} --><div class="wp-block-group alignwide"><!-- wp:image --><figure class="wp-block-image"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure><!-- /wp:image --><!-- wp:image --><figure class="wp-block-image"><img src="' . get_pattern_asset( 'logo-crop.svg' ) . '" alt=""/></figure><!-- /wp:image --></div><!-- /wp:group --></div><!-- /wp:group -->',
	)
);
