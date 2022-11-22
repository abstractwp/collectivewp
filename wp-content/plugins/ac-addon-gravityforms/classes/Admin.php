<?php

namespace ACA\GravityForms;

use AC;
use ACA\GravityForms\HideOnScreen\EntryFilters;
use ACA\GravityForms\HideOnScreen\WordPressNotifications;
use ACA\GravityForms\ListScreen\Entry;
use ACP;

final class Admin implements AC\Registrable {

	public function register() {
		add_action( 'acp/admin/settings/hide_on_screen', [ $this, 'add_hide_on_screen' ], 10, 2 );
	}

	public function add_hide_on_screen( ACP\Settings\ListScreen\HideOnScreenCollection $collection, AC\ListScreen $list_screen ) {
		if ( $list_screen instanceof Entry ) {
			$collection->remove( new ACP\Settings\ListScreen\HideOnScreen\Search() );
			$collection->add( new EntryFilters() );
			$collection->add( new WordPressNotifications() );
		}

	}

}