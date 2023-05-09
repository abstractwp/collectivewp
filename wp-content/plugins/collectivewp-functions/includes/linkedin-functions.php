<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include LinkedIn settings page.
require_once( plugin_dir_path( __FILE__ ) . 'linkedin-settings.php' );

// Register the plugin shortcode
add_shortcode('linkedin-register', 'linkedin_register_shortcode');

/**
 * Show register via LinkedIn button.
 *
 * @param array $atts the shortcode attributes.
 */
function linkedin_register_shortcode( $atts ) {
	// LinkedIn API endpoint for retrieving the authorization code
	$auth_url = "https://www.linkedin.com/oauth/v2/authorization";

	// Your LinkedIn application's Client ID and redirect URI
	$client_id = get_option('linkedin_client_id');
	$client_secret = get_option('linkedin_client_secret');
	$redirect_uri = site_url( '/register/' );

	$thank_you_url = get_option('linkedin_redirect_uri') ?? site_url( '/register/thank-you-for-registering/' );

	// Set the query parameters for retrieving the authorization code.
	$params = array(
		"response_type" => "code",
		"client_id"     => $client_id,
		"redirect_uri"  => $redirect_uri,
		"state"         => "login",
		"scope"         => "r_liteprofile r_emailaddress"
	);

	// Redirect the user to the LinkedIn API endpoint for authentication and authorization.
	$auth_url .= "?" . http_build_query($params);

	// Display the LinkedIn login button on the login page.
	$return_html = '<div>';

	$messages = '';
	// Check if the user has been redirected back to the website after granting permission to your LinkedIn application.
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
			// Use the access token to retrieve the user's LinkedIn profile.
			$profile_url   = "https://api.linkedin.com/v2/me";
			$email_url    = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))";
			$access_token = $token_data->access_token;

			// Create headers array with access token.
			$headers = array(
				"Authorization: Bearer ".$access_token,
				"Content-Type: application/json",
				"x-li-format: json"
			);

			// Create curl request to retrieve user's profile.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $profile_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$profile_result = curl_exec($ch);
			curl_close($ch);

			// Create curl request to retrieve user's email.
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $email_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$email_result = curl_exec($ch);
			curl_close($ch);

			// Parse the results.
			$profile_data = json_decode($profile_result, true);
			$email_data  = json_decode($email_result, true);

			// Extract the relevant information.
			$first_name     = $profile_data["firstName"]["localized"]["en_US"];
			$last_name     = $profile_data["lastName"]["localized"]["en_US"];
			$email_address = $email_data["elements"][0]["handle~"]["emailAddress"];
			$username      = substr($email_address, 0, strpos($email_address, "@"));
			$password  = wp_generate_password();

			// Insert the new user
			$user_id = bp_core_signup_user( $username, $password, $email_address, array( 'meta' => [] ) );

			// Check if the user was successfully created
			if ( ! is_wp_error( $user_id ) ) {
				$user_data = array(
					'ID' => $user_id,
					'display_name' => $first_name . '##' . $last_name
				);
				wp_update_user( $user_data );

				wp_redirect( $thank_you_url );
				exit;
			} else {
				$messages = 'Register fail!';
			}
		} else {
			if ( isset( $token_data->error_description ) && '' !== $token_data->error_description ) {
				error_log( $token_data->error_description );
			}
		}

	}
	if ( '' === $messages ) {
		$return_html .= '<div class="flex"><a class="button primary linkedin" href="' . $auth_url . '">Register with LinkedIn</a></div>';
	} else {
		$return_html .= '<p>' . $messages . '</p>';

		if (strpos($messages, 'Sorry, that username already exists!') !== false) {
			$auth_url = "https://www.linkedin.com/oauth/v2/authorization";

			$params = array(
				"response_type" => "code",
				"client_id"     => $client_id,
				"redirect_uri"  => site_url( '/login/' ),
				"state"         => "login",
				"scope"         => "r_liteprofile r_emailaddress"
			);

			$auth_url .= "?" . http_build_query($params);

			$return_html .= '<div class="flex"><a class="button primary linkedin" href="' . $auth_url . '">Login with LinkedIn</a></div>';
		}
	}

	$return_html .= '</div>';
	if ( !is_admin() ) {
		return $return_html;
	}
}


// Register the plugin shortcode.
add_shortcode('linkedin-login', 'linkedin_login_shortcode');

/**
 * Login with linkedin shortcode.
 */
