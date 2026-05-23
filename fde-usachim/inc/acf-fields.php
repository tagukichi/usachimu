<?php
/**
 * ACF field groups defined in code.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register a theme settings options page.
 */
add_action(
	'acf/init',
	static function () {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		acf_add_options_page(
			[
				'page_title'  => __( 'テーマ設定', 'fde-usachim' ),
				'menu_title'  => __( 'テーマ設定', 'fde-usachim' ),
				'menu_slug'   => 'fde-theme-settings',
				'capability'  => 'manage_options',
				'redirect'    => false,
				'icon_url'    => 'dashicons-admin-customizer',
				'position'    => 60,
				'update_button' => __( '保存', 'fde-usachim' ),
			]
		);
	}
);

/**
 * Register field groups.
 */
add_action(
	'acf/init',
	static function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		// ============================================================
		// Works: project details
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_works_details',
				'title'    => __( '実績詳細', 'fde-usachim' ),
				'fields'   => [
					[
						'key'           => 'field_fde_client_name',
						'label'         => __( 'クライアント名', 'fde-usachim' ),
						'name'          => 'client_name',
						'type'          => 'text',
						'instructions'  => __( '非公開の場合は「非公開」と入力。', 'fde-usachim' ),
						'placeholder'   => __( '株式会社○○ / 非公開', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_client_industry',
						'label'         => __( '業界', 'fde-usachim' ),
						'name'          => 'client_industry',
						'type'          => 'text',
						'placeholder'   => __( '小売 / 製造 / 士業 など', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_project_period',
						'label'         => __( 'プロジェクト期間', 'fde-usachim' ),
						'name'          => 'project_period',
						'type'          => 'text',
						'placeholder'   => __( '2025年4月〜6月（3ヶ月）', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_project_role',
						'label'         => __( '担当範囲', 'fde-usachim' ),
						'name'          => 'project_role',
						'type'          => 'text',
						'placeholder'   => __( '要件定義 / 設計 / 実装 / 運用', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_tech_stack',
						'label'         => __( '使用技術', 'fde-usachim' ),
						'name'          => 'tech_stack',
						'type'          => 'text',
						'instructions'  => __( 'カンマ区切りで入力。例：WordPress, ACF, Claude API', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_challenge',
						'label'         => __( '課題', 'fde-usachim' ),
						'name'          => 'challenge',
						'type'          => 'textarea',
						'rows'          => 4,
						'new_lines'     => 'wpautop',
					],
					[
						'key'           => 'field_fde_approach',
						'label'         => __( 'アプローチ', 'fde-usachim' ),
						'name'          => 'approach',
						'type'          => 'wysiwyg',
						'tabs'          => 'all',
						'toolbar'       => 'full',
						'media_upload'  => 0,
					],
					[
						'key'           => 'field_fde_outcome',
						'label'         => __( '成果', 'fde-usachim' ),
						'name'          => 'outcome',
						'type'          => 'wysiwyg',
						'tabs'          => 'all',
						'toolbar'       => 'full',
						'media_upload'  => 0,
					],
					[
						'key'           => 'field_fde_external_url',
						'label'         => __( '外部リンク', 'fde-usachim' ),
						'name'          => 'external_url',
						'type'          => 'url',
						'instructions'  => __( '公開できる場合のみ入力。', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_hero_image',
						'label'         => __( 'メインビジュアル', 'fde-usachim' ),
						'name'          => 'hero_image',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => __( '空の場合はアイキャッチ画像を使用します。', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_gallery',
						'label'         => __( 'ギャラリー画像', 'fde-usachim' ),
						'name'          => 'gallery',
						'type'          => 'gallery',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'insert'        => 'append',
					],
					[
						'key'           => 'field_fde_is_published_externally',
						'label'         => __( '外部公開OK', 'fde-usachim' ),
						'name'          => 'is_published_externally',
						'type'          => 'true_false',
						'ui'            => 1,
						'instructions'  => __( 'クライアントから外部公開の許可を得ている場合にON。', 'fde-usachim' ),
					],
				],
				'location' => [
					[
						[
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'works',
						],
					],
				],
				'menu_order'      => 0,
				'position'        => 'normal',
				'style'           => 'default',
				'label_placement' => 'top',
				'active'          => true,
			]
		);

		// ============================================================
		// Theme settings (options page)
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_theme_settings',
				'title'    => __( 'テーマ設定', 'fde-usachim' ),
				'fields'   => [
					[
						'key'   => 'field_fde_settings_tab_profile',
						'label' => __( 'プロフィール', 'fde-usachim' ),
						'name'  => '',
						'type'  => 'tab',
					],
					[
						'key'           => 'field_fde_profile_image',
						'label'         => __( 'プロフィール画像', 'fde-usachim' ),
						'name'          => 'profile_image',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
					],
					[
						'key'           => 'field_fde_profile_name',
						'label'         => __( '表示名', 'fde-usachim' ),
						'name'          => 'profile_name',
						'type'          => 'text',
						'placeholder'   => __( 'うさちむデザイン', 'fde-usachim' ),
					],
					[
						'key'           => 'field_fde_profile_tagline',
						'label'         => __( 'タグライン', 'fde-usachim' ),
						'name'          => 'profile_tagline',
						'type'          => 'text',
						'placeholder'   => __( 'Forward Deployed Engineer', 'fde-usachim' ),
					],
					[
						'key'          => 'field_fde_profile_bio',
						'label'        => __( '自己紹介（短文）', 'fde-usachim' ),
						'name'         => 'profile_bio',
						'type'         => 'textarea',
						'rows'         => 4,
						'new_lines'    => 'wpautop',
						'instructions' => __( 'フッターやTop抜粋で使用します。', 'fde-usachim' ),
					],

					[
						'key'   => 'field_fde_settings_tab_sns',
						'label' => __( 'SNS', 'fde-usachim' ),
						'name'  => '',
						'type'  => 'tab',
					],
					[
						'key'         => 'field_fde_sns_x_url',
						'label'       => __( 'X (Twitter) URL', 'fde-usachim' ),
						'name'        => 'sns_x_url',
						'type'        => 'url',
						'placeholder' => 'https://x.com/...',
					],
					[
						'key'         => 'field_fde_sns_facebook_url',
						'label'       => __( 'Facebook URL', 'fde-usachim' ),
						'name'        => 'sns_facebook_url',
						'type'        => 'url',
						'placeholder' => 'https://www.facebook.com/...',
					],

					[
						'key'   => 'field_fde_settings_tab_contact',
						'label' => __( '問い合わせ', 'fde-usachim' ),
						'name'  => '',
						'type'  => 'tab',
					],
					[
						'key'         => 'field_fde_contact_email',
						'label'       => __( '通知先メール', 'fde-usachim' ),
						'name'        => 'contact_email',
						'type'        => 'email',
						'instructions' => __( 'Contact Form 7 と同期させる用。', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_cf7_shortcode',
						'label'       => __( 'CF7 ショートコード', 'fde-usachim' ),
						'name'        => 'cf7_shortcode',
						'type'        => 'text',
						'placeholder' => '[contact-form-7 id="123" title="お問い合わせ"]',
						'instructions' => __( 'Contact Form 7 で作成したフォームのショートコードを貼り付け。', 'fde-usachim' ),
					],

					[
						'key'   => 'field_fde_settings_tab_seo',
						'label' => __( 'SEO / OGP', 'fde-usachim' ),
						'name'  => '',
						'type'  => 'tab',
					],
					[
						'key'         => 'field_fde_site_description',
						'label'       => __( 'サイトのデフォルト説明文', 'fde-usachim' ),
						'name'        => 'site_description',
						'type'        => 'textarea',
						'rows'        => 3,
					],
					[
						'key'           => 'field_fde_default_og_image',
						'label'         => __( 'デフォルト OGP 画像', 'fde-usachim' ),
						'name'          => 'default_og_image',
						'type'          => 'image',
						'return_format' => 'url',
						'instructions'  => __( '推奨：1200×630px', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_ga4_id',
						'label'       => __( 'GA4 測定ID', 'fde-usachim' ),
						'name'        => 'ga4_id',
						'type'        => 'text',
						'placeholder' => 'G-XXXXXXXXXX',
					],
				],
				'location' => [
					[
						[
							'param'    => 'options_page',
							'operator' => '==',
							'value'    => 'fde-theme-settings',
						],
					],
				],
				'menu_order'      => 0,
				'position'        => 'normal',
				'style'           => 'default',
				'label_placement' => 'top',
				'active'          => true,
			]
		);
	}
);
