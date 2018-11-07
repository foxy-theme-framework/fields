<?php

define( 'FOXY_FIELDS_INIT_FILE', __FILE__ );
$root_dir = dirname( FOXY_FIELDS_INIT_FILE );

spl_autoload_register(
	function( $class_name ) use ( $root_dir ) {
		$widget_file = sprintf( '%s/inc/class-%s.php', $root_dir, foxy_make_slug( $class_name ) );
		if ( file_exists( $widget_file ) ) {
			require_once $widget_file;
		}
	}
);
