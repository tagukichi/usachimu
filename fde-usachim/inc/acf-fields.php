<?php
/**
 * ACF field groups defined in code (CHIM WORKS).
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
 * Register field groups.
 */
add_action(
	'acf/init',
	static function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		// ============================================================
		// Works (case study) details
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_works_details',
				'title'    => __( '実績詳細', 'fde-usachim' ),
				'fields'   => [
					[
						'key'         => 'field_fde_industry',
						'label'       => __( '業界', 'fde-usachim' ),
						'name'        => 'industry',
						'type'        => 'text',
						'placeholder' => __( '基礎自治体 / SaaSスタートアップ など', 'fde-usachim' ),
					],
					[
						'key'          => 'field_fde_summary',
						'label'        => __( '概要', 'fde-usachim' ),
						'name'         => 'summary',
						'type'         => 'textarea',
						'rows'         => 4,
						'new_lines'    => '',
						'instructions' => __( '本文として実績スプレッドに表示。', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_headline',
						'label'       => __( 'ヘッドライン（KPIポスター大文字）', 'fde-usachim' ),
						'name'        => 'headline',
						'type'        => 'text',
						'placeholder' => __( '−85% / 8h → 30m など', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_headline_label',
						'label'       => __( 'ヘッドラインの説明', 'fde-usachim' ),
						'name'        => 'headline_label',
						'type'        => 'text',
						'placeholder' => __( '提案リードタイム / 月次経理作業 など', 'fde-usachim' ),
					],
					[
						'key'        => 'field_fde_kpi',
						'label'      => __( 'KPI（Key Result）', 'fde-usachim' ),
						'name'       => 'kpi',
						'type'       => 'repeater',
						'min'        => 0,
						'max'        => 4,
						'layout'     => 'table',
						'button_label' => __( 'KPI を追加', 'fde-usachim' ),
						'sub_fields' => [
							[ 'key' => 'field_fde_kpi_k', 'label' => '指標', 'name' => 'k', 'type' => 'text' ],
							[ 'key' => 'field_fde_kpi_v', 'label' => '値',   'name' => 'v', 'type' => 'text' ],
						],
					],
					[
						'key'          => 'field_fde_tags',
						'label'        => __( 'Stack タグ', 'fde-usachim' ),
						'name'         => 'tags',
						'type'         => 'text',
						'instructions' => __( 'カンマ区切り。例：LLM, Python, Slack Bot, Notion API', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_year',
						'label'       => __( '実施年', 'fde-usachim' ),
						'name'        => 'year',
						'type'        => 'text',
						'placeholder' => __( '2025 / 2024–25', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_scale',
						'label'       => __( '規模', 'fde-usachim' ),
						'name'        => 'scale',
						'type'        => 'text',
						'placeholder' => __( '〜300万円 / 〜800万円', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_role',
						'label'       => __( '体制', 'fde-usachim' ),
						'name'        => 'role',
						'type'        => 'text',
						'placeholder' => __( '一人 / 4ヶ月  ・  一人 + 内製2名', 'fde-usachim' ),
					],
					[
						'key'        => 'field_fde_external_url',
						'label'      => __( '外部リンク（任意）', 'fde-usachim' ),
						'name'       => 'external_url',
						'type'       => 'url',
					],
				],
				'location' => [
					[
						[ 'param' => 'post_type', 'operator' => '==', 'value' => 'works' ],
					],
				],
				'position'        => 'normal',
				'style'           => 'default',
				'label_placement' => 'top',
				'active'          => true,
			]
		);

		// ============================================================
		// Writing (post) details
		// ============================================================
		acf_add_local_field_group(
			[
				'key'      => 'group_fde_post_details',
				'title'    => __( '記事メタ', 'fde-usachim' ),
				'fields'   => [
					[
						'key'         => 'field_fde_post_tag_label',
						'label'       => __( 'タグ表示（カードに出る短い分類）', 'fde-usachim' ),
						'name'        => 'tag_label',
						'type'        => 'text',
						'placeholder' => __( 'AI / 評価', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_read_time',
						'label'       => __( '読了時間', 'fde-usachim' ),
						'name'        => 'read_time',
						'type'        => 'text',
						'placeholder' => __( '12 min', 'fde-usachim' ),
					],
				],
				'location' => [
					[
						[ 'param' => 'post_type', 'operator' => '==', 'value' => 'post' ],
					],
				],
				'position'        => 'side',
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
					// ---------- Tab: ブランド ----------
					[ 'key' => 'tab_brand', 'label' => __( 'ブランド', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_brand_name',
						'label'       => __( '屋号（テキストロゴ）', 'fde-usachim' ),
						'name'        => 'brand_name',
						'type'        => 'text',
						'placeholder' => 'CHIM WORKS',
					],
					[
						'key'         => 'field_fde_brand_subtitle',
						'label'       => __( '屋号サブ（mono の slash テキスト）', 'fde-usachim' ),
						'name'        => 'brand_subtitle',
						'type'        => 'text',
						'placeholder' => '// FORWARD DEPLOYED',
					],
					[
						'key'         => 'field_fde_cta_label',
						'label'       => __( 'ヘッダーCTA ラベル', 'fde-usachim' ),
						'name'        => 'cta_label',
						'type'        => 'text',
						'placeholder' => '相談を始める →',
					],

					// ---------- Tab: Hero ----------
					[ 'key' => 'tab_hero', 'label' => __( 'Hero', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_hero_eyebrow_l',
						'label'       => __( 'Hero 上左', 'fde-usachim' ),
						'name'        => 'hero_eyebrow_left',
						'type'        => 'text',
						'placeholder' => 'CHIM WORKS — INDEX',
					],
					[
						'key'         => 'field_fde_hero_eyebrow_r',
						'label'       => __( 'Hero 上右', 'fde-usachim' ),
						'name'        => 'hero_eyebrow_right',
						'type'        => 'text',
						'placeholder' => 'EST. 2021 · TOKYO, JP',
					],
					[
						'key'          => 'field_fde_hero_statement_l1',
						'label'        => __( 'Hero ステートメント 1行目', 'fde-usachim' ),
						'name'         => 'hero_statement_l1',
						'type'         => 'text',
						'placeholder'  => '書類で動くAIではなく、',
					],
					[
						'key'          => 'field_fde_hero_statement_l2_a',
						'label'        => __( 'Hero ステートメント 2行目（薄色部分）', 'fde-usachim' ),
						'name'         => 'hero_statement_l2_a',
						'type'         => 'text',
						'placeholder'  => '現場で動く',
					],
					[
						'key'          => 'field_fde_hero_statement_l2_b',
						'label'        => __( 'Hero ステートメント 2行目（強調部分）', 'fde-usachim' ),
						'name'         => 'hero_statement_l2_b',
						'type'         => 'text',
						'placeholder'  => 'AIを。',
					],
					[
						'key'          => 'field_fde_hero_lede',
						'label'        => __( 'Hero リード文', 'fde-usachim' ),
						'name'         => 'hero_lede',
						'type'         => 'textarea',
						'rows'         => 4,
						'new_lines'    => '',
						'instructions' => __( '**強調** で太字（白）にできます。', 'fde-usachim' ),
					],

					// Hero stats (SINCE / DELIVERED / NEXT SLOT)
					[
						'key'        => 'field_fde_hero_stats',
						'label'      => __( 'Hero 統計 3列', 'fde-usachim' ),
						'name'       => 'hero_stats',
						'type'       => 'repeater',
						'min'        => 0,
						'max'        => 3,
						'layout'     => 'table',
						'button_label' => __( '統計を追加', 'fde-usachim' ),
						'sub_fields' => [
							[ 'key' => 'field_fde_stat_k',   'label' => 'ラベル', 'name' => 'k',   'type' => 'text', 'placeholder' => 'SINCE' ],
							[ 'key' => 'field_fde_stat_v',   'label' => '値',     'name' => 'v',   'type' => 'text', 'placeholder' => '2021' ],
							[ 'key' => 'field_fde_stat_sub', 'label' => '注釈',   'name' => 'sub', 'type' => 'text', 'placeholder' => '個人事業として' ],
						],
					],
					[
						'key'         => 'field_fde_hero_cta_primary',
						'label'       => __( 'Hero CTA Primary ラベル', 'fde-usachim' ),
						'name'        => 'hero_cta_primary',
						'type'        => 'text',
						'placeholder' => '案件を相談する →',
					],
					[
						'key'         => 'field_fde_hero_cta_secondary',
						'label'       => __( 'Hero CTA Secondary ラベル', 'fde-usachim' ),
						'name'        => 'hero_cta_secondary',
						'type'        => 'text',
						'placeholder' => '実績を見る',
					],

					// Hero news は「カテゴリー：お知らせ（slug: news）」の最新投稿3件を自動取得します。
					// 投稿 → カテゴリーで「お知らせ」を作成し、投稿してください。
					// 1件目の post tag が大文字化されて NOTE / WRITING / CASE の位置に入ります。

					// ---------- Tab: §01 Why ----------
					[ 'key' => 'tab_why', 'label' => __( '§01 Why', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_why_title',
						'label'       => __( 'セクション タイトル', 'fde-usachim' ),
						'name'        => 'why_title',
						'type'        => 'text',
						'placeholder' => 'AIを味方につけ、次の10年に備える。',
					],
					[
						'key'          => 'field_fde_why_premise',
						'label'        => __( 'Premise（大文字引用）', 'fde-usachim' ),
						'name'         => 'why_premise',
						'type'         => 'textarea',
						'rows'         => 3,
						'new_lines'    => '',
						'instructions' => __( '**強調** で太字。', 'fde-usachim' ),
					],
					[
						'key'        => 'field_fde_why_pillars',
						'label'      => __( '3つの柱', 'fde-usachim' ),
						'name'       => 'why_pillars',
						'type'       => 'repeater',
						'min'        => 0,
						'max'        => 3,
						'layout'     => 'block',
						'sub_fields' => [
							[ 'key' => 'field_fde_pillar_no',   'label' => '見出し記号', 'name' => 'no',   'type' => 'text', 'placeholder' => '一' ],
							[ 'key' => 'field_fde_pillar_head', 'label' => '見出し',     'name' => 'head', 'type' => 'text' ],
							[ 'key' => 'field_fde_pillar_body', 'label' => '本文',       'name' => 'body', 'type' => 'textarea', 'rows' => 4, 'new_lines' => '' ],
						],
					],
					[
						'key'         => 'field_fde_why_dataflow_title',
						'label'       => __( 'Dataflow タイトル', 'fde-usachim' ),
						'name'        => 'why_dataflow_title',
						'type'        => 'text',
						'placeholder' => '散らばったデータを、現場の判断に届くまで。',
					],
					[
						'key'         => 'field_fde_why_dataflow_note',
						'label'       => __( 'Dataflow 説明', 'fde-usachim' ),
						'name'        => 'why_dataflow_note',
						'type'        => 'textarea',
						'rows'        => 3,
						'new_lines'   => '',
					],
					[
						'key'           => 'field_fde_why_dataflow_image',
						'label'         => __( 'Dataflow 画像（右側）', 'fde-usachim' ),
						'name'          => 'why_dataflow_image',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => __( 'アップロードすると、ASCIIダイアグラムの代わりにこの画像を表示します。推奨：横長、最大幅 1600px、PNG（背景透過）または JPG。', 'fde-usachim' ),
					],
					[
						'key'         => 'field_fde_why_dataflow_diagram',
						'label'       => __( 'Dataflow ダイアグラム（等幅プリ・画像未設定時のフォールバック）', 'fde-usachim' ),
						'name'        => 'why_dataflow_diagram',
						'type'        => 'textarea',
						'rows'        => 12,
						'new_lines'   => '',
					],

					// ---------- Tab: §02 About ----------
					[ 'key' => 'tab_about', 'label' => __( '§02 About', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'           => 'field_fde_about_portrait',
						'label'         => __( 'ポートレート画像', 'fde-usachim' ),
						'name'          => 'about_portrait',
						'type'          => 'image',
						'return_format' => 'array',
						'preview_size'  => 'medium',
					],
					[
						'key'         => 'field_fde_about_portrait_fig',
						'label'       => __( 'FIG ラベル', 'fde-usachim' ),
						'name'        => 'about_portrait_fig',
						'type'        => 'text',
						'placeholder' => 'FIG. 01 — CHIM',
					],
					[
						'key'        => 'field_fde_about_stats',
						'label'      => __( 'プロフィール統計（4項目）', 'fde-usachim' ),
						'name'       => 'about_stats',
						'type'       => 'repeater',
						'min'        => 0,
						'max'        => 4,
						'layout'     => 'table',
						'sub_fields' => [
							[ 'key' => 'field_fde_about_stat_k', 'label' => 'ラベル', 'name' => 'k', 'type' => 'text', 'placeholder' => 'SINCE' ],
							[ 'key' => 'field_fde_about_stat_v', 'label' => '値',     'name' => 'v', 'type' => 'text', 'placeholder' => '2021' ],
						],
					],
					[
						'key'         => 'field_fde_about_lead',
						'label'       => __( 'About リード（明朝大）', 'fde-usachim' ),
						'name'        => 'about_lead',
						'type'        => 'textarea',
						'rows'        => 3,
						'new_lines'   => '',
					],
					[
						'key'          => 'field_fde_about_body',
						'label'        => __( 'About 本文', 'fde-usachim' ),
						'name'         => 'about_body',
						'type'         => 'textarea',
						'rows'         => 8,
						'new_lines'    => '',
						'instructions' => __( '段落区切りは空行。**強調** で太字。', 'fde-usachim' ),
					],
					[
						'key'          => 'field_fde_about_tags',
						'label'        => __( 'About タグ', 'fde-usachim' ),
						'name'         => 'about_tags',
						'type'         => 'text',
						'instructions' => __( 'カンマ区切り。例：Forward Deployed, AI / LLM, Data Engineering, Solo, NDA OK', 'fde-usachim' ),
					],

					// ---------- Tab: §03 Services ----------
					[ 'key' => 'tab_services', 'label' => __( '§03 Services', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'        => 'field_fde_services_list',
						'label'      => __( 'サービス一覧', 'fde-usachim' ),
						'name'       => 'services_list',
						'type'       => 'repeater',
						'layout'     => 'block',
						'sub_fields' => [
							[ 'key' => 'field_fde_svc_no',    'label' => '番号',  'name' => 'no',    'type' => 'text', 'placeholder' => 'I' ],
							[ 'key' => 'field_fde_svc_title', 'label' => '名称',  'name' => 'title', 'type' => 'text' ],
							[ 'key' => 'field_fde_svc_meta',  'label' => 'メタ',  'name' => 'meta',  'type' => 'text', 'placeholder' => 'LLM / RAG / Eval' ],
							[ 'key' => 'field_fde_svc_desc',  'label' => '説明',  'name' => 'desc',  'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
						],
					],
					[
						'key'         => 'field_fde_services_note',
						'label'       => __( '契約に関する注記', 'fde-usachim' ),
						'name'        => 'services_note',
						'type'        => 'textarea',
						'rows'        => 2,
						'new_lines'   => '',
					],

					// ---------- Tab: §04 Process ----------
					[ 'key' => 'tab_process', 'label' => __( '§04 Process', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_process_meta',
						'label'       => __( 'メタ（最小〜典型〜最大）', 'fde-usachim' ),
						'name'        => 'process_meta',
						'type'        => 'text',
						'placeholder' => 'MIN 0w · TYP 10w · MAX 26w',
					],
					[
						'key'         => 'field_fde_process_aside',
						'label'       => __( 'NOTE（左カラム）', 'fde-usachim' ),
						'name'        => 'process_aside',
						'type'        => 'textarea',
						'rows'        => 4,
						'new_lines'   => '',
					],
					[
						'key'        => 'field_fde_process_steps',
						'label'      => __( 'Process ステップ', 'fde-usachim' ),
						'name'       => 'process_steps',
						'type'       => 'repeater',
						'layout'     => 'block',
						'sub_fields' => [
							[ 'key' => 'field_fde_step_no',    'label' => '番号',     'name' => 'no',    'type' => 'text', 'placeholder' => '00' ],
							[ 'key' => 'field_fde_step_dur',   'label' => '期間',     'name' => 'dur',   'type' => 'text', 'placeholder' => 'Day 0' ],
							[ 'key' => 'field_fde_step_title', 'label' => 'タイトル', 'name' => 'title', 'type' => 'text' ],
							[ 'key' => 'field_fde_step_desc',  'label' => '説明',     'name' => 'desc',  'type' => 'textarea', 'rows' => 3, 'new_lines' => '' ],
						],
					],

					// ---------- Tab: §05 Work ----------
					[ 'key' => 'tab_work', 'label' => __( '§05 Work', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_work_intro',
						'label'       => __( '実績セクション 注記', 'fde-usachim' ),
						'name'        => 'work_disclaimer',
						'type'        => 'text',
						'placeholder' => '※ クライアント名は伏せています。詳細は商談の場で。',
					],

					// ---------- Tab: §06 Stack ----------
					[ 'key' => 'tab_stack', 'label' => __( '§06 Stack', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_stack_meta',
						'label'       => __( 'Stack 見直し日', 'fde-usachim' ),
						'name'        => 'stack_meta',
						'type'        => 'text',
						'placeholder' => 'REVIEWED 2026.04',
					],
					[
						'key'        => 'field_fde_stack_groups',
						'label'      => __( 'Stack グループ', 'fde-usachim' ),
						'name'       => 'stack_groups',
						'type'       => 'repeater',
						'layout'     => 'block',
						'sub_fields' => [
							[ 'key' => 'field_fde_stack_g', 'label' => 'カテゴリ', 'name' => 'g', 'type' => 'text', 'placeholder' => 'AI / LLM' ],
							[ 'key' => 'field_fde_stack_items', 'label' => '項目（カンマ区切り）', 'name' => 'items', 'type' => 'text' ],
						],
					],

					// ---------- Tab: §08 Contact ----------
					[ 'key' => 'tab_contact', 'label' => __( '§08 Contact', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_contact_meta',
						'label'       => __( 'Contact メタ', 'fde-usachim' ),
						'name'        => 'contact_meta',
						'type'        => 'text',
						'placeholder' => 'RESPONSE WITHIN 1 BIZ DAY',
					],
					[
						'key'         => 'field_fde_contact_lead',
						'label'       => __( 'Contact リード', 'fde-usachim' ),
						'name'        => 'contact_lead',
						'type'        => 'text',
						'placeholder' => '初回60分はオンラインで無料です。',
					],
					[
						'key'         => 'field_fde_contact_note',
						'label'       => __( 'Contact 補足', 'fde-usachim' ),
						'name'        => 'contact_note',
						'type'        => 'textarea',
						'rows'        => 3,
						'new_lines'   => '',
					],
					[
						'key'         => 'field_fde_contact_email',
						'label'       => __( '通知先メール / 表示メール', 'fde-usachim' ),
						'name'        => 'contact_email',
						'type'        => 'email',
					],
					[
						'key'         => 'field_fde_sns_x_url',
						'label'       => __( 'X (Twitter) URL', 'fde-usachim' ),
						'name'        => 'sns_x_url',
						'type'        => 'url',
					],
					[
						'key'         => 'field_fde_sns_x_handle',
						'label'       => __( 'X ハンドル表示', 'fde-usachim' ),
						'name'        => 'sns_x_handle',
						'type'        => 'text',
						'placeholder' => '@chim_works',
					],
					[
						'key'         => 'field_fde_sns_facebook_url',
						'label'       => __( 'Facebook URL', 'fde-usachim' ),
						'name'        => 'sns_facebook_url',
						'type'        => 'url',
					],
					[
						'key'         => 'field_fde_cf7_shortcode',
						'label'       => __( 'CF7 ショートコード', 'fde-usachim' ),
						'name'        => 'cf7_shortcode',
						'type'        => 'text',
						'placeholder' => '[contact-form-7 id="123" title="お問い合わせ"]',
					],

					// ---------- Tab: Footer ----------
					[ 'key' => 'tab_footer', 'label' => __( 'Footer', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_footer_suffix',
						'label'       => __( '大ロゴ末尾（mono 小さく）', 'fde-usachim' ),
						'name'        => 'footer_suffix',
						'type'        => 'text',
						'placeholder' => '/fde',
					],
					[
						'key'         => 'field_fde_footer_meta_left',
						'label'       => __( 'Footer メタ 左', 'fde-usachim' ),
						'name'        => 'footer_meta_left',
						'type'        => 'textarea',
						'rows'        => 3,
						'new_lines'   => '',
					],
					[
						'key'         => 'field_fde_footer_meta_right',
						'label'       => __( 'Footer メタ 右', 'fde-usachim' ),
						'name'        => 'footer_meta_right',
						'type'        => 'textarea',
						'rows'        => 3,
						'new_lines'   => '',
					],

					// ---------- Tab: SEO ----------
					[ 'key' => 'tab_seo', 'label' => __( 'SEO / OGP', 'fde-usachim' ), 'name' => '', 'type' => 'tab' ],
					[
						'key'         => 'field_fde_site_description',
						'label'       => __( 'サイト説明文', 'fde-usachim' ),
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
						[ 'param' => 'options_page', 'operator' => '==', 'value' => 'fde-theme-settings' ],
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
