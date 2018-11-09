<?php

class Foxy_Field_Factory_Post_Meta extends Foxy_Field_Factory_Base {
	public function manufacture() {
		$this->generate_tab_content();
		$this->generate_fields_content();
	}
}
