<?php
/**
 * Partial Name: Foxy Fields API
 * Partial URI: https://wpclouds.com
 * Author: WPClouds
 * Version: 1.0
 * Description: Use fields for render metabox, setting api, etc
 *
 * @package Foxy/Fields
 * @author Puleeno Nguyen <puleeno@gmail.com>
 * @license GPLv3
 * @link https://wpclouds.com
 */

function foxy_fields_define_constants() {
	define( 'FOXY_FIELDS_INIT_FILE', __FILE__ );
	define( 'FOXY_FIELDS_ROOT_DIR', dirname( FOXY_FIELDS_INIT_FILE ) . '/' );
	define( 'FOXY_FIELDS_INC_DIR', FOXY_FIELDS_ROOT_DIR . 'inc/' );
	define( 'FOXY_FIELDS_ASSETS_URL', str_replace( ABSPATH, '', FOXY_FIELDS_ROOT_DIR ) . 'assets/' );
}

foxy_fields_define_constants();

spl_autoload_register(
	function( $class_name ) {
		$widget_file = sprintf(
			'%s/inc/class-%s.php', FOXY_FIELDS_ROOT_DIR, preg_replace(
				'/_/',
				'-',
				sanitize_title( $class_name )
			)
		);
		if ( file_exists( $widget_file ) ) {
			require_once $widget_file;
		}
	}
);


function foxy_fields_asset_url( $path = null ) {
	return site_url( FOXY_FIELDS_ASSETS_URL . $path );
}
