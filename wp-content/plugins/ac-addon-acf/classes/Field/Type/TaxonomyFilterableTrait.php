<?php

namespace ACA\ACF\Field\Type;

trait TaxonomyFilterableTrait {

	public function get_taxonomies() {
		return ! empty( $this->settings['taxonomy'] )
			? (array) $this->settings['taxonomy']
			: [];
	}

}