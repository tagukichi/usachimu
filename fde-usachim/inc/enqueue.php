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

		wp_enqueue_script(
			'fde-usachim',
			fde_asset_url( 'assets/js/main.js' ),
			[],
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
		echo "<link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Noto+Sans+JP:wght@400;500;700&family=JetBrains+Mono:wght@400;500&display=swap\">\n";
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
