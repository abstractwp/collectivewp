<?php
/**
 * Single Page Layouts.
 *
 * @package wd_s
 * @phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
 */

use function WebDevStudios\wd_s\get_pattern_asset;

register_block_pattern(
	'wd_s/single-page-post-1',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' - Post 1',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:spacer {"height":"50px"} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="https://collectivewp.com/wp-content/uploads/2023/03/Screenshot-2023-03-28-at-2.47.03-PM.png" alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/03/Gable-dashboard-1024x652.png" alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="https://collectivewp.com/wp-content/uploads/2023/04/Screenshot-2023-04-18-at-1.10.13-PM.png" alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/03/L1000722-1024x576.jpg" alt=""/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->',
	)
);

register_block_pattern(
	'wd_s/single-page-post-2',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' - Post 2',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:spacer {"height":"50px"} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"backgroundColor":"tertiary","layout":{"inherit":true,"type":"constrained"}} -->
		<div class="wp-block-group has-tertiary-background-color has-background"><!-- wp:heading -->
		<h2 class="wp-block-heading">What to consider</h2>
		<!-- /wp:heading -->

		<!-- wp:columns {"align":"wide","className":"tw-cols-stack-md-2"} -->
		<div class="wp-block-columns alignwide tw-cols-stack-md-2"><!-- wp:column -->
		<div class="wp-block-column"><!-- wp:paragraph -->
		<p><strong>01.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing.</p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column"><!-- wp:paragraph -->
		<p><strong>02.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Integer enim risus, suscipit eu iaculis sed metus.</p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:column --></div>
		<!-- /wp:columns -->

		<!-- wp:columns {"align":"wide","className":"tw-cols-stack-md-2"} -->
		<div class="wp-block-columns alignwide tw-cols-stack-md-2"><!-- wp:column -->
		<div class="wp-block-column"><!-- wp:paragraph -->
		<p><strong>03.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing.</p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column"><!-- wp:paragraph -->
		<p><strong>04.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Integer enim risus, suscipit eu iaculis sed metus.</p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
		<!-- /wp:group -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"0","margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"gray-100","layout":{"type":"constrained"}} -->
		<div class="wp-block-group has-gray-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:media-text {"mediaPosition":"right","mediaId":644,"mediaLink":"https://collectivewp.com/access-the-workplace-solution-ecosystem/ecosystem/","mediaType":"image","className":"tw-stack-md tw-media-bottom"} -->
		<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile tw-stack-md tw-media-bottom"><div class="wp-block-media-text__content"><!-- wp:paragraph -->
		<p><strong>Our pick</strong><a href="https://www.nytimes.com/wirecutter/out/link/36200/158513/4/142200?merchant=Amazon" target="_blank" rel="noreferrer noopener"></a></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":3,"style":{"typography":{"lineHeight":"1.1"}}} -->
		<h3 class="wp-block-heading" style="line-height:1.1">Product title 1</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$1,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="https://collectivewp.com/wp-content/uploads/2023/04/Ecosystem-1024x1024.png" alt="" class="wp-image-644 size-full"/></figure></div>
		<!-- /wp:media-text --></div>
		<!-- /wp:group -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"gray-100","layout":{"inherit":true,"type":"constrained"}} -->
		<div class="wp-block-group has-gray-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:media-text {"mediaPosition":"right","mediaId":695,"mediaLink":"https://collectivewp.com/issue-12/futureofjobs/","mediaType":"image","className":"tw-stack-md tw-media-bottom"} -->
		<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile tw-stack-md tw-media-bottom"><div class="wp-block-media-text__content"><!-- wp:paragraph -->
		<p><strong>Runner-up</strong><a href="https://www.nytimes.com/wirecutter/out/link/49062/178063/4/142201?merchant=Dick%27s%20Sporting%20Goods" target="_blank" rel="noreferrer noopener"></a></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":3,"style":{"typography":{"lineHeight":"1.1"}}} -->
		<h3 class="wp-block-heading" style="line-height:1.1">Product title 2</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$2,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="https://collectivewp.com/wp-content/uploads/2023/05/FutureofJobs-1024x1024.png" alt="" class="wp-image-695 size-full"/></figure></div>
		<!-- /wp:media-text --></div>
		<!-- /wp:group -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"gray-100","layout":{"inherit":true,"type":"constrained"}} -->
		<div class="wp-block-group has-gray-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:media-text {"mediaPosition":"right","mediaId":579,"mediaLink":"https://collectivewp.com/issue-9/grid_0/","mediaType":"image","className":"tw-stack-md tw-media-bottom"} -->
		<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile tw-stack-md tw-media-bottom"><div class="wp-block-media-text__content"><!-- wp:paragraph -->
		<p><strong>Upgrade pick</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":3,"style":{"typography":{"lineHeight":"1.1"}}} -->
		<h3 class="wp-block-heading" style="line-height:1.1">Product title 3</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$3,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="https://collectivewp.com/wp-content/uploads/2023/04/grid_0-1024x1024.webp" alt="" class="wp-image-579 size-full"/></figure></div>
		<!-- /wp:media-text --></div>
		<!-- /wp:group -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"backgroundColor":"gray-100","layout":{"inherit":true,"type":"constrained"}} -->
		<div class="wp-block-group has-gray-100-background-color has-background" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:media-text {"mediaPosition":"right","mediaId":579,"mediaLink":"https://collectivewp.com/issue-9/grid_0/","mediaType":"image","className":"tw-stack-md tw-media-bottom"} -->
		<div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile tw-stack-md tw-media-bottom"><div class="wp-block-media-text__content"><!-- wp:paragraph -->
		<p><strong><strong>Also great</strong></strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":3,"style":{"typography":{"lineHeight":"1.1"}}} -->
		<h3 class="wp-block-heading" style="line-height:1.1">Product title 4</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"30px"}}}} -->
		<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$3,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div><figure class="wp-block-media-text__media"><img src="https://collectivewp.com/wp-content/uploads/2023/04/grid_0-1024x1024.webp" alt="" class="wp-image-579 size-full"/></figure></div>
		<!-- /wp:media-text --></div>
		<!-- /wp:group -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:spacer {"height":"50px"} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading">Everything we recommend</h2>
		<!-- /wp:heading -->

		<!-- wp:group {"align":"full","backgroundColor":"base","layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignfull has-base-background-color has-background"><!-- wp:columns {"align":"wide"} -->
		<div class="wp-block-columns alignwide"><!-- wp:column {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","right":"var:preset|spacing|16","bottom":"var:preset|spacing|16","left":"var:preset|spacing|16"}}},"backgroundColor":"tertiary"} -->
		<div class="wp-block-column has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--16);padding-right:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16);padding-left:var(--wp--preset--spacing--16)"><!-- wp:paragraph -->
		<p><strong>Our pick</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"align":"center","id":644,"sizeSlug":"medium","linkDestination":"none"} -->
		<figure class="wp-block-image aligncenter size-medium"><img src="https://collectivewp.com/wp-content/uploads/2023/04/Ecosystem-300x300.png" alt="" class="wp-image-644"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":3,"fontSize":"xl"} -->
		<h3 class="wp-block-heading has-xl-font-size">Product title 1</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$1,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column -->

		<!-- wp:column {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","right":"var:preset|spacing|16","bottom":"var:preset|spacing|16","left":"var:preset|spacing|16"}}},"backgroundColor":"tertiary"} -->
		<div class="wp-block-column has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--16);padding-right:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16);padding-left:var(--wp--preset--spacing--16)"><!-- wp:paragraph -->
		<p><strong>Runner-up</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"align":"center","id":695,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image aligncenter size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/05/FutureofJobs-1024x1024.png" alt="" class="wp-image-695"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":3,"fontSize":"xl"} -->
		<h3 class="wp-block-heading has-xl-font-size">Product title 2</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$3,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column -->

		<!-- wp:column {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","right":"var:preset|spacing|16","bottom":"var:preset|spacing|16","left":"var:preset|spacing|16"}}},"backgroundColor":"tertiary"} -->
		<div class="wp-block-column has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--16);padding-right:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16);padding-left:var(--wp--preset--spacing--16)"><!-- wp:paragraph -->
		<p><strong>Upgrade pick</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"align":"center","id":579,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image aligncenter size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/04/grid_0-1024x1024.webp" alt="" class="wp-image-579"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":3,"fontSize":"xl"} -->
		<h3 class="wp-block-heading has-xl-font-size">Product title 3</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$3,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column -->

		<!-- wp:column {"style":{"spacing":{"padding":{"top":"var:preset|spacing|16","right":"var:preset|spacing|16","bottom":"var:preset|spacing|16","left":"var:preset|spacing|16"}}},"backgroundColor":"tertiary"} -->
		<div class="wp-block-column has-tertiary-background-color has-background" style="padding-top:var(--wp--preset--spacing--16);padding-right:var(--wp--preset--spacing--16);padding-bottom:var(--wp--preset--spacing--16);padding-left:var(--wp--preset--spacing--16)"><!-- wp:paragraph -->
		<p><strong>Also great</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"align":"center","id":597,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image aligncenter size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/04/Firefly_anoffice-building-in-pixar-style_art_94351-1024x1024.jpg" alt="" class="wp-image-597"/></figure>
		<!-- /wp:image -->

		<!-- wp:heading {"level":3,"fontSize":"xl"} -->
		<h3 class="wp-block-heading has-xl-font-size">Product title 4</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem ipsum dolor sit amet, commodo erat adipiscing elit. Sed do eiusmod ut tempor incididunt ut labore.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">$1,299*&nbsp;from&nbsp;Amazon</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
		<!-- /wp:group -->

		<!-- wp:spacer {"height":"50px"} -->
		<div style="height:50px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading">The research</h2>
		<!-- /wp:heading -->

		<!-- wp:list -->
		<ul><!-- wp:list-item {"fontSize":"normal"} -->
		<li class="has-normal-font-size"><a href="#how-our-picks-compare">How our picks compare</a></li>
		<!-- /wp:list-item -->

		<!-- wp:list-item {"fontSize":"normal"} -->
		<li class="has-normal-font-size"><a href="#why-you-should-trust-us">Why you should trust us</a></li>
		<!-- /wp:list-item --></ul>
		<!-- /wp:list -->

		<!-- wp:heading {"level":3} -->
		<h3 class="wp-block-heading" id="how-our-picks-compare">How our picks compare</h3>
		<!-- /wp:heading -->

		<!-- wp:table {"className":"is-style-stripes"} -->
		<figure class="wp-block-table is-style-stripes"><table><thead><tr><th></th><th>Product title 1</th><th>Product title 2</th><th>Product title 3</th><th>Product title 4</th></tr></thead><tbody><tr><td>Function 1</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr><tr><td>Function 2</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr><tr><td>Function 3</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr><tr><td>Function 4</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr><tr><td>Function 5</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr><tr><td>Function 6</td><td>xx</td><td>xxx</td><td>xxxx</td><td>xxxxx</td></tr></tbody></table></figure>
		<!-- /wp:table -->

		<!-- wp:heading {"level":3} -->
		<h3 class="wp-block-heading" id="why-you-should-trust-us">Why you should trust us</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. Lorem Ipsum&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p></p>
		<!-- /wp:paragraph -->',
	)
);

