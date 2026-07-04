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
 * Echo a scattered-triangle decoration SVG (animated by tri-field.js).
 * Place inside a position:relative / overflow:hidden section.
 *
 * @param string $variant tl | tr | bl | br (corner placement).
 * @param int    $count   number of triangles.
 * @param int    $seed    PRNG seed (0 = derive from variant for stability).
 */
function fde_tri_field( string $variant = 'br', int $count = 10, int $seed = 0 ): void {
	$allowed = [ 'tl', 'tr', 'bl', 'br' ];
	if ( ! in_array( $variant, $allowed, true ) ) {
		$variant = 'br';
	}
	if ( 0 === $seed ) {
		$seed = abs( crc32( $variant . '|' . $count ) ) % 1000000;
	}
	printf(
		'<svg class="tri-field tri-field--%1$s" viewBox="0 0 120 120" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false" data-tri-field data-tri-count="%2$d" data-tri-seed="%3$d"><g class="tri-field__g" stroke="currentColor" fill="currentColor"></g></svg>',
		esc_attr( $variant ),
		(int) $count,
		(int) $seed
	);
}

/**
 * Shared main navigation items — used by header, mobile panel, and footer.
 *
 * @return array<int,array{label:string,url:string}>
 */
function fde_main_nav_items(): array {
	$home = home_url( '/' );
	return [
		[ 'label' => 'Concept', 'url' => $home . '#concept' ],
		[ 'label' => 'Service', 'url' => $home . '#services' ],
		[ 'label' => 'About',   'url' => $home . '#about' ],
		[ 'label' => 'Blog',    'url' => $home . '#blog' ],
	];
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

/**
 * Split a textarea string into line items.
 * Strips leading bullet markers (-、・、*、•) and ignores blank lines.
 *
 * @return array<int,string>
 */
function fde_split_lines( string $text ): array {
	$lines = preg_split( '/\r\n|\r|\n/u', $text );
	if ( ! is_array( $lines ) ) {
		return [];
	}
	$out = [];
	foreach ( $lines as $line ) {
		// 行頭の bullet マーカーと余白を除去。
		// ltrim() の mask はバイト単位なので、マルチバイトを含む場合は
		// 0xE3 のような lead byte が日本語の先頭文字を破壊する。
		// /u フラグの preg_replace なら codepoint 単位で安全に剥がせる。
		$line = preg_replace( '/^[\s\-*•・　]+|\s+$/u', '', $line );
		if ( null === $line || '' === $line ) {
			continue;
		}
		$out[] = $line;
	}
	return $out;
}
