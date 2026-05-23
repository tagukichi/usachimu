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

		wp_register_script(
			'fde-usachim',
			fde_asset_url( 'assets/js/main.js' ),
			[],
			null,
			true
		);
		wp_script_add_data( 'fde-usachim', 'type', 'module' );
		wp_enqueue_script( 'fde-usachim' );
	}
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
