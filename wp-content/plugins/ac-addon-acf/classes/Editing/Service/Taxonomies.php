<?php

namespace ACA\ACF\Editing\Service;

class Taxonomies extends Taxonomy {

	public function get_view( $context ) {
		$view = parent::get_view( $context );

		if ( $context === self::CONTEXT_BULK ) {
			$view->has_methods( true );
		}

		$view->set_multiple( true );

		return $view;
	}

}