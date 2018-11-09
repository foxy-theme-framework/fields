<?php

class Foxy_Fields_Factory_Post_Meta extends Foxy_Fields_Factory_Base {
	public function generate_field( $field, $callback ) {
		return call_user_func( $callback, $field );
	}

	public function manufacture() {
		$this->generate_tab_content();
		$this->generate_fields_content();
	}
}
