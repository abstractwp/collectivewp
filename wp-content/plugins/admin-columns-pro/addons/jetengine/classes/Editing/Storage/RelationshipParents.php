<?php

namespace ACA\JetEngine\Editing\Storage;

use ACP;
use Jet_Engine\Relations\Relation;

class RelationshipParents implements ACP\Editing\Storage {

	/**
	 * @var Relation
	 */
	private $relation;

	public function __construct( Relation $relation ) {
		$this->relation = $relation;
	}

	public function get( $id ) {
		$item_key = 'parent_object_id';
		$result = [];

		foreach ( $this->relation->get_parents( $id ) as $item ) {
			$result[] = $item[ $item_key ];
		}

		return array_combine( $result, $result );

	}

	public function update( int $id, $data ): bool {
		$data = is_array( $data ) ? $data : [ $data ];
		$current_ids = array_keys( $this->get( $id ) );
		$updated_ids = array_intersect( $current_ids, $data );
		$deleted_ids = array_diff( $current_ids, $data );
		$added_ids = array_diff( $data, $updated_ids );

		foreach ( $deleted_ids as $delete_id ) {
			$this->relation->delete_rows( $delete_id, $id );
		}

		foreach ( $added_ids as $added_id ) {
			$this->relation->update( $added_id, $id );
		}

		return true;
	}

}