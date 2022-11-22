<?php

namespace ACA\ACF\Plugin;

use AC;
use AC\Plugin\UpdateCollection;
use ACA\ACF\Plugin\Update;

class SetupFactory extends AC\Plugin\SetupFactory {

	public function create( $type ) {

		switch ( $type ) {
			case self::SITE:
				$this->updates = new UpdateCollection( [
					new Update\V3000(),
				] );
				break;
		}

		return parent::create( $type );
	}

}