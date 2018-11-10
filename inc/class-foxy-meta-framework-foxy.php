<?php
class Foxy_Meta_Framework_Foxy extends Foxy_Meta_Framework_Base {
	protected $fields_info;

	public function __construct() {
		add_action( 'save_post', array( $this, 'save_post_metas' ), 10 ,2 );

		$this->fields_info = foxy_get_partial_info( FOXY_FIELDS_INIT_FILE );
	}

	public function save_post_metas( $post_id, $post ) {
		$meta_boxes = apply_filters( 'foxy_post_metas', array() );
		$post_metas = $this->filter_post_type_metas( $post, $meta_boxes );

		// Free up memory.
		unset( $meta_boxes );

		foreach ( $post_metas as $field ) {
			if ( empty( $field['type'] ) || empty( $field['id'] ) ) {
				continue;
			}
			if ( isset( $_POST[ $field['id'] ] ) ) {
				update_post_meta( $post_id, $field['id'], $_POST[ $field['id'] ] );
			} else {
				delete_post_meta( $post_id, $field['id'] );
			}
		}
	}

	public function get( $meta_key, $post_id = null, $single = true ) {
		$post_id = foxy_get_object_id( $post_id, WP_Post::class );

		return get_metadata( 'post', $post_id, $meta_key, $single );
	}

	public function metabox_callback( $post, $args ) {
		Foxy::asset()->register_css(
			'foxy-fields-base',
			foxy_fields_asset_url( 'css/base.css' ),
			null,
			$this->fields_info['Version']
		)->css( 'foxy-fields-base' );

		list( $tabs, $fields ) = $this->group_all_fields( $args['args'] );
		/**
		 * Create factory instance
		 */
		$factory = new Foxy_Fields_Factory_Post_Meta( $post, $tabs, $fields );

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
