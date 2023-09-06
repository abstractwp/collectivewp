<?php
/**
 * Bookmark patterns.
 *
 * @package wd_s
 */

use function WebDevStudios\wd_s\get_pattern_asset;

register_block_pattern(
	'wd_s/bookmark',
	array(
		'title'      => __( 'Bookmark', 'wd_s' ),
		'categories' => array( 'pages' ),
		'content'    => '<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:image {"id":214,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/01/Website_BookmarksIcon_Wide-1024x256.png" alt="" class="wp-image-214"/></figure>
		<!-- /wp:image -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:paragraph -->
		<p><strong>Welcome back to Bookmarks.  This is Collective\'s weekly newsletter about the world of work and place.</strong></p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p>We\'ve.</p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph {"align":"left"} -->
		<p class="has-text-align-left"><br>As a reminder, we\'ll be sending these weekly and if you have feedback shoot us a note at&nbsp;<a href="mailto:hello@collectivewp.com" target="_blank" rel="noreferrer noopener">hello@collectivewp.com</a>.  We were overjoyed with your feedback and support from our initial launch, so keep it coming!  It\'s our hope as always that by helping to share information, and brining resources to the community we can help everyone to build that future more quickly.</p>
		<!-- /wp:paragraph -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://collectivewp.com/contact/">Contact Us</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:paragraph -->
		<p>Lastly, if you want to sponsor, support, or partner with Collective you can reach out at the link above. Now onto the good stuff.</p>
		<!-- /wp:paragraph -->

		<!-- wp:spacer {"height":"52px"} -->
		<div style="height:52px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:separator {"align":"full","backgroundColor":"primary"} -->
		<hr class="wp-block-separator alignfull has-text-color has-primary-color has-alpha-channel-opacity has-primary-background-color has-background"/>
		<!-- /wp:separator -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:heading {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} -->
		<h2 class="has-small-font-size" style="text-transform:uppercase"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">Three Things You Should Know</mark></h2>
		<!-- /wp:heading -->

		<!-- wp:heading {"className":"tw-heading-border-bottom"} -->
		<h2 class="tw-heading-border-bottom">From The Past Week In Work &amp; Place</h2>
		<!-- /wp:heading -->

		<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"40px"}}},"fontSize":"large"} -->
		<h3 class="has-large-font-size" style="margin-top:40px">1.The Four Day Work Week</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>Burnout.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="">Read More</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->

		<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"40px"}}},"fontSize":"large"} -->
		<h3 class="has-large-font-size" style="margin-top:40px">2. Obsolescence and Conversions </h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>W</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="">Read More</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->

		<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"40px"}}},"fontSize":"large"} -->
		<h3 class="has-large-font-size" style="margin-top:40px">3.The Results of Dropbox\'s First "Life In Virtual First" Survey</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>As must be prepared to update skillsets and adapt their teams to these changes as well.  </p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"}} -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="">Read More</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:group {"align":"full","backgroundColor":"tertiary","layout":{"inherit":true,"type":"constrained"}} -->
		<div class="wp-block-group alignfull has-tertiary-background-color has-background"><!-- wp:group {"backgroundColor":"tertiary"} -->
		<div class="wp-block-group has-tertiary-background-color has-background"><!-- wp:heading {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} -->
		<h2 class="has-small-font-size" style="text-transform:uppercase">One Big Thing</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.4"}},"fontSize":"large"} -->
		<p class="has-large-font-size" style="line-height:1.4">"The" Person </p>
		<!-- /wp:paragraph --></div>
		<!-- /wp:group --></div>
		<!-- /wp:group -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group"><!-- wp:columns {"style":{"spacing":{"blockGap":"48px"}},"className":"tw-cols-stack-md tw-row-gap-small"} -->
		<div class="wp-block-columns tw-cols-stack-md tw-row-gap-small"><!-- wp:column {"verticalAlignment":"top"} -->
		<div class="wp-block-column is-vertically-aligned-top"><!-- wp:heading {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} -->
		<h2 class="has-small-font-size" style="text-transform:uppercase"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">Someone you should know</mark></h2>
		<!-- /wp:heading -->

		<!-- wp:heading -->
		<h2>In The Collective World of Work And Place</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><strong>Jomal McNeal</strong>:  has spent many years assisting stakeholders in aligning their mission and built environment. The journey starts in the healthcare environment where complex clients rely on infrastructure to save lives and then transitions to Workplace where a culmination of business strategy, process and space enable today’s knowledge workers to effectively collaborate on complex solutions. Jomal believes collecting high quality data in discovery leads to best-in-class design. We are stronger together therefore discovery must also be totally inclusive. The workplace is an effective tool in bringing people together when based on great discovery.</p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"id":494,"sizeSlug":"full","linkDestination":"none"} -->
		<figure class="wp-block-image size-full"><img src="https://collectivewp.com/wp-content/uploads/2023/02/Jomal.jpeg" alt="" class="wp-image-494"/></figure>
		<!-- /wp:image -->

		<!-- wp:paragraph -->
		<p><strong>In His Own Words</strong>:  "My favorite poem, “Me We”, by Muhammad Ali, is one of the shortest poems in the English language and sums my personal philosophy up quite nicely. &nbsp; This philosophy drives my work at Reworc where we leverage technology to scale discovery conversations to include entire organizations and better inform design. This philosophy, “Me We”, also motivates me to continue in helping to build the workplace community of practice called The Mosh Pit.&nbsp; Where each Friday people and place professionals around the world gather to focus on changing the world one workplace at a time.&nbsp; Finally, the “Me We” philosophy inspires me to contribute to equity diversity, inclusion and belonging initiatives within corporate real estate by taking a respective leadership role in CoreNet’s Northern California Chapter.&nbsp; I welcome the opportunity to connect with likeminded people."</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="">Connect On LinkedIn</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
		<!-- /wp:group -->

		<!-- wp:spacer {"height":"16px"} -->
		<div style="height:16px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:separator {"align":"full","backgroundColor":"primary"} -->
		<hr class="wp-block-separator alignfull has-text-color has-primary-color has-alpha-channel-opacity has-primary-background-color has-background"/>
		<!-- /wp:separator -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group"><!-- wp:columns -->
		<div class="wp-block-columns"><!-- wp:column -->
		<div class="wp-block-column">

		<!-- wp:heading {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} -->
		<h2 class="has-small-font-size" style="text-transform:uppercase"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">A product we\'re into right now</mark></h2>
		<!-- /wp:heading -->

		<!-- wp:heading {"textAlign":"left"} -->
		<h2 class="has-text-align-left">NomadGo</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><strong>About  X Product:  </strong>Their Mission </p>
		<!-- /wp:paragraph -->

		<!-- wp:paragraph -->
		<p><strong>What We Think: </strong> Workplace </p>
		<!-- /wp:paragraph -->

		<!-- wp:image {"id":486,"sizeSlug":"large","linkDestination":"none"} -->
		<figure class="wp-block-image size-large"><img src="https://collectivewp.com/wp-content/uploads/2023/02/nomad-go-1024x816.png" alt="" class="wp-image-486"/></figure>
		<!-- /wp:image -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://www.nomad-go.com/metashelf">Check It Out For Yourself</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:column --></div>
		<!-- /wp:columns --></div>
		<!-- /wp:group -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:separator {"align":"full","backgroundColor":"primary"} -->
		<hr class="wp-block-separator alignfull has-text-color has-primary-color has-alpha-channel-opacity has-primary-background-color has-background"/>
		<!-- /wp:separator -->

		<!-- wp:spacer {"height":"32px"} -->
		<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
		<!-- /wp:spacer -->

		<!-- wp:group {"layout":{"type":"constrained"}} -->
		<div class="wp-block-group"><!-- wp:heading {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} -->
		<h2 class="has-small-font-size" style="text-transform:uppercase"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">That\'s It For This WEek</mark></h2>
		<!-- /wp:heading -->

		<!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"20px"}}}} -->
		<h3 style="margin-top:20px">You Did It!</h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p>We\'re glad you read all the way to the bottom!  We appreciate you reading our newsletter.  We\'d love it if you checked out our resources page as well.  It\'s full of books, templates, and other great stuff all related to the worlds of work and place.</p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons -->
		<div class="wp-block-buttons"><!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="https://collectivewp.com/resources/">Visit Our Resources</a></div>
		<!-- /wp:button --></div>
		<!-- /wp:buttons --></div>
		<!-- /wp:group -->',
	)
);
