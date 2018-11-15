<?php

abstract class Foxy_Fields_Factory_Base {
	protected $object;
	protected $tabs;
	protected $fields;

	/**
	 * Foxy_Fields_Factory_Base constructor
	 *
	 * @param WP_Post|WP_Term|WP_User $object Set object need to add meta data.
	 * @param array                   $tabs   Add meta data with tabnav if $tabs have values.
	 * @param array                   $fields List fields will to be added to metabox.
	 */
	public function __construct( $tabs, $fields, $object = null ) {
		/**
		 * Set value current object to factory
		 */
		$this->object = $object;

		/**
		 * Parse tabs with default values
		 */
		$this->tabs = wp_parse_args(
			$tabs, array(
				'style'  => 'horizontal',
				'icon'   => '',
				'image'  => '',
				'fields' => array(),
			)
		);

		/**
		 * Add fields to factory
		 */
		$this->fields = (array) $fields;
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

		// Free up memory.
		unset( $tab );

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

		// Free up memory.
		unset( $tab );
	}

	public function generate_fields_content() {
		if ( empty( $this->fields ) ) {
			esc_html_e( "Don't has any fields for display", 'foxy' );
			return;
		}
		foreach ( $this->fields as $tab => $fields ) {
			Foxy::ui()->tag( $this->generate_fields_wrap( $tab ) );
			$this->generate_fields( $fields );
			echo '</div>';
		}

		// Free up memory.
		unset( $tab, $fields );
	}

	public function generate_fields( $fields ) {
		foreach ( $fields as $field ) {
			if ( empty( $field['id'] ) || empty( $field['type'] ) ) {
				continue;
			}
			$field_callback = apply_filters( "foxy_fields_{$field['type']}_callback", false );

			if ( ! is_callable( $field_callback ) ) {
				$field_class = sprintf(
					'Foxy_Fields_%s_Field',
					ucfirst( $field['type'] )
				);
				if ( ! class_exists( $field_class ) ) {
					continue;
				}
				$field_callback = array( new $field_class( $this->object, $field ), 'output' );

				// Free up memory.
				unset( $field_class );
			}

			$this->generate_field( $field, $field_callback );
		}

		// Free up memory.
		unset( $fields, $field, $field_callback );
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
