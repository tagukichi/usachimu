<?php
/**
 * ACF field groups (CHIM WORKS).
 *
 * - 「TOPページ用」フィールド（FV / 事業内容 / 調速 / フッター会社概要 / Contact）は、
 *   設定 → 表示設定 → ホームページに指定した固定ページの編集画面で編集します。
 * - 「テーマ設定」（options ページ）は ACF Pro 環境でのみ表示されます。
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
		$top_fields[] = fde_acf_textarea( 'hero_lede',      'hero_lede', __( 'サブコピー（ビジョン）', 'fde-usachim' ), 2, '**強調** で太字にできます。' );

		// ---------- 事業内容 ----------
		$top_fields[] = fde_acf_tab( 'services', __( '事業内容', 'fde-usachim' ) );
		$top_fields[] = [ 'key' => 'field_fde_svc_web_url', 'label' => __( 'WEB制作・システム開発 — 特設ページの URL（設定すると行がリンクになる）', 'fde-usachim' ), 'name' => 'svc_web_url', 'type' => 'url' ];
		$top_fields[] = [ 'key' => 'field_fde_svc_dx_url', 'label' => __( '業務効率化・DX支援 — 特設ページの URL（設定すると行がリンクになる）', 'fde-usachim' ), 'name' => 'svc_dx_url', 'type' => 'url' ];

		// ---------- 調速（LP の先頭を引用） ----------
		// FV の直下に独立セクションとして出る。トグル OFF のあいだは
		// セクションもナビ項目も出ない（既定 OFF。然るべきタイミングで ON に）。
		$top_fields[] = fde_acf_tab( 'product', __( '調速', 'fde-usachim' ) );
		$top_fields[] = [
			'key'           => 'field_fde_chousoku_show',
			'label'         => __( '調速セクションを表示', 'fde-usachim' ),
			'name'          => 'chousoku_show',
			'type'          => 'true_false',
			'ui'            => 1,
			'ui_on_text'    => __( '表示', 'fde-usachim' ),
			'ui_off_text'   => __( '非表示', 'fde-usachim' ),
			'default_value' => 0,
			'instructions'  => __( 'オンにすると FV の直下に調速のセクションが出て、ナビにも「調速」が加わります。', 'fde-usachim' ),
		];
		$top_fields[] = fde_acf_textarea( 'chousoku_title', 'chousoku_title', __( 'キャッチコピー（LP と同じ。{{ }} で囲むと青のグラデ強調）', 'fde-usachim' ), 3, '空欄なら LP のコピーを表示：住所を入力して、{{約5分}}。／不動産調査業務を効率化する／クラウド型ソフトウェア。' );
		$top_fields[] = fde_acf_textarea( 'chousoku_lead', 'chousoku_lead', __( 'リード文（{{ }} で強調可）', 'fde-usachim' ), 3, '空欄なら LP のリード文を表示。' );
		foreach ( [ 1, 2, 3, 4 ] as $n ) {
			$top_fields[] = fde_acf_text( "chousoku_point_{$n}_k", "chousoku_point_{$n}_k", sprintf( __( 'ポイント %d（チェック付きの短い一言）', 'fde-usachim' ), $n ) );
		}
		$top_fields[] = [
			'key'           => 'field_fde_chousoku_logo',
			'label'         => __( 'ロゴ画像（任意）', 'fde-usachim' ),
			'name'          => 'chousoku_logo',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => __( '未設定ならテーマ同梱の横ロゴ（調速 チョーソク 不動産調査アプリ）を表示。', 'fde-usachim' ),
		];
		$top_fields[] = [
			'key'   => 'field_fde_chousoku_url',
			'label' => __( 'LP の URL', 'fde-usachim' ),
			'name'  => 'chousoku_url',
			'type'  => 'url',
		];
		$top_fields[] = fde_acf_text( 'chousoku_cta_label', 'chousoku_cta_label', __( 'ボタンの文言', 'fde-usachim' ), '調速のサービスサイトを見る' );

		// ---------- フッター会社概要 ----------
		$top_fields[] = fde_acf_tab( 'company', __( 'フッター会社概要', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'company_name',    'company_name',    __( '商号', 'fde-usachim' ), '合同会社CHIM WORKS' );
		$top_fields[] = fde_acf_text( 'company_founded', 'company_founded', __( '設立', 'fde-usachim' ), '2026年8月' );
		$top_fields[] = fde_acf_text( 'company_ceo',     'company_ceo',     __( '代表者（空欄なら 田口幸樹）', 'fde-usachim' ), '田口幸樹' );
		$top_fields[] = fde_acf_text( 'company_capital', 'company_capital', __( '資本金', 'fde-usachim' ), '1,000,000円' );
		$top_fields[] = fde_acf_textarea( 'company_address', 'company_address', __( '所在地', 'fde-usachim' ), 2 );
		$top_fields[] = fde_acf_textarea( 'company_business', 'company_business', __( '事業内容（1行1項目。空欄なら WEB制作・システム開発 / 業務効率化・DX支援）', 'fde-usachim' ), 3 );
		foreach ( [ 1, 2 ] as $n ) {
			$top_fields[] = fde_acf_text( "company_extra_{$n}_k", "company_extra_{$n}_k", sprintf( __( '自由項目 %d — 見出し', 'fde-usachim' ), $n ), '取引銀行' );
			$top_fields[] = fde_acf_text( "company_extra_{$n}_v", "company_extra_{$n}_v", sprintf( __( '自由項目 %d — 内容', 'fde-usachim' ), $n ) );
		}


		// ---------- Contact（フォームなし・メールのみ） ----------
		$top_fields[] = fde_acf_tab( 'contact', __( 'Contact', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'contact_lead', 'contact_lead', __( '案内文（{email} の位置にメールアドレスが入る）', 'fde-usachim' ), 'お問い合わせは {email} まで。' );
		$top_fields[] = [ 'key' => 'field_fde_contact_email_top', 'label' => __( 'メールアドレス（空欄なら info@usachim.com）', 'fde-usachim' ), 'name' => 'contact_email', 'type' => 'email' ];
		$top_fields[] = fde_acf_text( 'contact_notice', 'contact_notice', __( '注意書き（案内文の後ろに続けて表示）', 'fde-usachim' ), '営業・勧誘のご連絡はお断りしております。' );

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
		// (3b) 特設ページ（3D）— page template で選んだ固定ページに表示
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_special_3d',
				'title'    => __( '特設ページ（3D）の設定', 'fde-usachim' ),
				'fields'   => [
					[
						'key'           => 'field_fde_sp_scene',
						'label'         => __( '3D シーン', 'fde-usachim' ),
						'name'          => 'sp_scene',
						'type'          => 'select',
						'choices'       => [
							'web' => __( 'WEB制作・システム開発（浮遊するワイヤーフレームの街）', 'fde-usachim' ),
							'dx'  => __( '業務効率化・DX支援（脈打つネットワーク）', 'fde-usachim' ),
						],
						'default_value' => 'web',
						'return_format' => 'value',
					],
					fde_acf_text( 'sp_eyebrow', 'sp_eyebrow', __( '見出し上の小さなラベル', 'fde-usachim' ), 'SERVICE 01' ),
					fde_acf_textarea( 'sp_lead', 'sp_lead', __( 'リード文（FV に重ねる 1〜2 文）', 'fde-usachim' ), 2 ),
				],
				'location' => [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'template-3d.php' ] ] ],
				'position' => 'normal',
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
