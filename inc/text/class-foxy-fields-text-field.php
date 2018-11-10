<?php

class Foxy_Fields_Text_Field extends Foxy_Fields_Base_Field {
	public function text( $id ) {
		$value = $this->data_parser->value( $id );
		?>
		<input type="text" class="foxy-field" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}

	public function field_content() {
		$field = wp_parse_args(
			$this->field,
			array(
				'content_width' => array(
				)
			)
		);
		Foxy::ui()->tag(
			array(
				'context' => 'foxy-fields-content',
				'class'   => sprintf(
					'foxy-content foxy-fields-content %s-type-content %s-content',
					esc_attr( $field['type'] ),
					esc_attr( $field['id'] )
				),
			)
		);
		$this->text( $field['id'] );
		echo '</div>';
	}
}
