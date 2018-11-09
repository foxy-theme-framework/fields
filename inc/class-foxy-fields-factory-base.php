<?php

abstract class Foxy_Fields_Factory_Base {
	protected $data_parser;
	protected $object;
	protected $tabs;
	protected $fields;

	public function __construct( $object, $tabs, $fields ) {
		$this->data_parser = new Foxy_Fields_Data_Parser();
		$this->object = $object;
		$this->tabs = $tabs;
		$this->fields = $fields;
	}

	public function generate_class_names() {
		$classe_names = array( 'foxy-fields' );

		if ( isset( $this->object->post_type ) ) {
			$class_names[] = sprintf( '%s-fields', $this->object->post_type );
		}
		return implode( ' ', $classe_names );
	}

	public function generate_tabs_wrap() {
		$class_names = sprintf(
			'foxy-tabs-wrap foxy-fields-tabs-wrap tabs-%s',
			esc_attr( $this->tabs['style'] )
		);

		return array(
			'context' => 'foxy-fields-tab-wrap',
			'class'   => $class_names,
		);
	}

	public function generate_tab_title( $tab ) {
		$title = '';
		if ( ! empty( $tab['icon'] ) ) {
			$title .= sprintf( '<span class="foxy-icon foxy-fields-icon %s"></span> ', esc_attr( $tab['icon'] ) );
		} elseif ( ! empty( $tab['image'] ) ) {
			$title .= sprintf(
				'<span class="foxy-image-icon foxy-fields-image"><img src="%s" alt="%s"/></span> ',
				esc_attr( $tab['image'] ),
				esc_attr( $tab['title'] )
			);
		}
		$title .= sprintf( '<span class="foxy-title-text foxy-fields-title">%s</span>', esc_html( $tab['title'] ) );
		return $title;
	}

	public function generate_tab_content() {
		if ( empty( $this->tabs['fields'] ) ) {
			return;
		}
		Foxy::ui()->tag( $this->generate_tabs_wrap() );
		Foxy::ui()->tag(
			array(
				'name'    => 'ul',
				'context' => 'foxy-field-tabs',
				'class'   => 'foxy-tabs foxy-fields-tabs',
			)
		);
		foreach ( $this->tabs['fields'] as $tab ) {
			if ( empty( $tab['id'] ) ) {
				continue;
			}
			Foxy::ui()->tag(
				array(
					'name'    => 'li',
					'context' => 'foxy-fields-tab',
					'class'   => 'foxy-tab foxy-fields-tab',
				)
			);
			printf( '<a href="#foxy-%s">%s</a>', esc_attr( $tab['id'] ), $this->generate_tab_title( $tab ) ); // WPCS: XSS ok.
			echo '</li>';
		}
		echo '</ul></div>';
	}

	public function generate_fields_content() {
		if ( empty( $this->fields ) ) {
			esc_html_e( "Don't has any fields for display", 'foxy' );
			return;
		}
		foreach ($this->fields as $tab => $fields ) {
			Foxy::ui()->tag( $this->generate_fields_wrap( $tab ) );
			$this->generate_fields( $fields );
			echo '</div>';
		}
	}

	public function generate_fields( $fields ) {
		foreach ($fields as $field) {
			if ( empty( $field['id'] ) || empty( $field['type'] ) ) {
				continue;
			}
			$field_callback = apply_filters( "foxy_fields_{$field['type']}_callback", false );

			if ( ! is_callable( $field_callback ) ) {

				$field_class = sprintf(
					'Foxy_Fields_%s_Field',
					ucfirst( $field['type'] )
				);

				$filename = sprintf(
					'%1$s%2$s/class-foxy-fields-%2$s-field.php',
					FOXY_FIELDS_INC_DIR,
					strtolower( $field['type'] )
				);

				if ( ! class_exists( $field_class ) ) {
					if ( file_exists( $filename ) ) {
						require_once $filename;
					} else {
						continue;
					}
					if ( ! class_exists( $field_class ) ) {
						continue;
					}
				}
				$field_callback = array( new $field_class(), 'output' );
			}
			$this->generate_field( $field, $field_callback );
		}

	}

	public function generate_fields_wrap( $tab_id ) {
		$class_names = array( 'foxy-fields-wrap' );
		if ( 'fxng' !== $tab_id ) {
			$class_name[] = 'has-tab';
			$class_name[] = sprintf( 'tab-%s', esc_attr( $tab_id ) );
		} else {
			$class_name[] = 'no-tab';
		}
		return array(
			'context' => 'foxy-fields-wrap',
			'id'      => esc_attr( 'foxy-' . $tab_id ),
			'class'   => implode( ' ', $class_names ),
		);
	}

	abstract public function generate_field( $field, $callback );

	abstract public function manufacture();
}
