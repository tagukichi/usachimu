<?php
/**
 * URL migrations from the legacy theme.
 *
 * 旧 `web` CPT の URL を新 `works` に 301 で寄せる。
 *
 * - /web/                → /works/
 * - /web/{slug}/         → /works/{slug}/（既存スラッグが新CPT側に存在する場合のみ。
 *                          無ければ /works/ にフォールバック）
 * - /web-cat/{term}/     → /works-category/{term}/
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'template_redirect',
	static function () {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return;
		}

		$request = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		if ( ! $request ) {
			return;
		}

		$path  = parse_url( $request, PHP_URL_PATH );
		$query = parse_url( $request, PHP_URL_QUERY );
		if ( ! is_string( $path ) ) {
			return;
		}

		$target = null;

		// /web/{slug}/ → /works/{slug}/ (only if slug exists under works)
		if ( preg_match( '#^/web/([^/]+)/?$#', $path, $m ) ) {
			$slug = sanitize_title( $m[1] );
			$post = get_page_by_path( $slug, OBJECT, 'works' );
			$target = $post ? get_permalink( $post ) : get_post_type_archive_link( 'works' );
		}
		// /web/ → /works/
		elseif ( preg_match( '#^/web/?$#', $path ) ) {
			$target = get_post_type_archive_link( 'works' );
		}
		// /web-cat/{term}/ → /works-category/{term}/
		elseif ( preg_match( '#^/web-cat/([^/]+)/?$#', $path, $m ) ) {
			$slug = sanitize_title( $m[1] );
			$term = get_term_by( 'slug', $slug, 'work_category' );
			if ( $term ) {
				$target = get_term_link( $term );
			} else {
				$target = get_post_type_archive_link( 'works' );
			}
		}

		if ( $target && ! is_wp_error( $target ) ) {
			if ( $query ) {
				$target = add_query_arg( wp_parse_args( $query ), $target );
			}
			wp_safe_redirect( $target, 301 );
			exit;
		}
	}
);
