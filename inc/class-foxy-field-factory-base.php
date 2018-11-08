<?php

abstract class Foxy_Field_Factory_Base {
	protected $data_parser;
	protected $object;
	protected $tabs;
	protected $fields;

	public function __construct( $object, $tabs, $fields ) {
		$this->data_parser = new Foxy_Field_Data_Parser();
		$this->object = $object;
		$this->tabs = $tabs;
		$this->fields = $fields;

	}

	public function generate_tab_title() {

	}

	public function generate_class_names() {

	}

	public function generate_field_label() {
	}

	public function generate_tab_content() {

	}

	public function generate_fields_content() {

	}

	abstract public function manufacture();
}
