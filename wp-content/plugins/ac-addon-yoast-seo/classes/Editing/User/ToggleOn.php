<?php

namespace ACA\YoastSeo\Editing\User;

use AC;
use ACP;

class ToggleOn extends ACP\Editing\Service\BasicStorage {

	public function __construct( $meta_key ) {
		parent::__construct( new ACP\Editing\Storage\User\Meta( $meta_key ) );
	}

	public function get_view( $context ) {
		return new ACP\Editing\View\Toggle(
			new AC\Type\ToggleOptions(
				new AC\Helper\Select\Option( '' ),
				new AC\Helper\Select\Option( 'on' )
			)
		);
	}

}