<?php

namespace ACA\ACF;

use AC;
use AC\Plugin\Version;
use AC\PluginInformation;
use AC\Registrable;
use AC\Request;
use AC\Service\Setup;
use ACA\ACF\FieldGroup;
use ACA\ACF\Plugin\SetupFactory;
use ACA\ACF\RequestHandler\MapLegacyListScreen;
use ACA\ACF\Search;
use ACA\ACF\Service\AddColumns;
use ACA\ACF\Service\ColumnSettings;
use ACA\ACF\Service\InitColumn;
use ACA\ACF\Service\Scripts;
use ACA\ACF\Sorting;
use ACP\RequestHandlerFactory;
use ACP\RequestParser;

final class AdvancedCustomFields extends AC\Plugin {

	public function __construct( $file, Version $version ) {
		parent::__construct( $file, $version );

		$column_initiator = new ColumnInstantiator(
			new ConfigFactory( new FieldFactory() ),
			new Search\ComparisonFactory(),
			new Sorting\ModelFactory(),
			new Editing\ModelFactory(),
			new Filtering\ModelFactory()
		);

		$setup_factory = new SetupFactory( 'aca_acf_version', $this->get_version() );

		$request_handler_factory = new RequestHandlerFactory( new Request() );
		$request_handler_factory->add( 'aca-acf-map-legacy-list-screen', new MapLegacyListScreen( AC()->get_storage() ) );

		$services = [
			new ColumnGroup(),
			new Service\LegacyColumnMapper(),
			new Service\RemoveDeprecatedColumnFromTypeSelector(),
			new AddColumns(
				new FieldRepository( new FieldGroup\QueryFactory() ),
				new FieldsFactory(),
				new ColumnFactory( $column_initiator )
			),
			new Scripts( $this->get_location() ),
			new InitColumn( $column_initiator ),
			new ColumnSettings(),
			new RequestParser( $request_handler_factory ),
		];

		$services[] = new Setup( $setup_factory->create( AC\Plugin\SetupFactory::SITE ) );

		if ( $this->is_network_active() ) {
			$services[] = new Setup( $setup_factory->create( AC\Plugin\SetupFactory::NETWORK ) );
		}

		array_map( function ( Registrable $service ) {
			$service->register();
		}, $services );
	}

	private function is_network_active() {
		return ( new PluginInformation( $this->get_basename() ) )->is_network_active();
	}

}