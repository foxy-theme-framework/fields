<?php

class Foxy_Fields_Meta {
	protected $fields = array();

	public static function load_metas() {
		$meta_boxes = apply_filters( 'foxy_post_metas', array() );
	}

	public function enqueue_scripts() {
	}

	public static function get_tabs( $post_title = null ) {
	}

	public static function get_fields( $post_type = null ) {
	}
}
