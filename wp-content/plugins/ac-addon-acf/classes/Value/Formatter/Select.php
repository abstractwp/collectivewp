<?php

namespace ACA\ACF\Value\Formatter;

use AC\Settings\Column\NumberOfItems;
use ACA\ACF\Field\Choices;
use ACA\ACF\Value\Formatter;

class Select extends Formatter {

	public function format( $value, $id = null ) {
		$labels = $this->field instanceof Choices
			? $this->field->get_choices()
			: [];

		if ( empty( $value ) ) {
			return $this->column->get_empty_char();
		}

		$result = [];
		foreach ( (array) $value as $v ) {
			$result[] = isset( $labels[ $v ] )
				? $labels[ $v ]
				: $v;
		}

		if ( $this->column->get_setting( NumberOfItems::NAME ) ) {
			return ac_helper()->html->more(
				$result,
				$this->column->get_setting( NumberOfItems::NAME )->get_value(),
				$this->column->get_separator()
			);
		}

		return implode( ', ', $result );
	}

}