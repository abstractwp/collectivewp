<?php

namespace ACA\ACF\Search\Comparison;

use ACP;

class Users extends User {

	protected function get_meta_query( $operator, ACP\Search\Value $value ) {
		$comparison = ACP\Search\Helper\MetaQuery\SerializedComparisonFactory::create(
			$this->get_meta_key(),
			$operator,
			$value
		);

		return $comparison();
	}

}