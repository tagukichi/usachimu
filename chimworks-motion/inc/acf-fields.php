<?php
/**
 * ACF field groups (CHIM WORKS).
 *
 * - 「TOPページ用」フィールド（FV / 事業内容 / 調速 / お問い合わせ）は、
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
		// 無料版 ACF のため繰り返しフィールドは使わず、4 枠（= 4 サービス）を用意。
		// 空欄の項目はサービスの既定（inc/service-pages.php）を表示する。
		// URL のフィールド名は以前のデータを引き継ぐ（1・2 枠目）
		$fde_svc_url_names = [ 1 => 'svc_web_url', 2 => 'svc_dx_url', 3 => 'svc_3_url', 4 => 'svc_4_url' ];
		$fde_n             = 0;
		foreach ( fde_services() as $fde_service ) {
			$n = ++$fde_n;
			$top_fields[] = [
				'key'          => "field_fde_svc_{$n}_title",
				'label'        => sprintf( __( '事業 %d — タイトル', 'fde-usachim' ), $n ),
				'name'         => "svc_{$n}_title",
				'type'         => 'text',
				'placeholder'  => $fde_service['title'],
				'instructions' => sprintf( __( '空欄なら「%s」', 'fde-usachim' ), $fde_service['title'] ),
			];
			$top_fields[] = fde_acf_textarea( "svc_{$n}_note", "svc_{$n}_note", sprintf( __( '事業 %d — 補足テキスト（改行はそのまま反映）', 'fde-usachim' ), $n ), 2, sprintf( __( '空欄なら：%s', 'fde-usachim' ), $fde_service['note'] ) );
			$top_fields[] = [
				'key'          => 'field_fde_' . $fde_svc_url_names[ $n ],
				'label'        => sprintf( __( '事業 %d — リンク先 URL（通常は空欄）', 'fde-usachim' ), $n ),
				'name'         => $fde_svc_url_names[ $n ],
				'type'         => 'url',
				'instructions' => sprintf( __( '空欄なら「%s」のサービスページ（/%s/）にリンクします。', 'fde-usachim' ), $fde_service['title'], $fde_service['slug'] ),
			];
		}

		// ---------- 調速（LP の先頭を引用） ----------
		// TOP の Product セクションと、システム開発ページの自社サービス欄で共通。
		$top_fields[] = fde_acf_tab( 'product', __( '調速', 'fde-usachim' ) );
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
			'instructions'  => __( '未設定ならテーマ同梱の横ロゴ（調速 チョーハヤ 不動産調査アプリ）を表示。', 'fde-usachim' ),
		];
		$top_fields[] = [
			'key'   => 'field_fde_chousoku_url',
			'label' => __( 'LP の URL', 'fde-usachim' ),
			'name'  => 'chousoku_url',
			'type'  => 'url',
		];
		$top_fields[] = fde_acf_text( 'chousoku_cta_label', 'chousoku_cta_label', __( 'ボタンの文言', 'fde-usachim' ), '調速のサービスサイトを見る' );

		// ---------- お問い合わせ（Google フォーム） ----------
		// 4 つのサービスページの下部に出るボタンの共通設定。URL が空のあいだはボタンを出さない。
		$top_fields[] = fde_acf_tab( 'contact', __( 'お問い合わせ', 'fde-usachim' ) );
		$top_fields[] = [
			'key'          => 'field_fde_contact_form_url',
			'label'        => __( 'Google フォームの URL', 'fde-usachim' ),
			'name'         => 'contact_form_url',
			'type'         => 'url',
			'instructions' => __( 'Google フォームの「送信」→ リンク（🔗）で表示される URL を貼り付けます。4 つのサービスページのお問い合わせボタンが、すべてこのフォームを開きます。空欄ならテーマに設定済みのフォームを使います。', 'fde-usachim' ),
		];
		$top_fields[] = [
			'key'          => 'field_fde_contact_prefill_entry',
			'label'        => __( '「お問い合わせの種類」の事前入力番号（任意）', 'fde-usachim' ),
			'name'         => 'contact_prefill_entry',
			'type'         => 'text',
			'placeholder'  => 'entry.123456789',
			'instructions' => __( '設定すると、各サービスページのボタンから開いたときに「お問い合わせの種類」がそのサービスで選ばれた状態になります。フォーム編集画面の「︙」→「事前入力した URL を取得」で種類を選んでリンクを作り、URL の中の「entry.数字」を貼り付けます。フォームの選択肢は「WEB制作」「業務効率化・DX支援」「システム開発」「AI研修・勉強会」と同じ表記にしてください。', 'fde-usachim' ),
		];
		$top_fields[] = fde_acf_text( 'contact_btn_label', 'contact_btn_label', __( 'ボタンの文言（空欄なら「お問い合わせフォームへ」）', 'fde-usachim' ), 'お問い合わせフォームへ' );
		$top_fields[] = fde_acf_textarea( 'contact_text', 'contact_text', __( 'ボタンの上の一文（空欄なら既定文）', 'fde-usachim' ), 2, '空欄なら：ご相談・お見積もりのご依頼は、こちらのフォームからお送りください。内容を確認のうえ、ご連絡します。' );

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
		// (3a) 会社概要 — 固定ページ「会社概要」（page-company.php）に表示
		//      空欄の項目は既定値（括弧内）を表示する
		// ============================================================
		$fde_cp_location = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => 'page-company.php' ] ] ];
		$fde_cp_page_id  = fde_company_page_id();
		if ( $fde_cp_page_id ) {
			// テンプレート未選択でもスラッグ company のページなら表示する
			$fde_cp_location[] = [ [ 'param' => 'page', 'operator' => '==', 'value' => (string) $fde_cp_page_id ] ];
		}
		$fde_cp_fields = [
			fde_acf_text( 'cp_name', 'cp_name', __( '商号（空欄なら 合同会社CHIM WORKS）', 'fde-usachim' ), '合同会社CHIM WORKS' ),
			fde_acf_text( 'cp_founded', 'cp_founded', __( '設立（空欄なら 2026年7月）', 'fde-usachim' ), '2026年7月' ),
			fde_acf_text( 'cp_ceo', 'cp_ceo', __( '代表者（空欄なら 田口幸樹）', 'fde-usachim' ), '田口幸樹' ),
			fde_acf_textarea( 'cp_address', 'cp_address', __( '所在地（空欄なら 埼玉県所沢市元町18-13）', 'fde-usachim' ), 2 ),
			fde_acf_textarea( 'cp_business', 'cp_business', __( '事業内容（改行はそのまま反映。空欄なら WEB制作／業務効率化・DX支援／システム開発／AI研修・勉強会 の4行）', 'fde-usachim' ), 3 ),
			[ 'key' => 'field_fde_cp_email', 'label' => __( '連絡先メール（空欄なら「連絡先」の行を表示しません）', 'fde-usachim' ), 'name' => 'cp_email', 'type' => 'email' ],
		];
		foreach ( [ 1, 2 ] as $n ) {
			$fde_cp_fields[] = fde_acf_text( "cp_extra_{$n}_k", "cp_extra_{$n}_k", sprintf( __( '追加項目 %d — 見出し（任意）', 'fde-usachim' ), $n ), '資本金' );
			$fde_cp_fields[] = fde_acf_text( "cp_extra_{$n}_v", "cp_extra_{$n}_v", sprintf( __( '追加項目 %d — 内容', 'fde-usachim' ), $n ) );
		}
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_company_page',
				'title'    => __( '会社概要', 'fde-usachim' ),
				'fields'   => $fde_cp_fields,
				'location' => $fde_cp_location,
				'position' => 'acf_after_title',
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
