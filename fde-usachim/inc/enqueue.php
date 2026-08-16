<?php
/**
 * Enqueue styles and scripts.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_style(
			'fde-usachim',
			fde_asset_url( 'assets/css/main.css' ),
			[],
			null
		);

		// GSAP（テーマ同梱・自己ホスト）。main.js より先に読み込む。
		wp_enqueue_script(
			'fde-gsap',
			fde_asset_url( 'assets/js/vendor/gsap.min.js' ),
			[],
			'3.12.5',
			true
		);
		wp_enqueue_script(
			'fde-gsap-scrolltrigger',
			fde_asset_url( 'assets/js/vendor/ScrollTrigger.min.js' ),
			[ 'fde-gsap' ],
			'3.12.5',
			true
		);

		wp_enqueue_script(
			'fde-usachim',
			fde_asset_url( 'assets/js/main.js' ),
			[ 'fde-gsap', 'fde-gsap-scrolltrigger' ],
			null,
			true
		);
	}
);

/**
 * Make the theme's main script load as an ES module.
 */
add_filter(
	'script_loader_tag',
	static function ( $tag, $handle ) {
		if ( 'fde-usachim' !== $handle ) {
			return $tag;
		}
		if ( false !== strpos( $tag, 'type="module"' ) ) {
			return $tag;
		}
		return preg_replace( '/<script\b/', '<script type="module"', $tag, 1 );
	},
	10,
	2
);

add_action(
	'wp_head',
	static function () {
		echo "<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\">\n";
		echo "<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>\n";
		echo "<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500;600&family=Inter+Tight:wght@500;600;700&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&family=Noto+Sans+JP:wght@400;500;700&display=swap\">\n";
	},
	5
);

/**
 * Remove the default Contact Form 7 stylesheet so the theme controls all form styles.
 */
add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_dequeue_style( 'contact-form-7' );
	},
	20
);
