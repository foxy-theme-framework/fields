<?php

class Foxy_Fields_Factory_Post_Meta extends Foxy_Fields_Factory_Base {
	/**
	 * Generate field content via callback
	 *
	 * @param array $field Field args.
	 * @param string|array $callback Callback function.
	 * @return string
	 */
	public function generate_field( $field, $callback ) {
		$class_names = 'foxy-field foxy-fields-field fx-row';
		if ( is_array( $callback ) && $callback[0] instanceof Foxy_Fields_Base_Field ) {
			$class_names .= ' ' . $callback[0]->generate_field_classes( $field );
		} else {
			$class_names .= ' custom-callback';
		}

		Foxy::ui()->tag(
			array(
				'context' => 'foxy-fields-field',
				'class'   => $class_names,
			)
		);
		call_user_func( $callback, $field, $this->object );
		echo '</div>';

		// Free up memory.
		unset( $field, $callback );
	}

	public function manufacture() {
		$this->generate_tab_content();
		$this->generate_fields_content();
	}
}
