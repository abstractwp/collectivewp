<?php

namespace ACA\GravityForms\Editing\Strategy;

use ACA\GravityForms;
use ACP;
use GFCommon;

class Entry implements ACP\Editing\Strategy {

	public function user_has_write_permission( $object_id ) {
		return GFCommon::current_user_can_any( GravityForms\Capabilities::EDIT_ENTRIES );
	}

}