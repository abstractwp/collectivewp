<?php
/**
 * // phpcs:ignoreFile.
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wd_s
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<script src="<?php echo esc_url( site_url( '/wp-includes/js/jquery/jquery.js' ) ); ?>"></script>
	<?php wp_head(); ?>

</head>

<body <?php body_class( 'site-wrapper' ); ?>>

	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'wd_s' ); ?></a>

	<header class="site-header sticky-header">

		<div class="site-header-content">

			<div class="site-branding">

				<?php
				if ( is_home() || ( 'post' === get_post_type() && has_category( 'newsletter' ) ) ) {
					$image          = '<img src="' . esc_url( get_parent_theme_file_uri( '/build/images/bookmarks-logo.png' ) ) . '" class="bookmarks-logo" alt="bookmarks" width="300" />';
					$wd_s_logo_html = sprintf(
						'<a href="%1$s" class="bookmarks-logo-link" rel="bookmarks">%2$s</a>',
						esc_url( home_url( '/blog/' ) ),
						$image
					);

					echo $wd_s_logo_html;
				} elseif ( has_category( 'thoughts' ) || is_category( 'thoughts' ) ) {
					$image          = '<img src="' . esc_url( get_parent_theme_file_uri( '/build/images/thoughts-logo.png' ) ) . '" class="thoughts-logo" alt="thoughts" width="300" />';
					$wd_s_logo_html = sprintf(
						'<a href="%1$s" class="thoughts-logo-link" rel="thoughts">%2$s</a>',
						esc_url( home_url( '/thoughts/' ) ),
						$image
					);

					echo $wd_s_logo_html;
				} else {
					the_custom_logo();
				}
				?>

				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title h2"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title h2"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php endif; ?>

				<?php

				$wd_s_description = get_bloginfo( 'description', 'display' );
				if ( $wd_s_description || is_customize_preview() ) :
					?>
					<p class="site-description"><?php echo esc_html( $wd_s_description ); ?></p>
				<?php endif; ?>

			</div><!-- .site-branding -->

			<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'mobile' ) ) : ?>
				<button type="button" class="off-canvas-open" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open Menu', 'wd_s' ); ?>"></button>
			<?php endif; ?>

		</div><!-- .site-header-content -->

		<nav id="site-navigation" class="main-navigation navigation-menu" aria-label="<?php esc_attr_e( 'Main Navigation', 'wd_s' ); ?>">
			<?php
			$wd_s_location = 'primary';
			if ( is_page_template( 'page-templates/landing-page.php' ) ) {
				$wd_s_location = 'landing';
			}
			wp_nav_menu(
				[
					'fallback_cb'    => false,
					'theme_location' => $wd_s_location,
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'menu dropdown container',
					'container'      => false,
				]
			);
			?>
		</nav><!-- #site-navigation-->

	</header><!-- .site-header-->
