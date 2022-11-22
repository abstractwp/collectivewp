<?php

namespace ACA\YoastSeo\Service;

use AC;
use AC\Registrable;
use ACP;
use ReflectionException;

final class Columns implements Registrable {

	public function register() {
		add_action( 'ac/column_types', [ $this, 'add_columns' ] );
	}

	/**
	 * @param AC\ListScreen $list_screen
	 *
	 * @throws ReflectionException
	 */
	public function add_columns( AC\ListScreen $list_screen ) {

		switch ( true ) {
			case $list_screen instanceof AC\ListScreen\User:
				$list_screen->register_column_types_from_dir( 'ACA\YoastSeo\Column\User' );

				break;

			case $list_screen instanceof AC\ListScreen\Post:
				$list_screen->register_column_types_from_dir( 'ACA\YoastSeo\Column\Post' );

				break;
			case $list_screen instanceof ACP\ListScreen\Taxonomy:
				$list_screen->register_column_types_from_dir( 'ACA\YoastSeo\Column\Taxonomy' );

				break;
		}

	}

}