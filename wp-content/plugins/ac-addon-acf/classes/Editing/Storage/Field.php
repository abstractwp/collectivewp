<?php

namespace ACA\ACF\Editing\Storage;

use ACP\Editing\Storage;

class Field implements Storage {

	/**
	 * @var string
	 */
	private $field_key;

	/**
	 * @var string
	 */
	private $id_prefix;

	/**
	 * @var ReadStorage
	 */
	private $read_storage;

	public function __construct( $field_key, $id_prefix, ReadStorage $read_storage ) {
		$this->field_key = (string) $field_key;
		$this->id_prefix = (string) $id_prefix;
		$this->read_storage = $read_storage;
	}

	public function get( $id ) {
		return $this->read_storage->get( $id );
	}

	public function update( $id, $value ) {
		return false !== update_field( $this->field_key, $value, $this->id_prefix . $id );
	}

}