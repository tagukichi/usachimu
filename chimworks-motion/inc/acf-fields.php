<?php
/**
 * ACF field groups (CHIM WORKS).
 *
 * - 「TOPページ用」フィールドは、設定 → 表示設定 → ホームページ
 *   に指定された固定ページの編集画面で編集します。
 * - ブランド / Footer / SEO / 連絡先など全ページ共通の設定は
 *   管理画面のサイドバー「テーマ設定」から編集します。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme settings options page.
 */
add_action(
	'acf/init',
	static function () {
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		acf_add_options_page(
			[
				'page_title'    => __( 'テーマ設定', 'fde-usachim' ),
				'menu_title'    => __( 'テーマ設定', 'fde-usachim' ),
				'menu_slug'     => 'fde-theme-settings',
				'capability'    => 'manage_options',
				'redirect'      => false,
				'icon_url'      => 'dashicons-admin-customizer',
				'position'      => 60,
				'update_button' => __( '保存', 'fde-usachim' ),
			]
		);
	}
);

/**
 * Helper: text field definition.
 */
function fde_acf_text( string $key, string $name, string $label, string $placeholder = '' ): array {
	return [
		'key'         => 'field_fde_' . $key,
		'label'       => $label,
		'name'        => $name,
		'type'        => 'text',
		'placeholder' => $placeholder,
	];
}

/**
 * Helper: textarea field definition.
 */
function fde_acf_textarea( string $key, string $name, string $label, int $rows = 3, string $instructions = '' ): array {
	return [
		'key'          => 'field_fde_' . $key,
		'label'        => $label,
		'name'         => $name,
		'type'         => 'textarea',
		'rows'         => $rows,
		'new_lines'    => '',
		'instructions' => $instructions,
	];
}

/**
 * Helper: tab field definition.
 */
