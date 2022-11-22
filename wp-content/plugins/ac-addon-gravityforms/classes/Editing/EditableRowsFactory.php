<?php

namespace ACA\GravityForms\Editing;

use AC;
use ACA\GravityForms\Editing\EditableRows\Entry;
use ACA\GravityForms\ListScreen;
use ACP\Editing\Ajax\EditableRowsFactoryInterface;

class EditableRowsFactory implements EditableRowsFactoryInterface {

	public static function create( AC\Request $request, AC\ListScreen $list_screen ) {
		return $list_screen instanceof ListScreen\Entry
			? new Entry( $request, $list_screen->editing(), $list_screen->get_list_table() )
			: null;
	}
}