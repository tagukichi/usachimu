<?php
/**
 * Theme setup: add_theme_support, nav menus, image sizes.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	static function () {
		load_theme_textdomain( 'fde-usachim', FDE_USACHIM_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support(
			'html5',
			[ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ]
		);
		add_theme_support(
			'custom-logo',
			[
				'height'      => 32,
				'width'       => 160,
				'flex-height' => true,
				'flex-width'  => true,
			]
		);

		register_nav_menus(
			[
				'primary' => __( 'プライマリ', 'fde-usachim' ),
				'footer'  => __( 'フッター', 'fde-usachim' ),
			]
		);

		add_image_size( 'work-card', 800, 600, true );
		add_image_size( 'work-hero', 1600, 900, true );
	}
);

add_filter(
	'document_title_separator',
	static function () {
		return '|';
	}
);
