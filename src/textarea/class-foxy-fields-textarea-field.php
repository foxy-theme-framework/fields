<?php

class Foxy_Fields_Textarea_Field extends Foxy_Fields_Base_Field {
	public function field_content() {
		$value = $this->data_parser->value();
		$id    = $this->field['id'];
		?>
		<textarea type="text" class="foxy-field" name="<?php echo esc_attr( $id ); ?>"><?php echo esc_attr( $value ); ?></textarea>
		<?php
	}
}