register_block_pattern(
	'wd_s/single-page-1',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 1',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} --><div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"align":"wide","style":{"typography":{"lineHeight":"1.1"}}} --><h1 class="alignwide has-text-align-center" style="line-height:1.1">' . esc_html_x( 'Coming soon', 'Block pattern content', 'wd_s' ) . '</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.4","fontSize":"large"}}} --><p class="has-large-font-size has-text-align-center" style="line-height:1.4">Aliquam eget tellus ligula quisque convallis, turpis a efficitur dictum, augue nunc sodales.</p><!-- /wp:paragraph --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"40px"}}}} --><div class="wp-block-buttons" style="margin-top:40px"><!-- wp:button {"style":{"color":{"background":"white","text":"black"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Get in touch', 'Block pattern content', 'wd_s' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/single-page-2',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 2',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} -->
		<div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"30px"}},"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"x-large"} --><h1 class="has-text-align-center has-x-large-font-size">Julie Miller</h1><!-- /wp:heading --><!-- wp:buttons {"layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"20px"}}} --><div class="wp-block-buttons"><!-- wp:button {"textColor":"white","className":"has-custom-width wp-block-button__width-100 is-style-outline"} --><div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-white-color has-text-color">Listen to my podcast</a></div><!-- /wp:button --><!-- wp:button {"textColor":"white","width":100,"className":"is-style-outline"} --><div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-white-color has-text-color">Watch my videos on YouTube</a></div><!-- /wp:button --><!-- wp:button {"textColor":"white","width":100,"className":"is-style-outline"} --><div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-white-color has-text-color">View my blog</a></div><!-- /wp:button --><!-- wp:button {"textColor":"white","width":100,"className":"is-style-outline"} --><div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link has-white-color has-text-color">Check out my reading list</a></div><!-- /wp:button --></div><!-- /wp:buttons --><!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","size":"has-normal-icon-size","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"20px"}}} --><ul class="wp-block-social-links has-normal-icon-size has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"facebook"} /--><!-- wp:social-link {"url":"#","service":"twitter"} /--><!-- wp:social-link {"url":"#","service":"instagram"} /--><!-- wp:social-link {"url":"#","service":"spotify"} /--><!-- wp:social-link {"url":"#","service":"youtube"} /--></ul><!-- /wp:social-links --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/single-page-3',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 3',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:media-text {"align":"full","mediaType":"image","imageFill":true,"backgroundColor":"contrast","textColor":"base","className":"tw-height-full","twStackedMd":true,"twMediaBottom":true} --><div class="wp-block-media-text alignfull is-stacked-on-mobile is-image-fill tw-height-full has-base-color has-contrast-background-color has-text-color has-background tw-stack-md tw-media-bottom"><figure class="wp-block-media-text__media" style="background-image:url(' . get_pattern_asset( 'profile-square.jpg' ) . ');background-position:50% 50%"><img src="' . get_pattern_asset( 'profile-square.jpg' ) . '" alt=""/></figure><div class="wp-block-media-text__content"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} --><h1 class="has-small-font-size" style="text-transform:uppercase">David Lin</h1><!-- /wp:heading --><!-- wp:heading {"style":{"spacing":{"margin":{"top":"20px"}}}} --><h2 style="margin-top:20px">' . esc_html_x( 'Freelance developer', 'Block pattern content - Referring to a man', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Duis finibus venenatis diam id semper. Etiam non nisi sit amet nisl lobortis hendrerit eget scelerisque lacus. Pellentesque vitae fermentum eros. Praesent congue interdum neque ac luctus. Nullam vulputate euismod massa et tincidunt, donec ultrices libero sed elit eleifend. Pellentesque posuere sagittis turpis, ut feugiat mi finibus nec odio dignissim. </p><!-- /wp:paragraph --><!-- wp:buttons {"style":{"spacing":{"margin":{"top":"35px"},"blockGap":"20px"}}} --><div class="wp-block-buttons" style="margin-top:35px"><!-- wp:button {"style":{"border":{"radius":"50px"}},"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" style="border-radius:50px">' . esc_html_x( 'Resume', 'Button to download Curriculum Vitae', 'wd_s' ) . '</a></div><!-- /wp:button --><!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"border":{"radius":"50px"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background" href="mailto:contact@example.com" style="border-radius:50px">' . esc_html_x( 'Contact', 'Block pattern content', 'wd_s' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:media-text -->',
	)
);

