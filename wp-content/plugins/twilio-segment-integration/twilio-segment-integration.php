<?php
/**
 * Plugin Name: Twilio Segment Integration
 * Plugin URI: https://www.abstractwp.com/
 * Description: A WordPress plugin to integrate the Twilio Segment PHP SDK
 * Version: 1.0.0
 * Author: AbstractWP
 * Author URI: https://www.example.com
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: twilio-segment-integration
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use Segment\Client;

class TwilioSegmentIntegration {
		private $client;

		public function __construct() {
			$this->initialize();
			$this->hooks();
		}

		public function initialize() {
			$write_key = '';

			if ( get_option( 'options_segment_write_key' ) ) {
				$write_key = get_option( 'options_segment_write_key' );
			}

			$this->client = new Client($write_key, [
				'ssl' => true,
			]);
		}

		public function hooks() {
			add_action('init', [$this, 'load_textdomain']);

			if (isset($_COOKIE["CookieConsent"])) {
				switch ($_COOKIE["CookieConsent"]) {
					case "-1":
						//The user is not within a region that requires consent - all cookies are accepted

						add_action('wp_footer', [$this, 'track_page_load']);
						break;

					default: //The user has given their consent

						//Read current user consent in encoded JavaScript format
						$valid_php_json = preg_replace('/\s*:\s*([a-zA-Z0-9_]+?)([}\[,])/', ':"$1"$2', preg_replace('/([{\[,])\s*([a-zA-Z0-9_]+?):/', '$1"$2":', str_replace("'", '"',stripslashes($_COOKIE["CookieConsent"]))));
						$CookieConsent = json_decode($valid_php_json);

						if (!filter_var($CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN)
						&& !filter_var($CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN) && !
						filter_var($CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN)) {
							//The user has opted out of cookies, set strictly necessary cookies only
						} else {

						if (filter_var($CookieConsent->preferences, FILTER_VALIDATE_BOOLEAN)) {
							//Current user accepts preference cookies
						} else {
							//Current user does NOT accept preference cookies
						}

						if (filter_var($CookieConsent->statistics, FILTER_VALIDATE_BOOLEAN)) {
							//Current user accepts statistics cookies
							add_action('wp_footer', [$this, 'track_page_load']);
						} else {
							//Current user does NOT accept statistics cookies
						}

						if (filter_var($CookieConsent->marketing, FILTER_VALIDATE_BOOLEAN)) {
							//Current user accepts marketing cookies
						} else {
							//Current user does NOT accept marketing cookies
						}
					}
				}
			} else {
				//The user has not accepted cookies - set strictly necessary cookies only
			}
		}

		public function load_textdomain() {
			load_plugin_textdomain('twilio-segment-integration', false, dirname(plugin_basename(__FILE__)) . '/languages');
		}

		public function track_page_load() {
			if (is_user_logged_in()) {
				$user   = wp_get_current_user();
				$userId = $user->ID;
			} else {
				$userId = 'anonymous';
			}

			$page_title = get_the_title();
			$page_url   = get_permalink();

			if (is_archive()) {
				$page_title = get_the_archive_title();
			}

			if (is_category() || is_tag()) {
				$page_url = get_term_link( get_queried_object() );

				$page_url = str_replace( "/category/thoughts/", "/thoughts/", $page_url );
			}

			if (is_post_type_archive()) {
				$page_url = get_post_type_archive_link( get_post_type() );
			}

			$this->client->page([
				'userId'     => $userId,
				'name'       => $page_title,
				'properties' => [
					'title' => $page_title,
					'url'   => $page_url,
				],
			]);
		}
}

$twilio_segment_integration = new TwilioSegmentIntegration();
