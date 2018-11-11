<?php

class Foxy_Fields_Radio_Field extends Foxy_Fields_Base_Field {
	public function field_content() {
		?>
		<?php if($this->is_tax): ?>
			<input type="hidden" name="<?php echo esc_attr( $this->field['id'] ); ?>" value="0">
		<?php endif; ?>
		<?php foreach ( $this->field['options'] as $option => $label ): ?>
			<input type="radio" name="<?php echo $this->field['id']; ?>" value="<?php echo esc_attr( $option ) ?>"<?php checked(true, $this->choosed( $option )); ?>> <?php echo esc_html( $label ); ?>
		<?php endforeach; ?>
		<?php
	}
}
