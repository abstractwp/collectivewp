<?php

namespace ACA\Pods\Editing\Service;

use AC;
use ACP;
use ACP\Editing\PaginatedOptions;
use ACP\Editing\Storage;
use ACP\Editing\View;
use ACP\Helper\Select\Entities;

class PickComments implements ACP\Editing\Service, PaginatedOptions {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @var boolean
	 */
	private $multiple;

	public function __construct( Storage $storage, $multiple ) {
		$this->storage = $storage;
		$this->multiple = (bool) $multiple;
	}

	public function get_view( string $context ): ?View {
		return ( new ACP\Editing\View\AjaxSelect() )
			->set_multiple( $this->multiple )
			->set_clear_button( true );
	}

	public function get_value( $id ) {
		$comment_ids = $this->storage->get( $id );

		if ( empty( $comment_ids ) ) {
			return false;
		}

		$value = [];
		foreach ( $comment_ids as $comment_id ) {
			$comment = get_comment( $comment_id );
			$value[ $comment_id ] = $comment->comment_date ?? $comment_id;
		}

		return $value;
	}

	public function update( int $id, $data ): void {
		$this->storage->update( $id, $data );
	}

	public function get_paginated_options( $search, $paged, $id = null ) {
		$entities = new Entities\Comment( compact( 'search', 'paged' ) );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new ACP\Helper\Select\Formatter\CommentSummary( $entities )
		);
	}

}