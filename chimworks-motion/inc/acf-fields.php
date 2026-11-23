<?php
/**
 * ACF field groups (CHIM WORKS).
 *
 * - 「TOPページ用」フィールド（FV / お悩み / 伴走支援 / 解決の手段 / 実績 / 調速 / よくある質問 / お問い合わせ）は、
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
		$fde_d      = fde_lp_defaults();

		// ---------- Hero ----------
		$top_fields[] = fde_acf_tab( 'hero', __( 'Hero（FV）', 'fde-usachim' ) );
		$top_fields[] = fde_acf_textarea( 'hero_stmt_l1', 'hero_statement_l1', __( 'ミッション 1行目（改行がそのまま反映されます）', 'fde-usachim' ), 2 );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_a',     'hero_statement_l2_a', __( '2行目（薄色部分・任意）', 'fde-usachim' ), '' );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_b',     'hero_statement_l2_b', __( '2行目（グラデーション強調部分）', 'fde-usachim' ), '社会の実現へ。' );
		$top_fields[] = fde_acf_textarea( 'hero_lede',      'hero_lede', __( 'サブコピー（ビジョン）', 'fde-usachim' ), 2, '**強調** で太字にできます。' );
		$top_fields[] = fde_acf_text( 'hero_cta', 'hero_cta', __( 'ボタンの文言（お問い合わせ欄へ移動）', 'fde-usachim' ), $fde_d['hero_cta'] );

		// ---------- こんなお悩み ----------
		$fde_help = static function ( string $key, string $help = '' ) use ( $fde_d ): string {
			return trim( $help . "\n【空欄のときの既定文】\n" . $fde_d[ $key ] );
		};
		$fde_area = static function ( string $key, string $label, string $help = '' ) use ( $fde_d, $fde_help ): array {
			$f                 = fde_acf_textarea( $key, $key, $label, max( 2, substr_count( $fde_d[ $key ], "\n" ) + 2 ) );
			$f['instructions'] = $fde_help( $key, $help );
			return $f;
		};
		$fde_pipe = '1行に1つ。「見出し｜説明」のように全角「｜」で区切ります。空欄なら既定文を表示します。';

		$top_fields[] = fde_acf_tab( 'pain', __( 'お悩み', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'pain_title', __( '見出し', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'pain_items', __( 'お悩み（1行に1つ。6つくらいがおすすめ）', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'pain_answer', __( 'お悩みの下の答え（改行はそのまま反映）', 'fde-usachim' ) );

		// ---------- 伴走支援 ----------
		$top_fields[] = fde_acf_tab( 'support', __( '伴走支援', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'support_lead', __( '説明文（改行はそのまま反映）', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'support_items', __( '伴走支援の特長（3つ）', 'fde-usachim' ), $fde_pipe );
		$top_fields[] = $fde_area( 'flow_items', __( '伴走の流れ', 'fde-usachim' ), $fde_pipe );
		$top_fields[] = $fde_area( 'flow_period', __( '期間の目安（流れの下に表示）', 'fde-usachim' ) );

		// ---------- 解決の手段 ----------
		$top_fields[] = fde_acf_tab( 'solutions', __( '解決の手段', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'solution_lead', __( '説明文', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'solution_items', __( '解決の手段（6つくらいがおすすめ）', 'fde-usachim' ), $fde_pipe );
		$top_fields[] = $fde_area( 'solution_tools', __( '対応ツール・技術（「、」区切り。名前に合う公式ロゴが自動で付きます）', 'fde-usachim' ) );

		// ---------- 実績 ----------
		$top_fields[] = fde_acf_tab( 'cases', __( '実績', 'fde-usachim' ) );
		foreach ( range( 1, 6 ) as $n ) {
			$top_fields[] = fde_acf_text( "case_{$n}_client", "case_{$n}_client", sprintf( __( '事例 %d — お客さまの表記（例：A社、B市）', 'fde-usachim' ), $n ), 'A社、B市（都内の自治体）' );
			$top_fields[] = fde_acf_text( "case_{$n}_tag", "case_{$n}_tag", sprintf( __( '事例 %d — 業種・種類のラベル（例：自治体、製造業）', 'fde-usachim' ), $n ) );
			$top_fields[] = [
				'key'          => "field_fde_case_{$n}_title",
				'label'        => sprintf( __( '事例 %d — 何をやったか（タイトル）', 'fde-usachim' ), $n ),
				'name'         => "case_{$n}_title",
				'type'         => 'text',
				'instructions' => 1 === $n ? __( '1〜3 は空欄なら既定の事例（A市・B社・C社）を表示します。4〜6 は、タイトルを入れた枠だけ表示します。画像を設定しない場合は、事例に合わせたアニメーションのイラストを表示します。', 'fde-usachim' ) : '',
			];
			$top_fields[] = fde_acf_textarea( "case_{$n}_text", "case_{$n}_text", sprintf( __( '事例 %d — 内容（取り組んだこと・変わったこと）', 'fde-usachim' ), $n ), 3 );
			$top_fields[] = fde_acf_textarea( "case_{$n}_points", "case_{$n}_points", sprintf( __( '事例 %d — 取り組んだこと（チェック付きの箇条書き。1行に1つ）', 'fde-usachim' ), $n ), 3 );
			$top_fields[] = [
				'key'           => "field_fde_case_{$n}_image",
				'label'         => sprintf( __( '事例 %d — 画像（任意。横長 16:10 がきれいに収まります）', 'fde-usachim' ), $n ),
				'name'          => "case_{$n}_image",
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
			];
		}

		// ---------- よくある質問 ----------
		$top_fields[] = fde_acf_tab( 'faq', __( 'よくある質問', 'fde-usachim' ) );
		$top_fields[] = $fde_area( 'faq_items', __( 'よくある質問（1行に1つ。「質問｜回答」）', 'fde-usachim' ) );

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
		// TOP の最後のお問い合わせ欄のボタン。
		$top_fields[] = fde_acf_tab( 'contact', __( 'お問い合わせ', 'fde-usachim' ) );
		$top_fields[] = [
			'key'          => 'field_fde_contact_form_url',
			'label'        => __( 'Google フォームの URL', 'fde-usachim' ),
			'name'         => 'contact_form_url',
			'type'         => 'url',
			'instructions' => __( 'Google フォームの「送信」→ リンク（🔗）で表示される URL を貼り付けます。TOP のお問い合わせボタンがこのフォームを開きます。空欄ならテーマに設定済みのフォームを使います。', 'fde-usachim' ),
		];
		$top_fields[] = fde_acf_text( 'contact_btn_label', 'contact_btn_label', __( 'ボタンの文言（空欄なら「お問い合わせフォームへ」）', 'fde-usachim' ), 'お問い合わせフォームへ' );
		$top_fields[] = fde_acf_textarea( 'contact_text', 'contact_text', __( 'ボタンの上の一文（空欄なら既定文）', 'fde-usachim' ), 2, '空欄なら：業務のお困りごと、まずはお気軽にご相談ください。内容を確認のうえ、ご連絡します。' );

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
