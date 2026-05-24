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
	'rest_api_init',
	function () {
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );

		add_filter(
			'rest_pre_serve_request',
			function ( $value ) {
				// CORS を許可するオリジンのホワイトリスト。
				// 必要に応じてフロント（本番 / ステージング）の URL を追加してください。
				$allowed = [
					'https://usachim.com',
				];

				$origin = function_exists( 'get_http_origin' ) ? get_http_origin() : '';
				if ( $origin && in_array( $origin, $allowed, true ) ) {
					header( 'Access-Control-Allow-Origin: ' . $origin );
					header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS' );
					header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
					header( 'Access-Control-Allow-Credentials: true' );
					header( 'Vary: Origin' );
				}

				if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
					status_header( 200 );
					exit;
				}

				return $value;
			}
		);
	},
	15
);
