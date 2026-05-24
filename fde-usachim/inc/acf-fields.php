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
		// (1) Works (case study) details — CPT
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_works_details',
				'title'    => __( '実績詳細', 'fde-usachim' ),
				'fields'   => [
					fde_acf_text( 'industry', 'industry', __( '業界', 'fde-usachim' ), '基礎自治体 / SaaSスタートアップ など' ),
					fde_acf_textarea( 'summary', 'summary', __( '概要', 'fde-usachim' ), 4, '本文として実績スプレッドに表示。' ),
					fde_acf_text( 'headline', 'headline', __( 'ヘッドライン（KPIポスター大文字）', 'fde-usachim' ), '−85% / 8h → 30m など' ),
					fde_acf_text( 'headline_label', 'headline_label', __( 'ヘッドラインの説明', 'fde-usachim' ), '提案リードタイム / 月次経理作業 など' ),

					// KPI 4組（繰り返しなし）
					fde_acf_text( 'kpi_1_k', 'kpi_1_k', __( 'KPI 1 指標', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_1_v', 'kpi_1_v', __( 'KPI 1 値', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_2_k', 'kpi_2_k', __( 'KPI 2 指標', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_2_v', 'kpi_2_v', __( 'KPI 2 値', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_3_k', 'kpi_3_k', __( 'KPI 3 指標', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_3_v', 'kpi_3_v', __( 'KPI 3 値', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_4_k', 'kpi_4_k', __( 'KPI 4 指標', 'fde-usachim' ) ),
					fde_acf_text( 'kpi_4_v', 'kpi_4_v', __( 'KPI 4 値', 'fde-usachim' ) ),

					fde_acf_text( 'tags', 'tags', __( 'Stack タグ（カンマ区切り）', 'fde-usachim' ), 'LLM, Python, Slack Bot, Notion API' ),
					fde_acf_text( 'year', 'year', __( '実施年', 'fde-usachim' ), '2025 / 2024–25' ),
					fde_acf_text( 'scale', 'scale', __( '規模', 'fde-usachim' ), '〜300万円 / 〜800万円' ),
					fde_acf_text( 'role', 'role', __( '体制', 'fde-usachim' ), '一人 / 4ヶ月  ・  一人 + 内製2名' ),
					[
						'key'   => 'field_fde_external_url',
						'label' => __( '外部リンク（任意）', 'fde-usachim' ),
						'name'  => 'external_url',
						'type'  => 'url',
					],
				],
				'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'works' ] ] ],
			]
		);

		// ============================================================
		// (2) Writing (post) details
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
		$top_fields[] = fde_acf_tab( 'hero', __( 'Hero', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'hero_stmt_l1',       'hero_statement_l1', __( 'Hero ステートメント 1行目', 'fde-usachim' ), '資料の中のAIではなく、' );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_a',     'hero_statement_l2_a', __( 'Hero 2行目（薄色部分）', 'fde-usachim' ), '現場の手の中で動く' );
		$top_fields[] = fde_acf_text( 'hero_stmt_l2_b',     'hero_statement_l2_b', __( 'Hero 2行目（強調部分）', 'fde-usachim' ), 'AIを。' );
		$top_fields[] = fde_acf_textarea( 'hero_lede',      'hero_lede', __( 'Hero リード文', 'fde-usachim' ), 4, '**強調** で太字（白）にできます。' );

		// ---------- §01 Why ----------
		$top_fields[] = fde_acf_tab( 'why', __( '§01 Why', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'why_title', 'why_title', __( 'セクション タイトル', 'fde-usachim' ), 'AIを味方につけ、次の10年に備える。' );
		$top_fields[] = fde_acf_textarea( 'why_premise', 'why_premise', __( 'Premise（大見出し）', 'fde-usachim' ), 2, '**強調** で太字。' );
		$top_fields[] = fde_acf_textarea( 'why_premise_sub', 'why_premise_sub', __( 'Premise サブテキスト', 'fde-usachim' ), 3, '大見出しの下に小さく表示。' );

		// 3つの柱
		foreach ( [ 1, 2, 3 ] as $n ) {
			$top_fields[] = fde_acf_text( "why_pillar_{$n}_no",   "why_pillar_{$n}_no",   sprintf( __( '柱 %d — 番号（一/二/三）', 'fde-usachim' ), $n ) );
			$top_fields[] = fde_acf_text( "why_pillar_{$n}_head", "why_pillar_{$n}_head", sprintf( __( '柱 %d — 見出し', 'fde-usachim' ), $n ) );
			$top_fields[] = fde_acf_textarea( "why_pillar_{$n}_body", "why_pillar_{$n}_body", sprintf( __( '柱 %d — 本文', 'fde-usachim' ), $n ), 4 );
		}

		$top_fields[] = fde_acf_text( 'why_df_title', 'why_dataflow_title', __( 'Dataflow タイトル', 'fde-usachim' ), '散らばったデータを、現場の判断に届くまで。' );
		$top_fields[] = fde_acf_textarea( 'why_df_note', 'why_dataflow_note', __( 'Dataflow 説明', 'fde-usachim' ), 3 );
		$top_fields[] = [
			'key'           => 'field_fde_why_df_image',
			'label'         => __( 'Dataflow 画像（右側）', 'fde-usachim' ),
			'name'          => 'why_dataflow_image',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
			'instructions'  => __( 'アップロードすると、ASCIIダイアグラムの代わりにこの画像を表示します。', 'fde-usachim' ),
		];
		$top_fields[] = fde_acf_textarea( 'why_df_diag', 'why_dataflow_diagram', __( 'Dataflow ダイアグラム（画像未設定時のフォールバック）', 'fde-usachim' ), 12 );

		// ---------- §02 About ----------
		$top_fields[] = fde_acf_tab( 'about', __( '§02 About', 'fde-usachim' ) );
		$top_fields[] = [
			'key'           => 'field_fde_about_portrait',
			'label'         => __( 'ポートレート画像', 'fde-usachim' ),
			'name'          => 'about_portrait',
			'type'          => 'image',
			'return_format' => 'array',
			'preview_size'  => 'medium',
		];
		$top_fields[] = fde_acf_text( 'about_portrait_fig', 'about_portrait_fig', __( 'FIG ラベル', 'fde-usachim' ), 'FIG. 01 — CHIM' );

		// Profile 情報（portrait 直下に表示）
		$top_fields[] = fde_acf_text( 'about_name',     'about_name',     __( '名前', 'fde-usachim' ), 'CHIM' );
		$top_fields[] = fde_acf_text( 'about_position', 'about_position', __( '肩書', 'fde-usachim' ), 'Forward Deployed Engineer' );
		$top_fields[] = fde_acf_text( 'about_location', 'about_location', __( '所在地', 'fde-usachim' ), 'Tokyo, JP' );
		$top_fields[] = fde_acf_text( 'about_since',    'about_since',    __( '開始年', 'fde-usachim' ), 'SINCE 2021' );

		$top_fields[] = fde_acf_textarea( 'about_lead', 'about_lead', __( 'About リード（明朝大）', 'fde-usachim' ), 3 );
		$top_fields[] = fde_acf_textarea( 'about_body', 'about_body', __( 'About 本文', 'fde-usachim' ), 8, '段落区切りは空行。**強調** で太字。' );

		// ---------- §03 Services ----------
		$top_fields[] = fde_acf_tab( 'services', __( '§03 Services', 'fde-usachim' ) );
		foreach ( [ 1, 2, 3, 4 ] as $n ) {
			$top_fields[] = fde_acf_text( "service_{$n}_no",    "service_{$n}_no",    sprintf( __( 'サービス %d — 番号', 'fde-usachim' ), $n ), 'I / II / III / IV' );
			$top_fields[] = fde_acf_text( "service_{$n}_title", "service_{$n}_title", sprintf( __( 'サービス %d — 名称', 'fde-usachim' ), $n ) );
			$top_fields[] = fde_acf_text( "service_{$n}_meta",  "service_{$n}_meta",  sprintf( __( 'サービス %d — メタ', 'fde-usachim' ), $n ), 'LLM / RAG / Eval' );
			$top_fields[] = fde_acf_textarea( "service_{$n}_desc", "service_{$n}_desc", sprintf( __( 'サービス %d — 説明', 'fde-usachim' ), $n ), 3 );
		}
		$top_fields[] = fde_acf_textarea( 'services_note', 'services_note', __( '契約に関する注記（**強調** 可）', 'fde-usachim' ), 2 );

		// ---------- §04 Process ----------
		$top_fields[] = fde_acf_tab( 'process', __( '§04 Process', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'process_meta', 'process_meta', __( 'メタ（期間レンジ）', 'fde-usachim' ), 'MIN 0w · TYP 10w · MAX 26w' );
		$top_fields[] = fde_acf_textarea( 'process_aside', 'process_aside', __( 'NOTE（左カラム・**強調** 可）', 'fde-usachim' ), 4 );
		foreach ( [ 1, 2, 3, 4, 5 ] as $n ) {
			$top_fields[] = fde_acf_text( "process_step_{$n}_no",    "process_step_{$n}_no",    sprintf( __( 'ステップ %d — 番号', 'fde-usachim' ), $n ), '00 / 01 / 02 ...' );
			$top_fields[] = fde_acf_text( "process_step_{$n}_dur",   "process_step_{$n}_dur",   sprintf( __( 'ステップ %d — 期間', 'fde-usachim' ), $n ), 'Day 0 / Week 1–2 ...' );
			$top_fields[] = fde_acf_text( "process_step_{$n}_title", "process_step_{$n}_title", sprintf( __( 'ステップ %d — タイトル', 'fde-usachim' ), $n ) );
			$top_fields[] = fde_acf_textarea( "process_step_{$n}_desc", "process_step_{$n}_desc", sprintf( __( 'ステップ %d — 説明', 'fde-usachim' ), $n ), 3 );
		}

		// ---------- §06 Stack ----------
		$top_fields[] = fde_acf_tab( 'stack', __( '§06 Stack', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'stack_meta', 'stack_meta', __( 'Stack 見直し日', 'fde-usachim' ), 'REVIEWED 2026.04' );
		foreach ( [ 1, 2, 3, 4, 5, 6 ] as $n ) {
			$top_fields[] = fde_acf_text( "stack_g_{$n}_g",     "stack_g_{$n}_g",     sprintf( __( 'Stack %d — カテゴリ名', 'fde-usachim' ), $n ), 'AI / LLM' );
			$top_fields[] = fde_acf_text( "stack_g_{$n}_items", "stack_g_{$n}_items", sprintf( __( 'Stack %d — 項目（カンマ区切り）', 'fde-usachim' ), $n ), 'OpenAI, Anthropic, Local LLM, RAG, Eval' );
		}

		// ---------- §08 Contact ----------
		$top_fields[] = fde_acf_tab( 'contact', __( '§08 Contact', 'fde-usachim' ) );
		$top_fields[] = fde_acf_text( 'contact_meta', 'contact_meta', __( 'Contact メタ', 'fde-usachim' ), 'RESPONSE WITHIN 1 BIZ DAY' );
		$top_fields[] = fde_acf_text( 'contact_lead', 'contact_lead', __( 'Contact リード', 'fde-usachim' ), '初回60分はオンラインで無料です。' );
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
					fde_acf_text( 'brand_name', 'brand_name', __( '屋号（テキストロゴ）', 'fde-usachim' ), 'CHIM WORKS' ),
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
