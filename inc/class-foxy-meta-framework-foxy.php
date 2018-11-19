<?php
class Foxy_Meta_Framework_Foxy extends Foxy_Meta_Framework_Base {
	protected $fields_info;

	public function __construct() {
		add_action( 'save_post', array( $this, 'save_post_metas' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

		$this->fields_info = foxy_get_partial_info( FOXY_FIELDS_INIT_FILE );
	}

	public function save_post_metas( $post_id, $post ) {
		$meta_boxes = apply_filters( 'foxy_post_metas', array() );
		$post_metas = foxy_filter_post_type_metas( $post->post_type, $meta_boxes );
		// Free up memory.
		unset( $meta_boxes );

		foreach ( $post_metas as $field ) {

			if ( empty( $field['type'] ) || empty( $field['id'] ) || 0 === strpos( $field['id'], 'tax_input[' ) ) {
				continue;
			}

			if ( isset( $_POST[ $field['id'] ] ) ) {
				update_post_meta( $post_id, $field['id'], $_POST[ $field['id'] ] );
			} elseif( ! empty($field['delete_empty'] ) ) {
				delete_post_meta( $post_id, $field['id'] );
			}
		}
	}

	public function register_scripts() {
		Foxy::asset()->register_css(
			'foxy-fields-base',
			foxy_fields_asset_url( 'css/base.css' ),
			null,
			$this->fields_info['Version']
		)->css( 'foxy-fields-base' );

		$meta_boxes = apply_filters( 'foxy_post_metas', array() );
		$post_metas = foxy_filter_post_type_metas( get_current_screen()->post_type, $meta_boxes );

		$field_types = array_unique(
			array_column( $post_metas, 'type' )
		);

		// Free up memory.
		unset( $meta_boxes, $post_metas );

		foreach ( $field_types as $field_type ) {
			$field_class = sprintf(
				'Foxy_Fields_%s_Field',
				ucfirst( $field_type )
			);

			$field_callback = apply_filters(
				"foxy_fields_{$field_type}_type_asset_callback",
				array( $field_class, 'enqueue' )
			);

			if ( ! is_callable( $field_callback ) ) {
				$filename = sprintf(
					'%1$s%2$s/class-foxy-fields-%2$s-field.php',
					FOXY_FIELDS_INC_DIR,
					strtolower( $field_type )
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

				// Free up memory.
				unset( $field_class, $filename );
			}
			if ( ! is_callable( $field_callback ) ) {
				continue;
			}

			call_user_func( $field_callback );
		}
		// Free up memory.
		unset( $field_types, $field_type, $field_callback );
	}

	public function get( $meta_key, $post_id = null, $single = true ) {
		$post_id = foxy_get_object_id( $post_id, WP_Post::class );

		return get_metadata( 'post', $post_id, $meta_key, $single );
	}

	public function metabox_callback( $post, $args ) {
		list( $tabs, $fields ) = foxy_group_all_meta_fields( $args['args'] );
		/**
		 * Create factory instance
		 */
		$factory = new Foxy_Fields_Factory_Post_Meta( $tabs, $fields, $post );

		Foxy::ui()->tag(
			array(
				'name'    => 'div',
				'context' => 'foxy-fields-metabox',
				'class'   => $factory->generate_class_names(),
			)
		);
		$factory->manufacture();
		echo '</div>';
	}
}
