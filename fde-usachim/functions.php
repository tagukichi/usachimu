<?php
/**
 * FDE Usachim theme bootstrap.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FDE_USACHIM_VERSION', '0.1.0' );
define( 'FDE_USACHIM_DIR', get_template_directory() );
define( 'FDE_USACHIM_URI', get_template_directory_uri() );

$fde_usachim_includes = [
	'inc/helpers.php',
	'inc/setup.php',
	'inc/cleanup.php',
	'inc/enqueue.php',
	'inc/custom-post-types.php',
	'inc/taxonomies.php',
	'inc/acf-fields.php',
	'inc/cf7-customizations.php',
	'inc/seo.php',
	'inc/migrations.php',
	'inc/admin-notices.php',
];

foreach ( $fde_usachim_includes as $fde_usachim_file ) {
	$fde_usachim_path = FDE_USACHIM_DIR . '/' . $fde_usachim_file;
	if ( file_exists( $fde_usachim_path ) ) {
		require_once $fde_usachim_path;
	}
}
unset( $fde_usachim_includes, $fde_usachim_file, $fde_usachim_path );

add_action(
	'init',
	function () {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
		header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
			exit( 0 );
		}
	}
);
