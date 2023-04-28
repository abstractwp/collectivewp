<?php
/*
Plugin Name: CollectiveWP Functions
Description: Project specific functions plugin for this site
Version: 0.1.0
Author: Abstract WP
Author URI: https://www.abstractwp.com
Text Domain: collectivewp
Domain Path: /languages
*/

/**
 * Disable core block features.
 */
function disable_core_block_features() {
	remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	remove_theme_support( 'core-block-patterns' );
	add_filter( 'use_widgets_block_editor', '__return_false' );
}
add_action( 'plugins_loaded', 'disable_core_block_features' );

/*
 * Blacklist specific Gutenberg blocks
 *
 * @author Misha Rudrastyh
 * @link https://rudrastyh.com/gutenberg/remove-default-blocks.html#blacklist-blocks
 */
add_filter( 'allowed_block_types_all', 'collectivewp_blacklist_blocks' );

function collectivewp_blacklist_blocks( $allowed_blocks ) {
	// get all the registered blocks.
	$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	// then disable some of them.
	unset( $blocks[ 'core/navigation' ] );
	unset( $blocks[ 'core/site-logo' ] );
	unset( $blocks[ 'core/site-title' ] );
	unset( $blocks[ 'core/site-tagline' ] );
	unset( $blocks[ 'core/query' ] );
	unset( $blocks[ 'core/posts-list' ] );
	unset( $blocks[ 'core/avatar' ] );
	unset( $blocks[ 'core/post-title' ] );
	unset( $blocks[ 'core/post-excerpt' ] );
	unset( $blocks[ 'core/post-featured-image' ] );
	unset( $blocks[ 'core/post-content' ] );
	unset( $blocks[ 'core/post-author' ] );
	unset( $blocks[ 'core/post-date' ] );
	unset( $blocks[ 'core/post-terms' ] );
	unset( $blocks[ 'core/post-navigation-link' ] );
	unset( $blocks[ 'core/read-more' ] );
	unset( $blocks[ 'core/comments-query-loop' ] );
	unset( $blocks[ 'core/post-comments-form' ] );
	unset( $blocks[ 'core/loginout' ] );
	unset( $blocks[ 'core/term-description' ] );
	unset( $blocks[ 'core/query-title' ] );
	unset( $blocks[ 'core/post-author-biography' ] );
	unset( $blocks[ 'core/comments' ] );

	// return the new list of allowed blocks.
	return array_keys( $blocks );

}

/**
 * Remove embed blocks.
 */
function collectivewp_deny_embed_variations_blocks() {
	wp_enqueue_script( 'deny-list-blocks', plugin_dir_url( __FILE__ ) . 'js/list-blocks.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' )
);
}
add_action( 'enqueue_block_editor_assets', 'collectivewp_deny_embed_variations_blocks' );

/**
 * Disable Default Dashboard Widgets, Yoast, Gravity Forms
 *
 * @link https://digwp.com/2014/02/disable-default-dashboard-widgets/
 *
 */
function collectivewp_disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// wp..
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_activity' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_right_now' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_recent_comments' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_incoming_links' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_plugins' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'dashboard_site_health' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'wsal' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'cookiebot_status' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'fluentsmtp_reports_widget' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'wpe_dify_news_feed' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_primary' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_secondary' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_quick_press' ]);
	unset($wp_meta_boxes[ 'dashboard' ][ 'side' ][ 'core' ][ 'dashboard_recent_drafts' ]);

	// bbpress
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'bbp-dashboard-right-now' ]);
	// yoast seo
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'yoast_db_widget' ]);
	// gravity forms
	unset($wp_meta_boxes[ 'dashboard' ][ 'normal' ][ 'core' ][ 'rg_forms_dashboard' ]);
}
add_action('wp_dashboard_setup', 'collectivewp_disable_default_dashboard_widgets', 999);

/**
 * Remove screen options
 *
 * @link https://www.wpbeginner.com/wp-tutorials/how-to-disable-the-screen-options-button-in-wordpress/
 */
function collectivewp_remove_screen_options() {
	return false;
}
add_filter('screen_options_show_screen', 'collectivewp_remove_screen_options');

/**
 * Add or Remove links from the Admin Bar
 *
 * @link https://digwp.com/2011/04/admin-bar-tricks/#add-remove-links
 *
 * @link https://www.isitwp.com/remove-wordpress-logo-admin-bar/
 *
 * @link https://wordpress.stackexchange.com/questions/200296/how-to-remove-customize-from-admin-menu-bar-after-wp-4-3/201646
 *
 */
function collectivewp_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' ); // remove comments
	$wp_admin_bar->remove_menu( 'wp-logo' ); // remove WordPress menu
	$wp_admin_bar->remove_menu( 'updates' ); // remove updates
	$wp_admin_bar->remove_menu( 'new-content' ); // remove add new
	$wp_admin_bar->remove_menu( 'customize' ); // remove customizer
}
add_action( 'wp_before_admin_bar_render', 'collectivewp_admin_bar_render' );

/**
 * Remove the Howdy Text in WordPress
 *
 * @link https://wpintensity.com/change-howdy-text-wordpress/
 *
 */
