<?php

namespace ACA\ACF\Plugin\Update;

use AC\ListScreenRepository\Storage\ListScreenRepository;
use AC\Plugin\Update;
use AC\Plugin\Version;

final class V3000 extends Update {

	public function __construct() {
		parent::__construct( new Version( '3.0' ) );
	}

	public function apply_update() {
		$repositories = AC()->get_storage()->get_repositories();

		foreach ( $repositories as $repository ) {
			if ( ! $repository->is_writable() ) {
				continue;
			}

			$this->migrate_v2_to_v3_columns_for_repo( $repository );
		}
	}

	private function migrate_v2_to_v3_columns_for_repo( ListScreenRepository $repository ) {
		foreach ( $repository->find_all() as $list_screen ) {
			$settings = $list_screen->get_settings();
			$updated = false;

			foreach ( $settings as $column_name => $setting ) {
				if ( 'column-acf_field' === $setting['type'] ) {
					$field = $setting['field'];
					$acf_field = acf_get_field( $setting['field'] );

					if ( $acf_field && $acf_field['type'] === 'group' && isset( $setting['sub_field'] ) ) {
						$field = 'acfgroup__' . $field . '-' . $setting['sub_field'];
					}

					$setting['type'] = $field;

					$settings[ $column_name ] = $setting;
					$updated = true;
				}
			}

			if ( $updated ) {
				$list_screen->set_settings( $settings );

				$repository->save( $list_screen );
			}
		}
	}

}