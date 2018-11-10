<?php

abstract class Foxy_Fields_Base_Field {
	protected $data_parser;
	protected $object;
	protected $field;

	public function __construct( $object, $field ) {
		$this->object = $object;
		$this->field = $field;

		$this->data_parser = new Foxy_Fields_Data_Parser(
			$this->object,
			$this->field
		);
	}

	public function generate_field_classes( $field ) {
		$class_names = sprintf(
			'field-type-%1$s field-%2$s',
			esc_attr( $field['type'] ),
			esc_attr( $field['id'] )
		);

		if ( ! empty( $field['title'] ) ) {
			$class_names .= ' has-title';
		}
		if ( ! empty( $field['desc'] ) ) {
			$class_names .= ' has-desc';
		}
		if ( ! empty( $field['subtitle'] ) ) {
			$class_names .= ' has-subtitle';
		}
		// Free up memory.
		unset( $field );

		return $class_names;
	}

	public function field_label() {
		$field  = $this->field;

		$tag = wp_parse_args( $field['label_width'], array(
			'context'         => 'foxy-field-label-wrap',
			'class'           => 'foxy-label-wrap foxy-fields-label-wrap',
			'mobile_columns'  => 12,
			'tablet_columns'  => 4,
			'desktop_columns' => 3,
		));
		Foxy::ui()->tag( $tag );
		Foxy::ui()->tag(
			array(
				'name'    => 'label',
				'context' => 'foxy-fields-label',
				'class'   => 'foxy-label foxy-fields-label',
			),
			array(
				'for' => sprintf( 'foxy-fields-%s', esc_attr( $field['id'] ) ),
			)
		);
		printf( '%s</label>', esc_html( $field['title'] ) );
		if ( ! empty( $field['subtitle'] ) ) {
			Foxy::ui()->tag(
				array(
					'context' => 'foxy-fields-subtitle',
					'class'   => 'foxy-subtitle foxy-fields-subtitle',
				)
			);
			printf( '%s</div>', esc_html( $field['subtitle'] ) );
		}
		echo '</div>';
	}

	public function field_desc() {
		$field = $this->field;
		if ( ! empty( $field['desc'] ) ) {
			Foxy::ui()->tag( array(
				'context' => 'foxy-fields-desc',
				'class'   => 'foxy-desc foxy-fields-desc',
			) );
			printf( '%s</div>', esc_html() );
		}
	}

	public function output() {
		$this->field_label();
		$this->field_content();
		$this->field_desc();
	}
}
