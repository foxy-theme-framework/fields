<?php

class Foxy_Fields_Select_Field extends Foxy_Fields_Base_Field {
	public function field_content() {
		?>
		<?php if($this->is_tax): ?>
		<input type="hidden" name="<?php echo esc_attr( $this->field['id'] ); ?>" value="0">
		<?php endif; ?>

		<select name="<?php echo $this->field['id']; ?>" id="">
			<?php if(!empty($this->field['dont_value'])): ?><option value=""><?php echo $this->field['dont_value']; ?></option><?php endif; ?>
			<?php foreach($this->field['options'] as $option => $label): ?>
			<option value="<?php echo esc_attr( $option ); ?>"<?php selected(true, $this->choosed( $option )); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}
