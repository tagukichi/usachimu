<?php
/**
 * Custom post types.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'init',
	static function () {
		register_post_type(
			'works',
			[
				'labels'              => [
					'name'                  => __( '実績', 'fde-usachim' ),
					'singular_name'         => __( '実績', 'fde-usachim' ),
					'menu_name'             => __( '実績', 'fde-usachim' ),
					'all_items'             => __( '実績一覧', 'fde-usachim' ),
					'add_new'               => __( '新規追加', 'fde-usachim' ),
					'add_new_item'          => __( '新しい実績を追加', 'fde-usachim' ),
					'edit_item'             => __( '実績を編集', 'fde-usachim' ),
					'new_item'              => __( '新しい実績', 'fde-usachim' ),
					'view_item'             => __( '実績を表示', 'fde-usachim' ),
					'view_items'            => __( '実績を表示', 'fde-usachim' ),
					'search_items'          => __( '実績を検索', 'fde-usachim' ),
					'not_found'             => __( '実績が見つかりません', 'fde-usachim' ),
					'not_found_in_trash'    => __( 'ゴミ箱に実績はありません', 'fde-usachim' ),
					'featured_image'        => __( 'サムネイル', 'fde-usachim' ),
					'set_featured_image'    => __( 'サムネイルを設定', 'fde-usachim' ),
					'remove_featured_image' => __( 'サムネイルを削除', 'fde-usachim' ),
					'use_featured_image'    => __( 'サムネイルとして使用', 'fde-usachim' ),
					'archives'              => __( '実績アーカイブ', 'fde-usachim' ),
				],
				'public'              => true,
				'has_archive'         => 'works',
				'rewrite'             => [ 'slug' => 'works', 'with_front' => false ],
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-portfolio',
				'menu_position'       => 5,
				'supports'            => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
				'exclude_from_search' => false,
			]
		);
	}
);
