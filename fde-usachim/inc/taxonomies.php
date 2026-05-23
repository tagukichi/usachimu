<?php
/**
 * Custom taxonomies.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'init',
	static function () {
		register_taxonomy(
			'work_category',
			[ 'works' ],
			[
				'labels'            => [
					'name'          => __( 'カテゴリー', 'fde-usachim' ),
					'singular_name' => __( 'カテゴリー', 'fde-usachim' ),
					'menu_name'     => __( 'カテゴリー', 'fde-usachim' ),
					'all_items'     => __( 'すべてのカテゴリー', 'fde-usachim' ),
					'edit_item'     => __( 'カテゴリーを編集', 'fde-usachim' ),
					'add_new_item'  => __( '新しいカテゴリーを追加', 'fde-usachim' ),
					'search_items'  => __( 'カテゴリーを検索', 'fde-usachim' ),
				],
				'hierarchical'      => false,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => [ 'slug' => 'works-category', 'with_front' => false ],
			]
		);
	}
);

/**
 * Seed default work_category terms on theme activation.
 */
add_action(
	'after_switch_theme',
	static function () {
		$defaults = [ 'Web制作', 'AI活用', '業務自動化', '顧問', 'その他' ];
		foreach ( $defaults as $name ) {
			if ( ! term_exists( $name, 'work_category' ) ) {
				wp_insert_term( $name, 'work_category' );
			}
		}
		flush_rewrite_rules();
	}
);