function fde_acf_tab( string $key, string $label ): array {
	return [
		'key'   => 'tab_fde_' . $key,
		'label' => $label,
		'name'  => '',
		'type'  => 'tab',
	];
}

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
		// (1) Writing (post) details
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_post_details',
				'title'    => __( '記事メタ', 'fde-usachim' ),
				'fields'   => [
					fde_acf_text( 'post_tag_label', 'tag_label', __( 'タグ表示（カードの短い分類）', 'fde-usachim' ), 'AI / 評価' ),
					fde_acf_text( 'post_read_time', 'read_time', __( '読了時間', 'fde-usachim' ), '12 min' ),
				],
				'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ] ] ],
				'position' => 'side',
			]
		);

		// ============================================================
		// (3) TOPページ用フィールド — 固定ページ（ホーム）に表示
		// ============================================================
		$top_fields = [];

		// ---------- Hero ----------
		$top_fields[] = fde_acf_tab( 'hero', __( 'Hero（FV）', 'fde-usachim' ) );
		$top_fields[] = fde_acf_textarea( 'hero_stmt_l1', 'hero_statement_l1', __( 'ミッション 1行目（改行がそのまま反映されます）', 'fde-usachim' ), 2 );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_a',     'hero_statement_l2_a', __( '2行目（薄色部分・任意）', 'fde-usachim' ), '' );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_b',     'hero_statement_l2_b', __( '2行目（グラデーション強調部分）', 'fde-usachim' ), '社会の実現へ。' );
		$top_fields[] = fde_acf_textarea( 'hero_lede',      'hero_lede', __( 'サブコピー（ビジョン）', 'fde-usachim' ), 2, '**強調** で太字（白）にできます。' );

		// ---------- 01 Concept ----------
		$top_fields[] = fde_acf_tab( 'concept', __( '01 Concept', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'concept_lead', 'concept_lead', __( 'リード（大きな一文）', 'fde-usachim' ), '「つくる」ことで、誰かの役に立ちたい。' );
		$top_fields[] = fde_acf_textarea( 'concept_body', 'concept_body', __( '本文（段落区切りは空行）', 'fde-usachim' ), 8 );

		// ---------- 02 Service ----------
		$top_fields[] = fde_acf_tab( 'services', __( '02 Service', 'fde-usachim' ) );
		$top_fields[] = fde_acf_textarea( 'svc_web_desc', 'svc_web_desc', __( 'WEB開発 — 概要', 'fde-usachim' ), 3 );
		$top_fields[] = [
			'key'           => 'field_fde_service_1_image',
			'label'         => __( 'WEB開発 — イメージ画像（任意）', 'fde-usachim' ),
			'name'          => 'service_1_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		];
		$top_fields[] = fde_acf_textarea( 'svc_web_hp_desc', 'svc_web_hp_desc', __( 'ホームページ制作 — 説明', 'fde-usachim' ), 3 );
		$top_fields[] = fde_acf_textarea( 'svc_sys_desc', 'svc_sys_desc', __( 'システム開発・アプリ開発 — 説明', 'fde-usachim' ), 3 );
		$top_fields[] = [
			'key'           => 'field_fde_chousoku_show',
			'label'         => __( '調速（自社サービス）— セクションを表示', 'fde-usachim' ),
			'name'          => 'chousoku_show',
			'type'          => 'true_false',
			'ui'            => 1,
			'ui_on_text'    => __( '表示', 'fde-usachim' ),
			'ui_off_text'   => __( '非表示', 'fde-usachim' ),
			'default_value' => 1,
			'instructions'  => __( 'オフにすると Service 内の調速フィーチャー枠をまるごと非表示にします。', 'fde-usachim' ),
		];
		$top_fields[] = fde_acf_textarea( 'chousoku_desc', 'chousoku_desc', __( '調速（自社サービス）— 説明文', 'fde-usachim' ), 3 );
		$top_fields[] = [
			'key'           => 'field_fde_chousoku_logo',
			'label'         => __( '調速 — ロゴ画像', 'fde-usachim' ),
			'name'          => 'chousoku_logo',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => __( '横長ロゴを推奨。未設定時はテキストで「調速」と表示。', 'fde-usachim' ),
		];
		$top_fields[] = [
			'key'   => 'field_fde_chousoku_url',
			'label' => __( '調速 — LP の URL', 'fde-usachim' ),
			'name'  => 'chousoku_url',
			'type'  => 'url',
		];
		$top_fields[] = fde_acf_textarea( 'svc_dx_desc', 'svc_dx_desc', __( '業務効率化支援 — 概要（見出し下・1行推奨）', 'fde-usachim' ), 2 );
		$top_fields[] = fde_acf_textarea( 'svc_dx_detail', 'svc_dx_detail', __( '行政や企業におけるDX推進支援 — 説明', 'fde-usachim' ), 4 );
		foreach ( [ 1, 2, 3 ] as $n ) {
			$top_fields[] = [
				'key'           => "field_fde_service_2_image_{$n}",
				'label'         => sprintf( __( '業務効率化支援 — BOX内の画像 %d（任意）', 'fde-usachim' ), $n ),
				'name'          => "service_2_image_{$n}",
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => 1 === $n ? __( '2枚以上設定すると数秒ごとにクロスフェードで切り替わります。1枚のみでもOK。', 'fde-usachim' ) : '',
			];
		}

		// ---------- 03 About ----------
		$top_fields[] = fde_acf_tab( 'about', __( '03 About', 'fde-usachim' ) );
		$top_fields[] = [
			'key'           => 'field_fde_about_portrait',
			'label'         => __( 'ポートレート画像', 'fde-usachim' ),
			'name'          => 'about_portrait',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		];
		$top_fields[] = fde_acf_text( 'about_name',     'about_name',     __( '名前', 'fde-usachim' ), '' );
		$top_fields[] = fde_acf_text( 'about_position', 'about_position', __( '肩書', 'fde-usachim' ), 'フリーランス デザイナー・エンジニア' );
		$top_fields[] = fde_acf_textarea( 'about_lead', 'about_lead', __( 'リード（大きな一文）', 'fde-usachim' ), 2 );
		$top_fields[] = fde_acf_textarea( 'about_body', 'about_body', __( '本文（段落区切りは空行）', 'fde-usachim' ), 5 );
		foreach ( [ 1, 2, 3 ] as $n ) {
			$top_fields[] = fde_acf_text( "about_tl_{$n}_year", "about_tl_{$n}_year", sprintf( __( '経歴 %d — 期間', 'fde-usachim' ), $n ), '2011 – 2015' );
			$top_fields[] = fde_acf_text( "about_tl_{$n}_text", "about_tl_{$n}_text", sprintf( __( '経歴 %d — 内容', 'fde-usachim' ), $n ) );
		}

		// ---------- 04 Contact ----------
		$top_fields[] = fde_acf_tab( 'contact', __( '04 Contact', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'contact_meta', 'contact_meta', __( 'Contact メタ', 'fde-usachim' ), 'RESPONSE WITHIN 1 BIZ DAY' );
		$top_fields[] = fde_acf_text( 'contact_lead', 'contact_lead', __( 'Contact リード', 'fde-usachim' ), 'まずはお気軽にご相談ください。' );
		$top_fields[] = fde_acf_textarea( 'contact_note', 'contact_note', __( 'Contact 補足', 'fde-usachim' ), 3 );
		$top_fields[] = fde_acf_text( 'cf7_shortcode_top', 'cf7_shortcode', __( 'CF7 ショートコード', 'fde-usachim' ), '[contact-form-7 id="123" title="お問い合わせ"]' );

		acf_add_local_field_group(
			[
				'key'      => 'group_fde_front_page',
				'title'    => __( 'TOPページ — セクションテキスト', 'fde-usachim' ),
				'fields'   => $top_fields,
				'location' => [
					[
						[ 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ],
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
		// (4) テーマ設定（options page） — 全ページ共通
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_theme_settings',
				'title'    => __( 'テーマ設定', 'fde-usachim' ),
				'fields'   => [
					// ---------- ブランド ----------
					fde_acf_tab( 'brand', __( 'ブランド', 'fde-usachim' ) ),
					fde_acf_text( 'brand_name', 'brand_name', __( '屋号（テキストロゴ）', 'fde-usachim' ), 'CHIMWORKS' ),
					fde_acf_text( 'brand_subtitle', 'brand_subtitle', __( '屋号サブ（mono の slash テキスト）', 'fde-usachim' ), '// FORWARD DEPLOYED' ),
					fde_acf_text( 'cta_label', 'cta_label', __( 'ヘッダーCTA ラベル', 'fde-usachim' ), '相談を始める →' ),

					// ---------- 連絡先（全ページ共通） ----------
					fde_acf_tab( 'channels', __( '連絡先', 'fde-usachim' ) ),
					[ 'key' => 'field_fde_contact_email', 'label' => __( '通知先 / 表示メール', 'fde-usachim' ), 'name' => 'contact_email', 'type' => 'email' ],
					[ 'key' => 'field_fde_sns_x_url',    'label' => __( 'X (Twitter) URL', 'fde-usachim' ), 'name' => 'sns_x_url',    'type' => 'url' ],
					fde_acf_text( 'sns_x_handle', 'sns_x_handle', __( 'X ハンドル表示', 'fde-usachim' ), '@chim_works' ),
					[ 'key' => 'field_fde_sns_facebook_url', 'label' => __( 'Facebook URL', 'fde-usachim' ), 'name' => 'sns_facebook_url', 'type' => 'url' ],

					// ---------- Footer ----------
					fde_acf_tab( 'footer', __( 'Footer', 'fde-usachim' ) ),
					fde_acf_textarea( 'footer_meta_l', 'footer_meta_left', __( 'Footer メタ 左', 'fde-usachim' ), 3 ),
					fde_acf_textarea( 'footer_meta_r', 'footer_meta_right', __( 'Footer メタ 右', 'fde-usachim' ), 3 ),

					// ---------- SEO ----------
					fde_acf_tab( 'seo', __( 'SEO / OGP', 'fde-usachim' ) ),
					fde_acf_textarea( 'site_description', 'site_description', __( 'サイト説明文', 'fde-usachim' ), 3 ),
					[
						'key'           => 'field_fde_default_og_image',
						'label'         => __( 'デフォルト OGP 画像', 'fde-usachim' ),
						'name'          => 'default_og_image',
						'type'          => 'image',
						'return_format' => 'url',
						'instructions'  => __( '推奨：1200×630px', 'fde-usachim' ),
					],
					fde_acf_text( 'ga4_id', 'ga4_id', __( 'GA4 測定ID', 'fde-usachim' ), 'G-XXXXXXXXXX' ),
				],
				'location' => [ [ [ 'param' => 'options_page', 'operator' => '==', 'value' => 'fde-theme-settings' ] ] ],
				'menu_order'      => 0,
				'position'        => 'normal',
				'style'           => 'default',
				'label_placement' => 'top',
			]
		);
	}
);
