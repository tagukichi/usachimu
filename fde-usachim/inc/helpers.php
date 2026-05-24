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
 * @param mixed $default Fallback when ACF is unavailable or value is empty.
 * @return mixed
 */
function fde_option( string $key, $default = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $key, 'option' );
		if ( null !== $value && '' !== $value && [] !== $value ) {
			return $value;
		}
	}
	return $default;
}

/**
 * Fetch an ACF field from the page set as the front page (設定 → 表示設定).
 * Falls back to $default when ACF is unavailable, the option is unset,
 * or the field is empty. Use for editing TOP page copy via the page editor.
 *
 * @param mixed $default Fallback when no value is found.
 * @return mixed
 */
function fde_field( string $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$page_id = (int) get_option( 'page_on_front' );
	if ( $page_id <= 0 ) {
		return $default;
	}
	$value = get_field( $key, $page_id );
	if ( null !== $value && '' !== $value && [] !== $value ) {
		return $value;
	}
	return $default;
}

/**
 * Convert author-friendly **bold** markers to <b> tags, escaping the rest.
 * Allows simple <br> for explicit line breaks.
 */
function fde_inline_text( string $text ): string {
	$escaped = esc_html( $text );
	$escaped = preg_replace( '/\*\*(.+?)\*\*/u', '<b>$1</b>', $escaped );
	return $escaped;
}

/**
 * Render a paragraph block: split on blank lines, escape, apply **bold**, join with <p>.
 */
function fde_paragraphs( string $text ): string {
	$text = str_replace( "\r\n", "\n", $text );
	$paragraphs = preg_split( '/\n{2,}/u', trim( $text ) );
	$out = '';
	foreach ( $paragraphs as $p ) {
		if ( '' === trim( $p ) ) {
			continue;
		}
		$line = fde_inline_text( $p );
		$line = nl2br( $line );
		$out .= '<p>' . $line . '</p>';
	}
	return $out;
}

/**
 * Split a comma (or 、) separated string into trimmed items.
 *
 * @return array<int,string>
 */
function fde_split_tags( string $text ): array {
	$parts = preg_split( '/[,、]/u', $text );
	if ( ! is_array( $parts ) ) {
		return [];
	}
	$out = [];
	foreach ( $parts as $p ) {
		$p = trim( $p );
		if ( '' !== $p ) {
			$out[] = $p;
		}
	}
	return $out;
}
