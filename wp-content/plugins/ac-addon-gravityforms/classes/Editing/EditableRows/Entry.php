<?php

namespace ACA\GravityForms\Editing\EditableRows;

use AC;
use ACA\GravityForms\Utils\Hooks;
use ACP\Editing\Ajax\EditableRows;
use ACP\Editing\Strategy;
use GF_Entry_List_Table;

class Entry extends EditableRows {

	/**
	 * @var GF_Entry_List_Table
	 */
	private $list_table;

	public function __construct( AC\Request $request, Strategy $strategy, GF_Entry_List_Table $list_table ) {
		parent::__construct( $request, $strategy );

		$this->list_table = $list_table;
	}

	public function register() {
		add_filter( 'gform_get_entries_args_entry_list', [ $this, 'set_query_vars' ] );
		add_action( Hooks::get_load_form_entries(), [ $this, 'send' ] );
	}

	public function send() {
		$this->list_table->prepare_items();

		$this->success( wp_list_pluck( $this->list_table->items, 'id' ) );
	}

	public function set_query_vars( $args ) {
		$this->check_nonce();

		$per_page = $this->get_editable_rows_per_iteration();

		$args['paging'] = [
			'offset'    => $this->get_offset(),
			'page_size' => $per_page,
		];

		return $args;
	}
}