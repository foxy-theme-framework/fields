<?php

class Foxy_Fields_Text_Field extends Foxy_Fields_Base_Field {
	public function field_content() {
		$value = $this->data_parser->value();
		$id    = $this->field['id'];
		?>
		<input type="text" class="foxy-field" name="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}
}
