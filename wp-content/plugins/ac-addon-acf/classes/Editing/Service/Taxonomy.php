<?php

namespace ACA\ACF\Editing\Service;

use AC\Request;
use ACP\Editing\PaginatedOptions;
use ACP\Editing\Service;
use ACP\Editing\Storage;
use ACP\Editing\View;
use ACP\Helper\Select\Paginated\Terms;

class Taxonomy implements Service, PaginatedOptions {

	/**
	 * @var string
	 */
	private $taxonomy;

	/**
	 * @var Storage
	 */
	private $storage;

	public function __construct( $taxonomy, Storage $storage ) {
		$this->taxonomy = (string) $taxonomy;
		$this->storage = $storage;
	}

	public function get_view( $context ) {
		$view = new View\AjaxSelect();
		$view->set_clear_button( true );

		return $view;
	}

	public function get_value( $id ) {
		$terms = ac_helper()->taxonomy->get_terms_by_ids(
			$this->storage->get( (int) $id ),
			$this->taxonomy
		);

		$values = [];

		foreach ( $terms as $term ) {
			$values[ $term->term_id ] = $term->name;
		}

		return $values;
	}

	public function update( Request $request ) {
		$id = (int) $request->get( 'id' );

		$params = $request->get( 'value' );

		if ( ! isset( $params['method'] ) ) {
			$params = [
				'method' => 'replace',
				'value'  => $params,
			];
		}

		$term_ids = array_unique( array_filter( (array) $params['value'] ) );

		switch ( $params['method'] ) {
			case 'add':
				$this->storage->update( $id, $this->extend_value( $id, $term_ids ) );
				break;
			case 'remove':
				$this->storage->update( $id, $this->reduce_value( $id, $term_ids ) );
				break;
			case 'replace':
			default:
				$this->storage->update( $id, $term_ids );
		}
	}

	public function get_paginated_options( $search, $page, $id = null ) {
		return new Terms( $search, $page, [ $this->taxonomy ] );
	}

	/**
	 * @param int   $id
	 * @param array $terms
	 *
	 * @return array
	 */
	private function extend_value( $id, array $terms ) {
		$values = array_keys( $this->get_value( (int) $id ) );
		$new_values = array_merge( $values, $terms );

		return array_unique( $new_values );
	}

	/**
	 * @param int   $id
	 * @param array $terms
	 *
	 * @return array
	 */
	private function reduce_value( $id, $terms ) {
		$values = array_keys( $this->get_value( (int) $id ) );

		foreach ( $values as $key => $term ) {
			if ( in_array( $term, $terms ) ) {
				unset( $values[ $key ] );
			}
		}

		return $values;
	}

}