<?php

namespace ACA\YoastSeo;

use AC;
use AC\PluginInformation;
use AC\Registrable;
use ACA\YoastSeo\Service;

class YoastSeo extends AC\Plugin {

	public function __construct( $file, AC\Plugin\Version $version ) {
		parent::__construct( $file, $version );
	}

	/**
	 * Register hooks
	 */
	public function register() {
		$plugin_information = new PluginInformation( $this->get_basename() );
		$is_network_active = $plugin_information->is_network_active();
		$setup_factory = new AC\Plugin\SetupFactory( 'aca_yoast_version', $this->get_version() );

		$services = [
			new Service\HideFilters(),
			new Service\ColumnGroups(),
			new Service\Columns(),
			new Service\Admin( $this->get_location() ),
			new AC\Service\Setup( $setup_factory->create( AC\Plugin\SetupFactory::SITE ) ),
		];

		if ( $is_network_active ) {
			$services[] = new AC\Service\Setup( $setup_factory->create( AC\Plugin\SetupFactory::NETWORK ) );
		}

		array_map( function ( Registrable $service ) {
			$service->register();
		}, $services );
	}

}