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
			add_action('wp_footer', [$this, 'track_page_load']);
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
