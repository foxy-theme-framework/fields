<?php
class Foxy_Fields_Data_Parser {
	protected $object;
	protected $field;

	public function __construct( $object, $field ) {
		$this->object = $object;
		$this->field  = $field;
	}

	public function value() {
		return Foxy::meta()->get( $this->field['id'], $this->object, true );
	}

	public function get_options() {
		list( $type, $target ) = explode( '@', $this->field['data'] );
		switch ( $type ) {
			case 'taxonomy':
				return foxy_get_terms(
					array(
						'taxonomy'   => $target,
						'hide_empty' => false,
						'fields'     => 'id=>name',
					)
				);
			case 'page':
				$target = 'page';
				goto post_type;
			case 'post':
				$target = 'post';
				goto post_type;
			case 'post_type':
				post_type:
				return $this->get_posts( $target );
		}
	}

	private function get_posts( $post_type, $args = array() ) {
		global $wpdb;
		$args  = wp_parse_args(
			$args, array(
				'post_status' => 'publish',
			)
		);
		$query = "SELECT {$wpdb->posts}.ID, {$wpdb->posts}.post_title FROM {$wpdb->posts} WHERE post_status";
		if ( is_array( $args['post_status'] ) ) {
			$query .= sprintf( ' IN (%s)', implode( ',', $args['post_status'] ) );
		} elseif ( is_string( $args['post_status'] ) ) {
			$query .= "='{$args['post_status']}'";
		} else {
			$query .= "='publish'";
		}
		$query .= " AND post_type='{$post_type}'";
		$data   = $wpdb->get_results( $query, 'ARRAY_A' );

		return array_column( $data, 'post_title', 'ID' );
	}
}
