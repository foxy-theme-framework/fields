<?php
class Foxy_Meta_Framework_Foxy extends Foxy_Meta_Framework_Base {
	protected $fields_info;

	public function __construct() {
		parent::__construct();

		$this->fields_info = foxy_get_partial_info( FOXY_FIELDS_INIT_FILE );
		Foxy::asset()->register_css(
			'foxy-field-base',
			foxy_fields_asset_url( 'css/base.css' ),
			null,
			$this->fields_info['Version']
		);
	}
	public function get( $meta_key, $post_id = null, $single = true ) {
	}

	public function metabox_callback( $post, $args ) {
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
