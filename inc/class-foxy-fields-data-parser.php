<?php
class Foxy_Fields_Data_Parser {
	protected $object;
	protected $field;

	public function __construct( $object, $field ) {
		$this->object = $object;
		$this->field = $field;
	}

	public function value() {
		return Foxy::meta()->get( $this->field['id'], $this->object, true );
	}
}
