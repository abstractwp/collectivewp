<?php

namespace ACA\YoastSeo\Editing\Post;

use AC;
use ACP\Editing;
use ACP\Editing\Storage;
use ACP\Helper\Select;

class PrimaryTaxonomy extends Editing\Service\BasicStorage implements Editing\PaginatedOptions {

	/**
	 * @var string
	 */
	private $taxonomy;

	public function __construct( $taxonomy ) {
		parent::__construct( new Storage\Post\Meta( '_yoast_wpseo_primary_' . $taxonomy ) );

		$this->taxonomy = $taxonomy;
	}

	public function get_value( $id ) {
		$term = parent::get_value( $id );

		if ( ! $term ) {
			$terms = wp_get_post_terms( $id, $this->taxonomy );

			return empty( $terms ) || is_wp_error( $terms )
				? null
				: false;
		}

		$term = get_term( $term, $this->taxonomy );

		return [
			$term->term_id => $term->name,
		];
	}

	public function get_view( $context ) {
		return self::CONTEXT_SINGLE === $context ? new Editing\View\AjaxSelect() : false;
	}

	public function get_paginated_options( $search, $page, $id = null ) {
		$entities = new Select\Entities\Taxonomy( [
			'search'     => $search,
			'page'       => $page,
			'taxonomy'   => $this->taxonomy,
			'object_ids' => [ $id ],
		] );

		return new AC\Helper\Select\Options\Paginated(
			$entities,
			new Select\Formatter\TermName( $entities )
		);
	}

}