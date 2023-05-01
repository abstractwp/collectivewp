<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include LinkedIn settings page.
require_once( plugin_dir_path( __FILE__ ) . 'linkedin-settings.php' );

// Register the plugin shortcode
add_shortcode('linkedin', 'linkedin_shortcode');

function linkedin_shortcode( $atts ) {
	// LinkedIn API endpoint for retrieving the authorization code
	$auth_url = "https://www.linkedin.com/oauth/v2/authorization";

	// Your LinkedIn application's Client ID and redirect URI
	$client_id = get_option('linkedin_client_id');
	$client_secret = get_option('linkedin_client_secret');
	$redirect_uri = get_option('linkedin_redirect_uri');
	$form_id = get_option('linkedin_form_id');

	// Mapping fields.
	$linkedin_field_first_name = get_option('linkedin_field_first_name') ?? 'input_1.3';
	$linkedin_field_last_name = get_option('linkedin_field_last_name') ?? 'input_1.6';
	$linkedin_field_email_address = get_option('linkedin_field_email_address') ?? 'input_2';
	$linkedin_field_username = get_option('linkedin_field_username') ?? 'input_3';

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
	$return_html = '<div>';

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
			$entry_data = array(
				$linkedin_field_first_name => $first_name,
				$linkedin_field_last_name => $last_name,
				$linkedin_field_email_address   => $email_address,
				$linkedin_field_username   => substr($email_address, 0, strpos($email_address, "@")),
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
						error_log( 'Gravity Forms entry created with ID: ' . $entry['entry_id'] );
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
		$return_html .= '<div class="flex"><a class="button primary linkedin" href="' . $auth_url . '">Register with LinkedIn</a></div>';
	} else {
		$return_html .= $messages;
	}

	$return_html .= '</div>';
	if ( !is_admin() ) {
		return $return_html;
	}
}