register_block_pattern(
	'wd_s/single-page-4',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 4',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":0,"minHeight":100,"minHeightUnit":"vh","contentPosition":"center center","isDark":false,"align":"full"} --><div class="wp-block-cover alignfull is-light" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-0 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"inherit":true}} --><div class="wp-block-group"><!-- wp:group {"style":{"border":{"radius":"16px"}},"backgroundColor":"base","textColor":"contrast"} --><div class="wp-block-group has-contrast-color has-base-background-color has-text-color has-background" style="border-radius:16px"><!-- wp:heading {"textAlign":"center","level":1,"fontSize":"x-large"} --><h1 class="has-text-align-center has-x-large-font-size">' . esc_html_x( 'Page title', 'Block pattern content', 'wd_s' ) . '</h1><!-- /wp:heading --><!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Suspendisse tristique ipsum nisi, venenatis efficitur magna lacinia non. Donec congue erat eu lacus hendrerit gravida. Phasellus finibus nisl ut dui feugiat egestas. Vivamus eget neque dolor. Morbi vulputate dolor quis purus ullamcorper rhoncus, cras ut tempor diam luctus.</p><!-- /wp:paragraph --><!-- wp:list {"className":"is-style-tw-inline has-text-align-center"} --><ul class="is-style-tw-inline has-text-align-center"><li><a href="#">Instagram</a></li><li><a href="#">YouTube</a></li><li><a href="mailto:contact@example.com">' . esc_html_x( 'Contact', 'Block pattern content', 'wd_s' ) . '</a></li></ul><!-- /wp:list --></div><!-- /wp:group --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/single-page-5',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 5',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} --><div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"blockGap":"40px"}},"layout":{"inherit":true}} -->	<div class="wp-block-group"><!-- wp:heading {"level":1,"align":"wide","style":{"typography":{"lineHeight":"1.1"}}} --><h1 class="alignwide" style="line-height:1.1">' . esc_html_x( 'Page title', 'Block pattern content', 'wd_s' ) . '</h1><!-- /wp:heading --><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":"48px"}}} --><div class="wp-block-columns alignwide"><!-- wp:column {"width":"66.66%"} --><div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:paragraph --><p>Donec sit amet augue consequat, dictum diam nec, laoreet magna. Aenean nec sagittis nibh. Phasellus sit amet commodo tortor elit libero tincidunt turpis, in nisi scelerisque congue donec. Dapibus faucibus nulla eu dignissim. Curabitur at condimentum massa. Aliquam lobortis sit amet ipsum ut tincidunt. Proin mollis ipsum ornare ultrices sit amet ut dolor metus a sem. Nulla pulvinar elementum arcu et tincidunt ligula lobortis. </p><!-- /wp:paragraph --></div><!-- /wp:column -->	<!-- wp:column {"width":"33.33%"} --><div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:paragraph --><p><strong>' . esc_html_x( 'Date', 'Block pattern content', 'wd_s' ) . '</strong><br>' . esc_html_x( 'Monday, October 24', 'Block pattern content', 'wd_s' ) . '</p><!-- /wp:paragraph --><!-- wp:paragraph --><p><strong>' . esc_html_x( 'Address', 'Block pattern content', 'wd_s' ) . '</strong><br>16 Thompson Street<br>San Francisco, CA 94102</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --><!-- wp:buttons {"align":"wide"} --><div class="wp-block-buttons alignwide"><!-- wp:button {"style":{"color":{"background":"white","text":"black"}}} --><div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" style="background-color:white;color:black">' . esc_html_x( 'Get in touch', 'Block pattern content', 'wd_s' ) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/single-page-6',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 6',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"minHeight":100,"minHeightUnit":"vh","customGradient":"linear-gradient(60deg,rgb(120,107,242) 0%,rgb(52,150,207) 100%)","isDark":false,"align":"full"} --><div class="wp-block-cover alignfull is-light" style="min-height:100vh"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient" style="background:linear-gradient(60deg,rgb(120,107,242) 0%,rgb(52,150,207) 100%)"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"color":{"text":"#ffffff"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"layout":{"inherit":false,"contentSize":"1200px"}} --><div class="wp-block-group has-text-color has-link-color" style="color:#ffffff"><!-- wp:group {"style":{"spacing":{"blockGap":"16px"}},"className":"tw-mb-6","layout":{"type":"flex","allowOrientation":false}} --><div class="wp-block-group tw-mb-6"><!-- wp:image {"width":60,"height":60,"className":"is-style-rounded"} --><figure class="wp-block-image is-resized is-style-rounded"><img src="' . get_pattern_asset( 'profile-square.jpg' ) . '" alt="" width="60" height="60"/></figure><!-- /wp:image --><!-- wp:heading {"level":1,"fontSize":"large"} --><h1 class="has-large-font-size">Julie Miller</h1><!-- /wp:heading --></div><!-- /wp:group --><!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":1.4}}} --><p class="has-large-font-size" style="line-height:1.4">Nullam a dignissim lacus. Nulla at orci sed arcu luctus tristique at eget mi. Curabitur finibus neque vitae sollicitudin posuere. Maecenas sed diam tellus. Sed magna massa, venenatis vitae congue sed, dignissim ut libero. Nullam lacinia vel est sit amet faucibus quisque vel quam. In turpis iaculis ultricies at ut ante. Maecenas venenatis tincidunt libero, et vitae in <a href="#">Hooli</a>, <a href="#">Initech</a>, and <a href="#">Acme</a>.</p><!-- /wp:paragraph --><!-- wp:paragraph {"fontSize":"large","style":{"typography":{"lineHeight":1.4}}} --><p class="has-large-font-size" style="line-height:1.4">Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae. Ut ante purus, cursus eget tincidunt a, malesuada a orci. Donec rhoncus purus in erat faucibus tincidunt. In id lacus lectus. Ut facilisis mauris velit, vel pretium nisi finibus vel.</p><!-- /wp:paragraph --><!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","className":"is-style-logos-only","style":{"spacing":{"blockGap":"20px","margin":{"top":"40px"}}}} --><ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:40px"><!-- wp:social-link {"url":"#","service":"instagram"} /--><!-- wp:social-link {"url":"#","service":"twitter"} /--><!-- wp:social-link {"url":"#","service":"linkedin"} /--><!-- wp:social-link {"url":"mailto:contact@example.com","service":"mail"} /--></ul><!-- /wp:social-links --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);

