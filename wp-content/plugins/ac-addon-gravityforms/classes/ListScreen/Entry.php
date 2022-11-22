<?php

namespace ACA\GravityForms\ListScreen;

use AC;
use ACA\GravityForms;
use ACA\GravityForms\Column\EntryConfigurator;
use ACP\Editing;
use ACP\Export;
use GF_Entry_List_Table;
use GFAPI;
use GFForms;
use GFFormsModel;

class Entry extends AC\ListScreenWP implements Editing\ListScreen, Export\ListScreen {

	/**
	 * @var int $form_id
	 */
	private $form_id;

	/**
	 * @var EntryConfigurator
	 */
	private $column_configurator;

	public function __construct( $form_id, EntryConfigurator $column_configurator ) {
		$this->form_id = (int) $form_id;
		$this->column_configurator = $column_configurator;

		$this->set_group( GravityForms\GravityForms::GROUP )
		     ->set_page( 'gf_entries' )
		     ->set_screen_id( '_page_gf_entries' )
		     ->set_screen_base( '_page_gf_entries' )
		     ->set_key( 'gf_entry_' . $form_id )
		     ->set_meta_type( GravityForms\MetaTypes::GRAVITY_FORMS_ENTRY );
	}

	public function editing() {
		return new GravityForms\Editing\Strategy\Entry();
	}

	public function export() {
		return new GravityForms\Export\Strategy\Entry( $this );
	}

	public function get_heading_hookname() {
		return 'gform_entry_list_columns';
	}

	protected function get_object( $id ) {
		return GFAPI::get_entry( $id );
	}

	public function set_manage_value_callback() {
		add_filter( 'gform_entries_field_value', [ $this, 'manage_value_entry' ], 10, 4 );
	}

	/**
	 * @param string $original_value
	 * @param int    $form_id
	 * @param string $field_id
	 * @param array  $entry
	 *
	 * @return string
	 */
	public function manage_value_entry( $original_value, $form_id, $field_id, $entry ) {
		$custom_column_value = $this->get_display_value_by_column_name( $field_id, $entry['id'], $original_value );

		if ( $custom_column_value ) {
			return $custom_column_value;
		}

		$value = $this->get_display_value_by_column_name( 'field_id-' . $field_id, $entry['id'], $original_value );

		return $value ?: $original_value;
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return GFAPI::get_form( $this->get_form_id() )['title'];
	}

	/**
	 * @return int
	 */
	public function get_form_id() {
		return $this->form_id;
	}

	/**
	 * @return bool
	 */
	public function is_current_screen( $wp_screen ) {
		return
			strpos( $wp_screen->id, '_page_gf_entries' ) !== false &&
			strpos( $wp_screen->base, '_page_gf_entries' ) !== false &&
			$this->get_current_form_id() === $this->form_id;
	}

	/**
	 * @return int
	 */
	private function get_current_form_id() {
		$form_id = GFForms::get( 'id' );

		if ( ! $form_id ) {
			$forms = GFFormsModel::get_forms();

			if ( $forms ) {
				$form_id = $forms[0]->id;
			}
		}

		return (int) $form_id;
	}

	/**
	 * @return string
	 */
	protected function get_admin_url() {
		return admin_url( 'admin.php' );
	}

	/**
	 * @return string
	 */
	public function get_screen_link() {
		return add_query_arg( [ 'id' => $this->get_form_id() ], parent::get_screen_link() );
	}

	/**
	 * @return GF_Entry_List_Table
	 */
	public function get_list_table() {
		return ( new GravityForms\TableFactory() )->create( $this->get_screen_id(), $this->form_id );
	}

	/**
	 * @throws \ReflectionException
	 */
	public function register_column_types() {
		$this->column_configurator->register_entry_columns( $this );

		$this->register_column_types_from_dir( 'ACA\GravityForms\Column\Entry\Original' );
		$this->register_column_types_from_dir( 'ACA\GravityForms\Column\Entry\Custom' );
	}

}