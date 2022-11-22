<?php

namespace ACA\YoastSeo\Column\Post;

use AC;
use ACP;

class Linked extends AC\Column
	implements ACP\Export\Exportable {

	public function __construct() {
		$this->set_type( 'wpseo-linked' )
		     ->set_group( 'yoast-seo' )
		     ->set_original( true );
	}

	public function export() {
		return new ACP\Export\Model\Disabled( $this );
	}

}