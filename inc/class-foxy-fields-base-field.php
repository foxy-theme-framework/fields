<?php

abstract class Foxy_Fields_Base_Field {
	public function create_label( $field ) {
		$tag = wp_parse_args( $field['label_width'], array(
			'echo' => false,
		));
		$label = Foxy::ui()->tag($tag);
			$label .= sprintf(
				'<label for="foxy-field-%s" class="foxy-field-label">%s</label>',
				$field['id'],
				$field['title']
			);

			if ( ! empty( $field['subtitle'] ) ) {
				$label .= sprintf(
					'<div class="foxy-field-subtitle">%s</div>',
					$field['subtitle']
				);
			}
		$label .= '</div>';

		return $label;
	}

	public function create_desc( $field ) {
		if ( ! empty( $field['desc'] ) ) :
		?>
		<div class="field-desc">
			<?php echo esc_html( $field['desc'] ); ?>
		</div>
		<?php
		endif;
	}

	abstract public function output( $field );
}
