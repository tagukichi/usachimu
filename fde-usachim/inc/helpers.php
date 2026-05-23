<?php
/**
 * Helper functions.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve a theme asset URL with version-busting mtime.
 */
function fde_asset_url( string $relative_path ): string {
	$relative_path = ltrim( $relative_path, '/' );
	$full_path     = FDE_USACHIM_DIR . '/' . $relative_path;
	$version       = file_exists( $full_path ) ? (string) filemtime( $full_path ) : FDE_USACHIM_VERSION;

	return add_query_arg( 'v', $version, FDE_USACHIM_URI . '/' . $relative_path );
}

/**
 * Echo an SVG icon from assets/icons (Lucide etc.) inline.
 */
function fde_icon( string $name, array $attrs = [] ): void {
	$path = FDE_USACHIM_DIR . '/assets/icons/' . $name . '.svg';
	if ( ! file_exists( $path ) ) {
		return;
	}
	$svg = file_get_contents( $path );
	if ( false === $svg ) {
		return;
	}
	if ( ! empty( $attrs ) ) {
		$attr_string = '';
		foreach ( $attrs as $key => $value ) {
			$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
		}
		$svg = preg_replace( '/<svg\b/', '<svg' . $attr_string, $svg, 1 );
	}
	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Fetch a theme option from the ACF options page with a fallback.
 *
 * @param mixed $default Fallback when ACF is unavailable.
 * @return mixed
 */
function fde_option( string $key, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, 'option' );
		if ( null !== $value && '' !== $value ) {
			return $value;
		}
	}
	return $default;
}