function collectivewp_remove_howdy( $wp_admin_bar ) {
	if (is_admin()) {
		$my_account = $wp_admin_bar->get_node('my-account');
		$newtitle   = str_replace( 'Howdy,', '', $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}
}
add_filter( 'admin_bar_menu', 'collectivewp_remove_howdy',25 );

// Register the plugin shortcode
add_shortcode('linkedin', 'linkedin_shortcode');

function linkedin_shortcode( $atts ) {
	// LinkedIn API endpoint for retrieving the authorization code
	$auth_url = "https://www.linkedin.com/oauth/v2/authorization";

	// Your LinkedIn application's Client ID and redirect URI
	$client_id     = "86soip2obkw8cx";
	$client_secret = "QWNvx3DqAiDOMG4F";
	$redirect_uri = "https://collectivewp.test/register/";

	// Set the query parameters for retrieving the authorization code
	$params = array(
		"response_type" => "code",
		"client_id"     => $client_id,
		"redirect_uri"  => $redirect_uri,
		"state"         => "login", // set a state parameter to prevent CSRF attacks
		"scope"         => "r_liteprofile r_emailaddress" // specify the permissions that your LinkedIn application is requesting
	);

	// Redirect the user to the LinkedIn API endpoint for authentication and authorization
	$auth_url .= "?" . http_build_query($params);

	// Display the LinkedIn login button on the login page
	echo '<div class="container">';

	$messages = '';
	// Check if the user has been redirected back to the website after granting permission to your LinkedIn application
	if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] === 'login') {
		// Exchange the authorization code for an access token
		$authorization_code = $_GET['code'];

		$token_url    = "https://www.linkedin.com/oauth/v2/accessToken";
		$token_params = array(
				"grant_type" => "authorization_code",
				"client_id" => $client_id,
				"client_secret" => $client_secret,
				"redirect_uri" => $redirect_uri,
				"code" => $authorization_code
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $token_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
		$token_response = curl_exec($ch);
		curl_close($ch);

		$token_data = json_decode($token_response);

		if ( ( isset( $token_data->error ) && 'invalid_request' !== $token_data->error ) ||
			! isset( $token_data->error ) ) {
			// Use the access token to retrieve the user's LinkedIn profile
			$profile_url   = "https://api.linkedin.com/v2/me";
			$email_url    = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))";
			$access_token = $token_data->access_token;

			// Create headers array with access token
			$headers = array(
				"Authorization: Bearer ".$access_token,
				"Content-Type: application/json",
				"x-li-format: json"
			);

			// Create curl request to retrieve user's profile
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $profile_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$profile_result = curl_exec($ch);
			curl_close($ch);

			// Create curl request to retrieve user's email
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $email_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$email_result = curl_exec($ch);
			curl_close($ch);

			// Parse the results
			$profile_data = json_decode($profile_result, true);
			$email_data  = json_decode($email_result, true);

			// Extract the relevant information
			$first_name     = $profile_data["firstName"]["localized"]["en_US"];
			$last_name     = $profile_data["lastName"]["localized"]["en_US"];
			$email_address = $email_data["elements"][0]["handle~"]["emailAddress"];


			// Set the form ID and entry data
			$form_id    = 5; // Replace with your actual form ID
			$entry_data = array(
				'input_1.3' => $first_name,
				'input_1.6' => $last_name,
				'input_2'   => $email_address,
				'input_3'   => substr($email_address, 0, strpos($email_address, "@")),
			);

			// Create a new instance of the Gravity Forms API
			$api = new GFAPI();

			// Check if the form ID is valid
			$form = $api->get_form( $form_id );
			if ( $form ) {
				// Create the new entry
				$entry = $api->submit_form( $form_id, $entry_data );
				// Check if the entry was created successfully
				if ( is_wp_error( $entry ) ) {
					// Handle the error
					error_log( 'Failed to create Gravity Forms entry: ' . $entry->get_error_message() );
				} else {
					if ( isset($entry['entry_id']) ) {
						// Entry created successfully
						error_log( 'Gravity Forms entry created with ID: ' . $entry['id'] );
						if ( isset( $entry['confirmation_type'] ) && $entry['confirmation_type'] === 'redirect' ) {
							wp_redirect( $entry['confirmation_redirect'] );
							exit;
						} else {
							$messages = $entry['confirmation_message'];
						}

					} else {
						if (isset($entry['validation_messages'])) {
							array_unique( $entry['validation_messages'] );
							$messages = implode('<br/>', $entry['validation_messages']);
						}
					}
				}

			} else {
				// Invalid form ID
				error_log( 'Invalid Gravity Forms form ID: ' . $form_id );
			}
		}
		else {
			if ( isset( $token_data->error_description ) && '' !== $token_data->error_description ) {
				error_log( $token_data->error_description );
			}
		}

	}
	if ( '' === $messages ) {
		echo '<div class="flex"><a class="button primary linkedin" href="' . $auth_url . '">Register with LinkedIn</a></div>';
	} else {
		echo $messages;
	}
	echo '</div>';
}