register_block_pattern(
	'wd_s/single-page-7',
	array(
		'title'      => _x( 'Single Page', 'Block pattern category', 'wd_s' ) . ' 7',
		'categories' => array( 'page-single' ),
		'content'    => '<!-- wp:cover {"url":"' . get_pattern_asset( 'wide.jpg' ) . '","dimRatio":60,"minHeight":100,"minHeightUnit":"vh","align":"full"} --><div class="wp-block-cover alignfull" style="min-height:100vh"><span aria-hidden="true" class="has-background-dim-60 wp-block-cover__gradient-background has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_pattern_asset( 'wide.jpg' ) . '" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"wide","layout":{"inherit":true}} --><div class="wp-block-group alignwide"><!-- wp:heading {"textAlign":"center","level":1,"align":"wide"} --><h1 class="alignwide has-text-align-center">' . esc_html_x( 'Page title', 'Block pattern content', 'wd_s' ) . '</h1><!-- /wp:heading --><!-- wp:columns {"align":"wide","twStack":"md-2","className":"tw-justify-center","twTextAlign":"center","style":{"spacing":{"blockGap":"24px"}}} --><div class="wp-block-columns alignwide tw-cols-stack-md-2 tw-justify-center has-text-align-center"><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#ffffff80"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"},"blockGap":"16px"}}} --><div class="wp-block-column has-border-color" style="border-color:#ffffff80;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:heading {"fontSize":"large"} --><h2 class="has-large-font-size">' . esc_html_x( 'Address', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>16 Thompson Street<br>San Francisco, CA 94102</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#ffffff80"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"},"blockGap":"16px"}}} --><div class="wp-block-column has-border-color" style="border-color:#ffffff80;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:heading {"fontSize":"large"} --><h2 class="has-large-font-size">' . esc_html_x( 'Opening hours', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Monday - Friday: 9am - 7pm<br>Saturday: 9am - 10pm</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"style":{"border":{"width":"1px","style":"solid","color":"#ffffff80"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"},"blockGap":"16px"}}} --><div class="wp-block-column has-border-color" style="border-color:#ffffff80;border-style:solid;border-width:1px;padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px"><!-- wp:heading {"fontSize":"large"} --><h2 class="has-large-font-size">' . esc_html_x( 'Contact', 'Block pattern content', 'wd_s' ) . '</h2><!-- /wp:heading --><!-- wp:paragraph --><p>(123) 456-7890<br>contact@example.com</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns --></div><!-- /wp:group --></div></div><!-- /wp:cover -->',
	)
);
