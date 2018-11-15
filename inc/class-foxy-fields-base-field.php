<?php

abstract class Foxy_Fields_Base_Field {
	protected $data_parser;
	protected $object;
	protected $field;
	protected $is_tax;

	public function __construct( $object, $field ) {
		$this->object = $object;
		$this->field  = wp_parse_args(
			$field, array(
				'title'      => '',
				'subtitle'   => '',
				'desc'       => '',
				'default'    => '',
				'value'      => '',
				'options'    => '',
				'data'       => '',
				'dont_value' => '',
			)
		);

		$this->data_parser = new Foxy_Fields_Data_Parser(
			$this->object,
			$this->field
		);

		if ( ! empty( $this->field['data'] ) ) {
			$this->field['options'] = $this->data_parser->get_options();
		}

		$this->is_tax = ( 0 === strpos( $this->field['id'], 'tax_input[' ) );
	}

	public static function enqueue() {
	}

	public function choosed( $current ) {
		$choosed = false;
		if ( empty( $this->field['data'] ) ) {
			$choosed = $this->data_parser->value();
		} else {
			list($type, $target) = explode( '@', $this->field['data'] );
			switch ( $type ) {
				case 'post_tag':
					$target = 'post_tag';
					goto taxonomy;
				case 'category':
					$target = 'category';
					goto taxonomy;
				case 'taxonomy':
					taxonomy:
					$choosed = wp_get_post_terms(
						$this->object->ID, $target, array(
							'orderby' => 'name',
							'order'   => 'ASC',
							'fields'  => 'ids',
						)
					);
					break;
				default:
					$choosed = $this->data_parser->value();
					break;
			}
		}
		if ( is_array( $choosed ) ) {
			return in_array( $current, $choosed, true );
		} else {
			return $choosed == $current;
		}
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
		$tag = wp_parse_args(
			$this->field, array(
				'context'         => 'foxy-field-label-wrap',
				'class'           => 'foxy-label-wrap foxy-fields-label-wrap',
				'mobile_columns'  => 12,
				'tablet_columns'  => 4,
				'desktop_columns' => 3,
			)
		);
		Foxy::ui()->tag( $tag );
		Foxy::ui()->tag(
			array(
				'name'    => 'label',
				'context' => 'foxy-fields-label',
				'class'   => 'foxy-label foxy-fields-label',
			),
			array(
				'for' => sprintf( 'foxy-fields-%s', esc_attr( $this->field['id'] ) ),
			)
		);
		printf( '%s</label>', esc_html( $this->field['title'] ) );
		if ( ! empty( $this->field['subtitle'] ) ) {
			Foxy::ui()->tag(
				array(
					'context' => 'foxy-fields-subtitle',
					'class'   => 'foxy-subtitle foxy-fields-subtitle',
				)
			);
			printf( '%s</div>', esc_html( $this->field['subtitle'] ) );
		}
		echo '</div>';
	}

	public function field_desc() {
		$field = $this->field;
		if ( ! empty( $field['desc'] ) ) {
			Foxy::ui()->tag(
				array(
					'context' => 'foxy-fields-desc',
					'class'   => 'foxy-desc foxy-fields-desc',
				)
			);
			printf( '%s</div>', esc_html() );
		}
	}

	public function output() {
		$this->field_label();

		Foxy::ui()->tag(
			array(
				'context'         => 'foxy-fields-content',
				'class'           => sprintf(
					'foxy-content foxy-fields-content %s-type-content %s-content',
					esc_attr( $this->field['type'] ),
					esc_attr( $this->field['id'] )
				),
				'mobile_columns'  => 12,
				'tablet_columns'  => 8,
				'desktop_columns' => 9,
			)
		);

		$this->field_content();
		$this->field_desc();

		echo '</div>';
	}
}