function linkedin_login_shortcode( $atts ) {

	if ( is_user_logged_in() ) {
		$html = '<div class="has-global-padding is-layout-constrained wp-block-group">';
		$current_user = wp_get_current_user();
		$username = $current_user->user_login;
		$html .= '<div class="flex"><a class="button primary" href="' . home_url( '/members/' . $username . '/profile/' ) . '">Go to your Profile</a></div></div>';

		return $html;
	}

	$client_id = get_option('linkedin_client_id');
	$client_secret = get_option('linkedin_client_secret');
	$login_redirect_url = site_url( '/login/' );

	$auth_url = "https://www.linkedin.com/oauth/v2/authorization";

	$params = array(
		"response_type" => "code",
		"client_id"     => $client_id,
		"redirect_uri"  => $login_redirect_url,
		"state"         => "login",
		"scope"         => "r_liteprofile r_emailaddress"
	);

	$auth_url .= "?" . http_build_query($params);

	if (isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] === 'login') {
		$authorization_code = $_GET['code'];
		$token_url    = "https://www.linkedin.com/oauth/v2/accessToken";
		$token_params = array(
				"grant_type" => "authorization_code",
				"client_id" => $client_id,
				"client_secret" => $client_secret,
				"redirect_uri" => $login_redirect_url,
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
			$email_url    = "https://api.linkedin.com/v2/emailAddress?q=members&projection=(elements*(handle~))";
			$access_token = $token_data->access_token;

			$headers = array(
				"Authorization: Bearer ".$access_token,
				"Content-Type: application/json",
				"x-li-format: json"
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $email_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$email_result = curl_exec($ch);
			curl_close($ch);

			$email_data  = json_decode($email_result, true);
			$email_address = $email_data["elements"][0]["handle~"]["emailAddress"];

			$user = get_user_by( 'email', $email_address );

			if ( $user ) {
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $user->user_login, $user );
				$profile_url = home_url( '/members/' . $user->user_login . '/profile/' );
				wp_redirect( $profile_url );
				exit;
			} else {
				wp_redirect( site_url( '/register/' ) );
			}
		} else {
			if ( isset( $token_data->error_description ) && '' !== $token_data->error_description ) {
				error_log( $token_data->error_description );
			}
		}
	} else {
		return '<div class="flex"><a class="button primary linkedin" href="' . $auth_url . '">Login with LinkedIn</a></div>';
	}
}

function send_user_data_to_ac( $user_id ) {
	// Get user data
	$user = get_userdata( $user_id );
	$data_name = explode( "##", $user->display_name );
	$first_name = $data_name[0];
	$last_name = $data_name[1];
	$email     = $user->user_email;
	$password  = wp_generate_password();
	$username  = $user->user_login;

	$user_data = array(
		'ID'           => $user_id,
		'first_name'    => $first_name,
		'last_name'    => $last_name,
		'display_name' => $user->display_name
	);

	wp_update_user($user_data);

	wp_set_password($password, $user_id);

	// Set up the API URL and key
	$api_url = get_option('activecampaign_api_uri');
	$api_key = get_option('activecampaign_api_key');
	$form_id = get_option('activecampaign_form_id');

	$params = array(
		// this is the action that adds a contact
		'api_action'   => 'contact_add',
		'api_output'   => 'serialize',
	);

	$post = array(
		'email'              => $email,
		'first_name'          => $first_name,
		'last_name'          => $last_name,
		'form'               => $form_id,
		'field[%PASSWORD%,0]' => $password,
		'field[%USERNAME%,0]' => $username
	);

	$query = "";
	foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
	$query = rtrim($query, '& ');

	$data = "";
	foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
	$data = rtrim($data, '& ');

	$api_url = rtrim($api_url, '/ ');

	if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

	if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
			die('JSON not supported. (introduced in PHP 5.2.0)');
	}
	$api = $api_url . '/admin/api.php?' . $query;

	$request = curl_init($api);
	curl_setopt($request, CURLOPT_HTTPHEADER, array('API-TOKEN: ' . $api_key));
	curl_setopt($request, CURLOPT_HEADER, 0);
	curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($request, CURLOPT_POSTFIELDS, $data);
	curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
	$response = (string)curl_exec($request);
	curl_close($request);

	if ( !$response ) {
		die('Nothing was returned. Do you have a connection to Email Marketing server?');
	}

	$result = unserialize($response);

	if ( ! $result['result_code'] ) {
		error_log( $result['result_message'] );
	}
}

add_action( 'bp_core_activated_user', 'send_user_data_to_ac' );
