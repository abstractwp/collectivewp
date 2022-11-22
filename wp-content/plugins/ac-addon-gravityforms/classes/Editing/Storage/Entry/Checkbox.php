<?php

namespace ACA\GravityForms\Editing\Storage\Entry;

use ACA\GravityForms\Field\Field;
use ACP\Editing\Storage;
use GFAPI;

class Checkbox implements Storage {

	/**
	 * @var Field
	 */
	private $field;

	public function __construct( Field $field ) {
		$this->field = $field;
	}

	public function get( $id ) {
		$entry = GFAPI::get_entry( $id );

		if ( is_wp_error( $entry ) ) {
			return [];
		}

		$value = [];

		foreach ( array_keys( $this->field->get_sub_fields() ) as $key ) {
			if ( isset( $entry[ $key ] ) && $entry[ $key ] ) {
				$value[] = $entry[ $key ];
			}
		}

		return $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return string|null
	 */
	private function get_id_for_value( $value ) {
		foreach ( $this->field->get_sub_fields() as $key => $subfield ) {
			if ( $subfield->get_value() === $value ) {
				return (string) $key;
			}
		}

		return null;
	}

	public function update( $id, $value ) {
		// First remove each value for each subfield
		foreach ( array_keys( $this->field->get_sub_fields() ) as $key ) {
			GFAPI::update_entry_field( $id, $key, '' );
		}

		if( ! empty( $value ) ){
			// Populate subfields if value exists
			foreach ( $value as $item ) {
				$field_id = $this->get_id_for_value( $item );
				if ( $field_id ) {
					GFAPI::update_entry_field( $id, $field_id, $item );
				}
			}
		}

	}


}