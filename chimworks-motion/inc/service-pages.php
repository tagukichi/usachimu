<?php
/**
 * サービス詳細ページ（WEB制作 / 業務効率化・DX支援 / システム開発 / AI研修・勉強会）。
 *
 * - 4 ページの定義（番号・スラッグ・名前・TOP の補足テキスト）をここに集約し、
 *   TOP の事業内容・会社概要・各ページのテンプレートが同じ定義を使う。
 * - 固定ページは管理画面を開いたときに一度だけ自動作成する（スラッグで
 *   page-{slug}.php が自動適用される）。
 * - 各ページの文言は、ページ編集画面の ACF で上書きできる。空欄なら既定文。
 * - お問い合わせは全ページ共通の Google フォーム 1 つ（URL は TOP ページの ACF）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 4 つのサービスの定義。並び順 = 表示順。
 *
 * @return array<string,array{no:string,slug:string,title:string,en:string,words:array<int,string>,note:string}>
 */
function fde_services(): array {
	return [
		'web'    => [
			'no'    => '01',
			'slug'  => 'web-design',
			'title' => 'WEB制作',
			'en'    => 'Web Design',
			'words' => [ 'LP', 'ホームページ', 'ECサイト', '会員サイト', '口コミサイト', 'ポータルサイト', 'WordPress' ],
			'note'  => 'これまでに200件以上のWebサイトを制作。コーポレートサイトやLPを、企画・デザインから公開後の運用まで手がけます。',
		],
		'dx'     => [
			'no'    => '02',
			'slug'  => 'dx',
			'title' => '業務効率化・DX支援',
			'en'    => 'DX Support',
			'words' => [ '業務の見直し', 'Excelの自動化', 'Microsoft 365', 'Dify', 'Claude', 'ChatGPT', 'ツール導入' ],
			'note'  => '業務の流れを整理し、ツールの導入や自動化で、日々の手間を減らします。',
		],
		'system' => [
			'no'    => '03',
			'slug'  => 'system',
			'title' => 'システム開発',
			'en'    => 'System Development',
			'words' => [ 'Next.js', 'React', 'TypeScript', 'Vite', 'Python', 'Supabase', 'Vercel', 'Azure', 'Docker' ],
			'note'  => '業務システムやWebアプリを、要件の整理から設計・開発・運用まで一貫して手がけます。生成AIを組み込んだ開発にも対応します。',
		],
		'ai'     => [
			'no'    => '04',
			'slug'  => 'ai-training',
			'title' => 'AI研修・勉強会',
			'en'    => 'AI Training',
			'words' => [ '生成AIの基礎', 'プロンプト', 'ワークショップ', 'ChatGPT', 'Claude', 'Copilot', '管理職向け' ],
			'note'  => '自治体・企業向けに、生成AIの基礎から業務での使い方までを学ぶ研修・勉強会を行っています。',
		],
	];
}

/**
 * サービスページの固定ページ ID（公開中のみ。無ければ 0）。
 */
function fde_service_page_id( string $key ): int {
	static $cache = [];
	if ( ! isset( $cache[ $key ] ) ) {
		$services      = fde_services();
		$page          = isset( $services[ $key ] ) ? get_page_by_path( $services[ $key ]['slug'] ) : null;
		$cache[ $key ] = ( $page instanceof WP_Post && 'publish' === $page->post_status ) ? (int) $page->ID : 0;
	}
	return $cache[ $key ];
}

/**
 * サービスページの URL（ページが無ければ空文字）。
 */
function fde_service_url( string $key ): string {
	$id = fde_service_page_id( $key );
	return $id ? (string) get_permalink( $id ) : '';
}

/**
 * 表示中のページの ACF 値。未入力・ACF 無効なら既定値。
 *
 * @param mixed $default
 * @return mixed
 */
function fde_page_field( string $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $key, (int) get_queried_object_id() );
	if ( null !== $value && '' !== $value && [] !== $value ) {
		return $value;
	}
	return $default;
}

/**
 * 「見出し｜説明」形式のテキストエリアを行ごとの配列にする。
 * 区切りは全角「｜」か半角「|」。空行は無視。
 *
 * @return array<int,array<int,string>>
 */
function fde_pipe_rows( string $text ): array {
	$rows = [];
	foreach ( preg_split( '/\r\n|\r|\n/u', $text ) ?: [] as $line ) {
		$line = trim( (string) $line );
		if ( '' === $line ) {
			continue;
		}
		$rows[] = array_map( 'trim', preg_split( '/[｜|]/u', $line ) ?: [ $line ] );
	}
	return $rows;
}

/**
 * お問い合わせ（Google フォーム）の URL。全サービスページ共通。
 * TOP ページの ACF が空なら、テーマに設定したフォームを使う。
 * 「事前入力の番号（entry.○○）」が設定されていれば、開いたページのサービス名を
 * 「お問い合わせの種類」にあらかじめ選んだ状態で開く。
 */
function fde_contact_form_url( string $service_key = '' ): string {
	$url = trim( (string) fde_field( 'contact_form_url', '' ) );
	if ( '' === $url ) {
		$url = 'https://docs.google.com/forms/d/1Z7TgEWtB7j-METOTrDfhCz1PswBeecEz5Yyl9PZ8XoY/viewform';
	}
	// 編集画面から写したときに付く余計なパラメータは外す
	$url = remove_query_arg( [ 'edit_requested', 'usp' ], $url );

	$entry    = preg_replace( '/[^0-9]/', '', (string) fde_field( 'contact_prefill_entry', '' ) );
	$services = fde_services();
	if ( '' !== $entry && isset( $services[ $service_key ] ) ) {
		$url = add_query_arg(
			[
				'usp'              => 'pp_url',
				'entry.' . $entry  => rawurlencode( $services[ $service_key ]['title'] ),
			],
			$url
		);
	}
	return esc_url_raw( $url );
}

/**
 * 「/web」で公開している制作事例のカスタム投稿タイプ名。
 * WEB制作ページの ACF で指定があればそれを、無ければ /web で公開されている投稿タイプを探す。
 */
function fde_works_post_type(): string {
	$forced = trim( (string) fde_page_field( 'web_works_post_type', '' ) );
	if ( '' !== $forced && post_type_exists( $forced ) ) {
		return $forced;
	}
	foreach ( get_post_types( [ 'public' => true, '_builtin' => false ], 'objects' ) as $type ) {
		$slug = is_array( $type->rewrite ) && isset( $type->rewrite['slug'] ) ? trim( (string) $type->rewrite['slug'], '/' ) : '';
		if ( 'web' === $slug || 'web' === $type->name || 'web' === $type->has_archive ) {
			return $type->name;
		}
	}
	return '';
}

/**
 * ツール・技術名から公式アイコン（assets/img/brands/*.svg）の URL を返す。
 * 名前の一部が一致すれば使う（例：「Microsoft 365」→ microsoft）。無ければ空文字。
 */
function fde_brand_icon( string $name ): string {
	$name = strtolower( $name );
	$map  = [
		'copilot'    => 'copilot',
		'microsoft'  => 'microsoft',
		'm365'       => 'microsoft',
		'excel'      => 'microsoft',
		'dify'       => 'dify',
		'claude'     => 'claude',
		'chatgpt'    => 'openai',
		'openai'     => 'openai',
		'next'       => 'nextjs',
		'react'      => 'react',
		'typescript' => 'typescript',
		'vite'       => 'vite',
		'python'     => 'python',
		'supabase'   => 'supabase',
		'vercel'     => 'vercel',
		'azure'      => 'azure',
		'docker'     => 'docker',
		'wordpress'  => 'wordpress',
	];
	foreach ( $map as $needle => $file ) {
		if ( false !== strpos( $name, $needle ) ) {
			return fde_asset_url( 'assets/img/brands/' . $file . '.svg' );
		}
	}
	return '';
}

/**
 * 見出しの言葉に合う線のアイコン（自作の SVG パス）。合うものが無ければ汎用の形。
 */
function fde_line_icon( string $label ): string {
	$icons = [
		'LP'       => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 7h6M9 11h6M9 15h3"/>',
		'ホームページ' => '<path d="M3 21h18M5 21V8l7-5 7 5v13"/><path d="M10 21v-5h4v5"/>',
		'EC'       => '<circle cx="9" cy="20" r="1.4"/><circle cx="17" cy="20" r="1.4"/><path d="M3 4h2l2.4 11h10.2L20 7H6.2"/>',
		'会員'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21c1.4-4 4.4-6 8-6s6.6 2 8 6"/>',
		'口コミ'     => '<path d="M4 5h16v11H9l-5 4z"/><path d="M12 8.2l.9 1.8 2 .3-1.4 1.4.3 2-1.8-.9-1.8.9.3-2-1.4-1.4 2-.3z"/>',
		'ポータル'   => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.6 2.6 3.8 5.6 3.8 9s-1.2 6.4-3.8 9c-2.6-2.6-3.8-5.6-3.8-9S9.4 5.6 12 3z"/>',
		'業務システム' => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4M7 9h4M7 12h7"/>',
		'Webアプリ'  => '<rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>',
		'生成AI'    => '<path d="M12 3l1.8 4.6L18.5 9.5l-4.7 1.9L12 16l-1.8-4.6L5.5 9.5l4.7-1.9z"/><path d="M18 15l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8z"/>',
		'API'      => '<path d="M8 8l-4 4 4 4M16 8l4 4-4 4M13.5 5l-3 14"/>',
		'基礎'      => '<path d="M4 6l8-3 8 3-8 3z"/><path d="M6 8v5c0 1.7 2.7 3 6 3s6-1.3 6-3V8"/>',
		'プロンプト'  => '<path d="M4 17l6-6-6-6M12 19h8"/>',
		'ワークショップ' => '<circle cx="8" cy="8" r="3"/><circle cx="16" cy="8" r="3"/><path d="M3 20c.8-3 2.8-5 5-5s4.2 2 5 5M11 20c.8-3 2.8-5 5-5s4.2 2 5 5"/>',
		'使い分け'   => '<path d="M7 4v16M17 4v16M4 8h6M14 16h6"/>',
		'管理職'    => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V4h6v3M3 12h18"/>',
		'ツール'    => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.4-.6-.6-2.4z"/>',
		'環境'      => '<path d="M12 3l8 3v6c0 4.6-3.4 8-8 9-4.6-1-8-4.4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
		'受講'      => '<circle cx="12" cy="7" r="3.2"/><path d="M5 21v-1.5C5 16.5 8 14 12 14s7 2.5 7 5.5V21"/>',
	];
	$path = '<circle cx="12" cy="12" r="8"/><path d="M12 8v8M8 12h8"/>';
	foreach ( $icons as $needle => $svg ) {
		if ( false !== mb_strpos( $label, $needle ) ) {
			$path = $svg;
			break;
		}
	}
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

/**
 * ACF の画像フィールド（返り値は配列）から URL と alt を取り出す。
 *
 * @param mixed $image
 * @return array{url:string,alt:string,w:int,h:int}
 */
function fde_image_data( $image ): array {
	if ( is_array( $image ) && ! empty( $image['url'] ) ) {
		$size = $image['sizes']['large'] ?? $image['url'];
		return [
			'url' => (string) $size,
			'alt' => (string) ( $image['alt'] ?? '' ),
			'w'   => (int) ( $image['sizes']['large-width'] ?? $image['width'] ?? 0 ),
			'h'   => (int) ( $image['sizes']['large-height'] ?? $image['height'] ?? 0 ),
		];
	}
	if ( is_numeric( $image ) && function_exists( 'wp_get_attachment_image_src' ) ) {
		$src = wp_get_attachment_image_src( (int) $image, 'large' );
		if ( $src ) {
			return [ 'url' => (string) $src[0], 'alt' => '', 'w' => (int) $src[1], 'h' => (int) $src[2] ];
		}
	}
	return [ 'url' => '', 'alt' => '', 'w' => 0, 'h' => 0 ];
}

/**
 * サービスの固定ページを一度だけ自動作成する（同じスラッグのページがあれば作らない）。
 */
add_action(
	'admin_init',
	static function (): void {
		if ( get_option( 'fde_service_pages_created' ) || ! current_user_can( 'publish_pages' ) ) {
			return;
		}
		foreach ( fde_services() as $service ) {
			if ( get_page_by_path( $service['slug'] ) ) {
				continue;
			}
			wp_insert_post(
				[
					'post_type'     => 'page',
					'post_status'   => 'publish',
					'post_title'    => $service['title'],
					'post_name'     => $service['slug'],
					'post_content'  => '',
					'page_template' => 'page-' . $service['slug'] . '.php',
				]
			);
		}
		update_option( 'fde_service_pages_created', 1, false );
	}
);

/**
 * 各サービスページの既定文（ACF が空欄のときに表示）。
 * ACF の入力欄にも「空欄ならこの文」として同じ内容を案内する。
 *
 * @return array<string,string>
 */
function fde_service_defaults(): array {
	return [
		// ---- WEB制作 ----
		'web_catch'      => "目的に合わせて、\n{{伝わるWebサイト}}を\nつくります。",
		'web_points'     => "制作実績200件以上\n企画から運用まで一貫対応\nスマホ対応\n公開後の更新もサポート",
		'web_lead'       => "これまでに200件以上のWebサイトを制作してきました。\n目的やご予算に合わせて、企画・デザインから開発、公開後の運用まで一貫して手がけます。",
		'web_types'      => implode(
			"\n",
			[
				'LP（ランディングページ）｜商品やサービスの魅力を1ページで伝え、問い合わせや申し込みにつなげるページ。',
				'ホームページ（コーポレートサイト）｜会社・店舗・団体の信頼感を伝える、名刺代わりのWebサイト。',
				'ECサイト｜商品の掲載から決済までをオンラインで完結できるショップサイト。',
				'会員サイト｜ログインした会員だけが見られるコンテンツや、マイページがあるサイト。',
				'口コミ・レビューサイト｜利用者が口コミを投稿・閲覧でき、比較や検索ができるサイト。',
				'ポータルサイト｜地域や業界の情報を集め、検索・掲載ができる情報サイト。',
			]
		),
		'web_prices'     => implode(
			"\n",
			[
				'LP型｜20万円〜｜1ページ完結のランディングページ',
				'ホームページ型｜35万円〜｜会社やサービスを紹介する、複数ページのサイト',
				'ポータルサイト・会員サイト｜60万円〜｜ログイン・投稿・検索などの機能があるサイト',
			]
		),
		'web_price_note' => 'ページ数や機能、素材の有無によって変わります。ECサイトや口コミサイトなども、内容をうかがってお見積もりします。',

		// ---- 業務効率化・DX支援 ----
		'dx_catch'       => "毎日の手作業を、\n{{自動化}}で\nもっと身軽に。",
		'dx_points'      => "Excel・紙の作業を自動化\n生成AIの業務活用\nMicrosoft 365に対応\n小さく始めて定着まで",
		'dx_lead'        => "業務の流れを整理し、ツールの導入や自動化で、日々の手間を減らします。\n「何から始めればいいか分からない」という段階からご相談いただけます。",
		'dx_pillar_1'    => "業務の見直し｜いまの業務の流れを一緒に洗い出し、ムダや手戻り、特定の人に頼っている作業を見える化します。\n業務フローの整理・見える化\n紙や手作業の洗い出し\n改善の優先順位づけと計画づくり",
		'dx_pillar_2'    => "自動化｜繰り返しの作業は、Excelや生成AIで自動化します。小さく始めて、効果を見ながら広げます。\nExcelの集計・転記の自動化（マクロ・VBA）\nPower Automateによる定型作業の自動化\n生成AIによる文章作成・要約・データ整理",
		'dx_pillar_3'    => "ツール導入支援｜目的に合ったツールを選び、設定から社内での定着までを支援します。\nツールの選定・比較\n初期設定・既存データの移行\nマニュアル作成・社内向けの説明会",
		'dx_examples'    => implode(
			"\n",
			[
				'毎月のExcel集計とレポート作成を自動化',
				'複数のファイルからの転記作業を自動化',
				'社内の問い合わせにAIチャットで回答（Dify）',
				'会議の議事録や報告書の下書きを生成AIで作成',
				'紙やPDFの書類をデータにして一覧に整理',
			]
		),
		'dx_tools'       => implode(
			"\n",
			[
				'Microsoft 365｜Excel・Teams・SharePoint・Power Automate など',
				'Dify｜社内向けのAIチャットや業務アプリをノーコードで構築',
				'Claude｜長い文書の読み込み・要約や、文章作成が得意な生成AI',
				'ChatGPT｜幅広い業務に使える、代表的な生成AI',
			]
		),
		'dx_steps'       => implode(
			"\n",
			[
				'ヒアリング｜困っていることや、いまの業務の進め方をうかがいます。',
				'業務の整理｜業務の流れを見える化し、改善するポイントを決めます。',
				'改善・自動化｜ツールの導入や自動化を、小さく始めて進めます。',
				'定着の支援｜マニュアル作成や説明会で、社内に根づくまで伴走します。',
			]
		),

		// ---- システム開発 ----
		'sys_catch'      => "業務にフィットする\n{{システム}}を、\n企画から運用まで。",
		'sys_points'     => "要件の整理から一貫対応\n生成AIの組み込み\nモダンな技術スタック\n公開後の保守・改善",
		'sys_lead'       => "業務システムやWebアプリを、要件の整理から設計・開発・運用まで一貫して手がけます。\n生成AIを組み込んだ開発にも対応します。",
		'sys_products'   => implode(
			"\n",
			[
				'業務システム｜顧客管理・案件管理・予約管理など、業務に合わせてつくるシステム。',
				'Webアプリ・SaaS｜ブラウザやスマホで使える、サービス向けのアプリケーション。',
				'生成AIを組み込んだアプリ｜文章の作成・要約や、書類の読み取りなどをAIで行うアプリ。',
				'API・データ連携｜外部サービスや既存のシステム、公的データとの連携。',
			]
		),
		'sys_stack'      => implode(
			"\n",
			[
				'フロントエンド｜Next.js、React、TypeScript、Vite',
				'バックエンド・データベース｜Python、Supabase',
				'インフラ・クラウド｜Vercel、Azure、Docker',
				'CMS｜WordPress',
			]
		),
		'sys_steps'      => implode(
			"\n",
			[
				'要件の整理｜目的と業務の流れをうかがい、必要な機能を一緒に整理します。',
				'設計｜画面やデータの構成を設計し、完成イメージを共有します。',
				'開発｜小さくつくって確認しながら、段階的に開発します。',
				'運用・改善｜公開後の保守や、使いながらの改善にも対応します。',
			]
		),
		'sys_product_lead' => '自社でも、不動産調査アプリ「調速（チョーハヤ）」を開発・提供しています。',

		// ---- AI研修・勉強会 ----
		'ai_catch'       => "生成AIを、\n{{現場で使える力}}に\n変える研修。",
		'ai_points'      => "自治体での研修実績\nツール・環境に合わせて調整\n手を動かすハンズオン\n対面・オンライン対応",
		'ai_lead'        => "自治体・企業向けに、生成AIの基礎から業務での使い方までを学ぶ研修・勉強会を行っています。\n組織で使っているツールや環境に合わせて、内容を組み立てます。",
		'ai_record'      => '都内の自治体で、職員の方向けの生成AI研修を複数回実施しています。実際の業務に近い題材を使い、手を動かしながら学べる内容にしています。',
		'ai_menu'        => implode(
			"\n",
			[
				'生成AIの基礎｜生成AIで何ができるのかを、注意点やリスクも含めて基本から学びます。',
				'プロンプトの書き方｜AIに意図を正しく伝えるための指示の出し方を、実習で身につけます。',
				'業務での活用ワークショップ｜実際の業務を題材に、資料作成や要約などにAIを使って取り組みます。',
				'ChatGPT・Claude・Copilotの使い分け｜ツールごとの得意なことを比べ、目的に合った使い分けを学びます。',
				'管理職向け講座｜導入の進め方やルールづくり、リスク管理など、組織で活用するためのポイントを学びます。',
			]
		),
		'ai_flex'        => implode(
			"\n",
			[
				'お使いのツールに合わせて｜Microsoft 365 Copilot・ChatGPT・Claudeなど、組織で導入しているツールを使った内容にします。',
				'環境やルールに合わせて｜自治体の三層分離など、ネットワーク環境や情報セキュリティのルールに沿った内容にします。',
				'受講する方に合わせて｜人数やITの得意・不得意、時間に合わせて、講義・ハンズオン、対面・オンラインを組み合わせます。',
			]
		),
		'ai_price_note'  => '研修の内容・時間・人数に合わせて、別途お見積もりします。',
	];
}

/**
 * サービスページの文言（ACF → 既定文）。
 */
function fde_service_text( string $key ): string {
	$defaults = fde_service_defaults();
	return trim( (string) fde_page_field( $key, $defaults[ $key ] ?? '' ) );
}

/**
 * サービスページ用 ACF フィールド群を登録する。
 */
add_action(
	'acf/init',
	static function (): void {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		$d    = fde_service_defaults();
		$pipe = '1行に1項目。「見出し｜説明」のように全角「｜」で区切ります。空欄なら下の既定文を表示します。';

		$field = static function ( string $name, string $label, string $type = 'textarea', string $help = '' ) use ( $d ): array {
			$f = [
				'key'          => 'field_fde_' . $name,
				'label'        => $label,
				'name'         => $name,
				'type'         => $type,
				'instructions' => $help . ( isset( $d[ $name ] ) ? "\n【既定文】\n" . $d[ $name ] : '' ),
			];
			if ( 'textarea' === $type ) {
				$f['rows']      = max( 3, substr_count( $d[ $name ] ?? '', "\n" ) + 2 );
				$f['new_lines'] = '';
			}
			return $f;
		};

		$image = static function ( string $name, string $label, string $help = '' ): array {
			return [
				'key'           => 'field_fde_' . $name,
				'label'         => $label,
				'name'          => $name,
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'library'       => 'all',
				'instructions'  => $help,
			];
		};
		$hero_help = '任意。設定するとページ上部の右側に表示します（横長 4:3 くらいがきれいに収まります）。未設定ならテーマのイラストを表示します。';

		$ai_photos = [];
		foreach ( range( 1, 6 ) as $n ) {
			$ai_photos[] = $image( "ai_photo_{$n}", sprintf( '研修の様子 — 写真 %d', $n ), 1 === $n ? '任意。設定した写真だけを「研修の様子」として並べます（1枚目は大きく表示）。顔が写る場合は掲載の許可をとってください。' : '' );
			$ai_photos[] = $field( "ai_photo_{$n}_cap", sprintf( '写真 %d のキャプション（任意）', $n ), 'text' );
		}

		$groups = [
			'web'    => [
				$image( 'web_hero_image', 'メイン画像', $hero_help ),
				$field( 'web_catch', 'キャッチコピー（ページ上部の大きな見出し。{{ }} で囲むとグラデーションで強調。改行はそのまま反映）' ),
				$field( 'web_points', 'キャッチコピー下のチェック項目（1行に1つ。4つくらいがおすすめ）' ),
				$field( 'web_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'web_types', '対応できるWebサイトの種類', 'textarea', $pipe ),
				$field( 'web_prices', '料金表', 'textarea', '1行に1プラン。「プラン名｜料金｜説明」の順に全角「｜」で区切ります。' ),
				$field( 'web_price_note', '料金表の下の注記' ),
				$field( 'web_works_count', '制作事例の表示件数（空欄なら 6）', 'number' ),
				$field( 'web_works_post_type', '制作事例の投稿タイプ名（通常は空欄。/web の投稿タイプを自動で使います）', 'text' ),
			],
			'dx'     => [
				$image( 'dx_hero_image', 'メイン画像', $hero_help ),
				$field( 'dx_catch', 'キャッチコピー（ページ上部の大きな見出し。{{ }} で囲むとグラデーションで強調。改行はそのまま反映）' ),
				$field( 'dx_points', 'キャッチコピー下のチェック項目（1行に1つ。4つくらいがおすすめ）' ),
				$field( 'dx_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'dx_pillar_1', '支援内容 1', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_pillar_2', '支援内容 2', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_pillar_3', '支援内容 3', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_examples', '自動化の例（1行に1つ）' ),
				$field( 'dx_tools', '対応ツール', 'textarea', $pipe ),
				$field( 'dx_steps', '進め方', 'textarea', $pipe ),
			],
			'system' => [
				$image( 'sys_hero_image', 'メイン画像', $hero_help ),
				$field( 'sys_catch', 'キャッチコピー（ページ上部の大きな見出し。{{ }} で囲むとグラデーションで強調。改行はそのまま反映）' ),
				$field( 'sys_points', 'キャッチコピー下のチェック項目（1行に1つ。4つくらいがおすすめ）' ),
				$field( 'sys_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'sys_products', '開発できるもの', 'textarea', $pipe ),
				$field( 'sys_stack', '技術スタック', 'textarea', '1行に1分類。「分類｜技術、技術、…」のように書きます。' ),
				$field( 'sys_steps', '開発の進め方', 'textarea', $pipe ),
				$field( 'sys_product_lead', '自社サービス（調速）の上の一文' ),
			],
			'ai'     => array_merge( [
				$image( 'ai_hero_image', 'メイン画像', $hero_help ),
				$field( 'ai_catch', 'キャッチコピー（ページ上部の大きな見出し。{{ }} で囲むとグラデーションで強調。改行はそのまま反映）' ),
				$field( 'ai_points', 'キャッチコピー下のチェック項目（1行に1つ。4つくらいがおすすめ）' ),
				$field( 'ai_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'ai_record', 'これまでの実績' ),
				$field( 'ai_record_url', '実績を紹介したブログ記事の URL（任意。入れるとリンクを表示）', 'url' ),
			], $ai_photos, [
				$field( 'ai_menu', '研修メニュー', 'textarea', $pipe ),
				$field( 'ai_flex', '環境に合わせた対応', 'textarea', $pipe ),
				$field( 'ai_price_note', '料金についての一文' ),
			] ),
		];

		foreach ( fde_services() as $key => $service ) {
			$template = 'page-' . $service['slug'] . '.php';
			$location = [ [ [ 'param' => 'page_template', 'operator' => '==', 'value' => $template ] ] ];
			$page_id  = fde_service_page_id( $key );
			if ( $page_id ) {
				// テンプレート未選択でも、このスラッグのページなら表示する
				$location[] = [ [ 'param' => 'page', 'operator' => '==', 'value' => (string) $page_id ] ];
			}
			acf_add_local_field_group(
				[
					'key'      => 'group_fde_service_' . $key,
					'title'    => sprintf( 'サービスページ — %s', $service['title'] ),
					'fields'   => $groups[ $key ],
					'location' => $location,
					'position' => 'acf_after_title',
				]
			);
		}
	}
);

/* ------------------------------------------------------------------
 * サービスページの部品（テンプレートから呼ぶ）
 * ---------------------------------------------------------------- */

/**
 * セクション見出し（TOP と同じ「番号 + 英字見出し + 日本語」）。
 */
function fde_svp_head( string $en, string $jp ): void {
	?>
	<header class="sec-head">
		<div class="sec-head__l">
			<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
			<h2 class="sec-head__title"><?php echo esc_html( $en ); ?></h2>
		</div>
		<span class="sec-head__meta"><?php echo esc_html( $jp ); ?></span>
	</header>
	<?php
}

/**
 * 「見出し｜説明」のカード一覧。
 * $icon … 'line'＝見出しに合う線のアイコン、'brand'＝ツールの公式アイコン、''＝なし。
 *
 * @param array<int,array<int,string>> $rows
 */
function fde_svp_cards( array $rows, string $modifier = '', string $icon = 'line' ): void {
	if ( ! $rows ) {
		return;
	}
	?>
	<div class="svp-cards<?php echo $modifier ? ' svp-cards--' . esc_attr( $modifier ) : ''; ?>">
		<?php foreach ( $rows as $i => $row ) : ?>
			<?php $logo = 'brand' === $icon ? fde_brand_icon( $row[0] ) : ''; ?>
			<article class="svp-card">
				<div class="svp-card__top">
					<?php if ( 'brand' === $icon ) : ?>
						<span class="svp-card__logo">
							<?php if ( $logo ) : ?>
								<img src="<?php echo esc_url( $logo ); ?>" alt="" width="40" height="40" loading="lazy">
							<?php else : ?>
								<span class="svp-card__initial"><?php echo esc_html( mb_substr( $row[0], 0, 1 ) ); ?></span>
							<?php endif; ?>
						</span>
					<?php elseif ( 'line' === $icon ) : ?>
						<span class="svp-card__icon"><?php echo fde_line_icon( $row[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 固定の SVG ?></span>
					<?php endif; ?>
					<span class="svp-card__idx mono"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				</div>
				<h3 class="svp-card__title"><?php echo esc_html( $row[0] ); ?></h3>
				<?php if ( ! empty( $row[1] ) ) : ?>
					<p class="svp-card__text"><?php echo esc_html( $row[1] ); ?></p>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * 番号付きの進め方（「見出し｜説明」）。
 *
 * @param array<int,array<int,string>> $rows
 */
function fde_svp_steps( array $rows ): void {
	if ( ! $rows ) {
		return;
	}
	?>
	<ol class="svp-steps">
		<?php foreach ( $rows as $i => $row ) : ?>
			<li class="svp-step">
				<span class="svp-step__no mono"><?php echo esc_html( sprintf( 'STEP %02d', $i + 1 ) ); ?></span>
				<h3 class="svp-step__title"><?php echo esc_html( $row[0] ); ?></h3>
				<?php if ( ! empty( $row[1] ) ) : ?>
					<p class="svp-step__text"><?php echo esc_html( $row[1] ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php
}

/**
 * チェック付きの箇条書き。
 *
 * @param array<int,string> $items
 */
function fde_svp_checks( array $items, string $modifier = '' ): void {
	$items = array_values( array_filter( array_map( 'trim', $items ), 'strlen' ) );
	if ( ! $items ) {
		return;
	}
	?>
	<ul class="svp-checks<?php echo $modifier ? ' svp-checks--' . esc_attr( $modifier ) : ''; ?>">
		<?php foreach ( $items as $item ) : ?>
			<li>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
				<span><?php echo esc_html( $item ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * 行ごとのテキストを配列に（空行は除く）。
 *
 * @return array<int,string>
 */
function fde_svp_lines( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/u', $text ) ?: [] ), 'strlen' ) );
}

/**
 * {{強調}} をグラデーション文字の span に、改行を <br> に変換する。
 */
function fde_svp_rich( string $text ): string {
	$out = esc_html( $text );
	$out = preg_replace( '/\{\{(.+?)\}\}/u', '<span class="svp-accent">$1</span>', $out );
	return nl2br( (string) $out );
}

/**
 * ページ上部（調速の LP の FV と同じ構成・動き）。
 * 左：サービス名・キャッチコピー・リード文・お問い合わせへのボタン・チェック項目
 * 右：メイン画像（ACF）か、サービスごとの画面イメージのアニメーション
 * 下：サービスに関係する言葉（ロゴ付き）が流れる帯
 */
function fde_svp_hero( string $key, string $lead ): void {
	$service = fde_services()[ $key ];
	$prefix  = 'system' === $key ? 'sys' : $key;
	$image   = fde_image_data( fde_page_field( $prefix . '_hero_image', null ) );
	$catch   = fde_service_text( $prefix . '_catch' );
	$points  = fde_svp_lines( fde_service_text( $prefix . '_points' ) );
	$check   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';
	?>
	<div class="svp-progress" aria-hidden="true" data-progress><span></span></div>
	<header class="svp-hero svp-hero--<?php echo esc_attr( $key ); ?>">
		<div class="svp-hero__bg" aria-hidden="true">
			<span class="svp-orb svp-orb--1"></span>
			<span class="svp-orb svp-orb--2"></span>
			<span class="svp-orb svp-orb--3"></span>
		</div>
		<div class="svp-hero__inner">
			<div class="svp-hero__copy svp-anim">
				<p class="svp-hero__eyebrow"><span class="svp-hero__no mono">SERVICE <?php echo esc_html( $service['no'] ); ?></span><span class="svp-hero__en mono"><?php echo esc_html( $service['en'] ); ?></span></p>
				<h1 class="svp-hero__title">
					<?php // 「・」の後ろでだけ折り返す（「業務効率化・／DX支援」） ?>
					<span class="svp-hero__name"><?php echo str_replace( '・', '・<wbr>', esc_html( get_the_title() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></span>
					<?php if ( '' !== $catch ) : ?>
						<span class="svp-hero__catch"><?php echo fde_svp_rich( $catch ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></span>
					<?php endif; ?>
				</h1>
				<?php if ( '' !== $lead ) : ?>
					<p class="svp-hero__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== fde_contact_form_url() ) : ?>
					<div class="svp-hero__cta">
						<a class="svp-btn" href="#contact">お問い合わせはこちら</a>
					</div>
				<?php endif; ?>
				<?php if ( $points ) : ?>
					<ul class="svp-hero__points">
						<?php foreach ( $points as $point ) : ?>
							<li><?php echo $check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 固定の SVG ?><?php echo esc_html( $point ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<div class="svp-hero__visual svp-anim-visual">
				<?php if ( '' !== $image['url'] ) : ?>
					<img class="svp-hero__img" src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="eager">
				<?php else : ?>
					<?php fde_svp_mock( $key ); ?>
					<p class="svp-hero__cap">※ 画面はイメージです。</p>
				<?php endif; ?>
			</div>
		</div>
	</header>
	<div class="svp-band" aria-hidden="true">
		<div class="svp-band__track">
			<?php for ( $loop = 0; $loop < 2; $loop++ ) : ?>
				<?php foreach ( $service['words'] as $word ) : ?>
					<?php $logo = fde_brand_icon( $word ); ?>
					<span class="svp-band__item">
						<?php if ( $logo ) : ?>
							<img src="<?php echo esc_url( $logo ); ?>" alt="" width="20" height="20">
						<?php else : ?>
							<i></i>
						<?php endif; ?>
						<?php echo esc_html( $word ); ?>
					</span>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
	<?php
}

/**
 * ページ上部の画面イメージ（HTML と CSS だけで動く。16 秒で一巡）。
 * 調速の LP の製品モックと同じ「PC の画面 + 手前にスマホ」の組み方。
 */
function fde_svp_mock( string $key ): void {
	$icon = static function ( string $name ): string {
		return esc_url( fde_brand_icon( $name ) );
	};
	$urls = [ 'web' => 'your-company.jp', 'dx' => '月次集計.xlsx', 'system' => 'admin.your-app.jp', 'ai' => '生成AI研修 ― 第1回' ];
	?>
	<div class="svm svm--<?php echo esc_attr( $key ); ?>" aria-hidden="true">
		<div class="svm-win">
			<div class="svm-bar"><i></i><i></i><i></i><span class="svm-url"><?php echo esc_html( $urls[ $key ] ?? '' ); ?></span></div>
			<div class="svm-body">
				<?php if ( 'web' === $key ) : ?>
					<div class="svm-tool">
						<span class="svm-tabs"><b>PC</b><span>SP</span></span>
						<span class="svm-pub">公開する</span>
					</div>
					<div class="svm-canvas">
						<div class="svm-site-head"><span class="svm-logo-dot"></span><span class="svm-sk w20"></span><span class="svm-nav"><i></i><i></i><i></i></span></div>
						<div class="svm-site-hero"><b>Your Brand</b><span class="svm-sk w50 is-light"></span><span class="svm-site-btn"></span></div>
						<div class="svm-site-cards"><i></i><i></i><i></i></div>
					</div>
					<div class="svm-toast">✓ サイトを公開しました</div>
				<?php elseif ( 'dx' === $key ) : ?>
					<div class="svm-tool">
						<span class="svm-file"><img src="<?php echo $icon( 'excel' ); // phpcs:ignore ?>" alt="">売上集計</span>
						<span class="svm-run">▶ 自動化を実行</span>
					</div>
					<div class="svm-sheet">
						<div class="svm-row svm-row--th"><span>日付</span><span>担当</span><span>件数</span><span>売上</span></div>
						<?php for ( $r = 1; $r <= 6; $r++ ) : ?>
							<div class="svm-row svm-row--<?php echo (int) $r; ?>"><i></i><i></i><i></i><i class="is-num"></i></div>
						<?php endfor; ?>
					</div>
					<div class="svm-status"><span class="svm-status__run">処理中…</span><span class="svm-status__done">✓ 完了</span></div>
					<div class="svm-chart"><b>月次レポート</b><span class="svm-bars"><i></i><i></i><i></i><i></i><i></i></span></div>
				<?php elseif ( 'system' === $key ) : ?>
					<div class="svm-app">
						<div class="svm-side"><span class="svm-logo-dot"></span><i class="svm-side__active"></i><i></i><i></i><i></i><i></i></div>
						<div class="svm-main">
							<div class="svm-kpis"><span><b>顧客</b><i></i></span><span><b>案件</b><i></i></span><span><b>売上</b><i></i></span></div>
							<div class="svm-graph"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
							<div class="svm-table"><i></i><i></i><i></i></div>
						</div>
					</div>
					<div class="svm-toast">✓ 保存しました</div>
				<?php else : ?>
					<div class="svm-room">
						<div class="svm-screen">
							<div class="svm-sl svm-sl--1"><b>生成AIの基礎</b><span class="svm-sk w80"></span><span class="svm-sk w60"></span><span class="svm-sk w70"></span></div>
							<div class="svm-sl svm-sl--2"><b>プロンプトのコツ</b><span class="svm-formula"><em>役割</em>＋<em>目的</em>＋<em>条件</em></span><span class="svm-sk w70"></span></div>
							<div class="svm-sl svm-sl--3"><b>業務で使ってみよう</b><span class="svm-mini"><i class="me"></i><i class="ai"></i><i class="me short"></i></span></div>
							<div class="svm-sl svm-sl--4"><b>今日のまとめ</b><span class="svm-done-list"><i>✓</i><span class="svm-sk w70"></span><i>✓</i><span class="svm-sk w60"></span><i>✓</i><span class="svm-sk w80"></span></span></div>
						</div>
						<div class="svm-speaker">
							<div class="svm-say">
								<span class="svm-say__t svm-say__t--1">まずは、生成AIで何ができるか見てみましょう</span>
								<span class="svm-say__t svm-say__t--2">指示は「役割・目的・条件」を具体的に</span>
								<span class="svm-say__t svm-say__t--3">では、実際の議事録で試してみましょう</span>
								<span class="svm-say__t svm-say__t--4">明日から使えるポイントをおさらいします</span>
							</div>
							<div class="svm-person">
								<span class="svm-person__arm"></span>
								<span class="svm-person__body"></span>
								<span class="svm-person__head"><i class="svm-person__mouth"></i></span>
								<span class="svm-wave"><i></i><i></i></span>
							</div>
						</div>
						<div class="svm-audience">
							<span class="svm-head"></span><span class="svm-head"></span>
							<span class="svm-head svm-head--ask"><i class="svm-hand"></i><b class="svm-q">質問です！</b></span>
							<span class="svm-head"></span><span class="svm-head"></span>
						</div>
					</div>
				<?php endif; ?>
				<?php if ( 'ai' !== $key ) : ?>
					<svg class="svm-cursor" viewBox="0 0 24 24"><path d="M5 3l14 8-6.2 1.6L10 19z"/></svg>
				<?php endif; ?>
			</div>
		</div>

		<div class="svm-phone">
			<div class="svm-phone__notch"></div>
			<div class="svm-phone__screen">
				<?php if ( 'web' === $key ) : ?>
					<span class="svm-phone__app">スマホ表示</span>
					<span class="svm-ph-site">
						<span class="svm-ph-head"><i></i><i></i></span>
						<span class="svm-phone__hero"><b>Your Brand</b></span>
						<span class="svm-sk w80"></span><span class="svm-sk w60"></span>
						<span class="svm-phone__card"></span><span class="svm-phone__card"></span><span class="svm-phone__card"></span>
					</span>
					<span class="svm-ph-badge">✓ 公開中</span>
				<?php elseif ( 'dx' === $key ) : ?>
					<span class="svm-phone__app"><img src="<?php echo $icon( 'microsoft' ); // phpcs:ignore ?>" alt="">Teams</span>
					<span class="svm-bub svm-bub--1">今月の売上集計、お願いします</span>
					<span class="svm-bub svm-bub--me svm-bub--2">自動化で集計します</span>
					<span class="svm-bub svm-bub--typing"><i></i><i></i><i></i></span>
					<span class="svm-notice"><img src="<?php echo $icon( 'microsoft' ); // phpcs:ignore ?>" alt=""><b>集計が完了しました</b><span class="svm-notice__bars"><i></i><i></i><i></i><i></i></span></span>
				<?php elseif ( 'system' === $key ) : ?>
					<span class="svm-phone__app">案件一覧</span>
					<span class="svm-list">
						<i class="svm-list__row svm-list__row--1"><b></b><em class="svm-tag svm-tag--wip">対応中</em><em class="svm-tag svm-tag--done">完了</em></i>
						<i class="svm-list__row svm-list__row--2"><b></b><em class="svm-tag">新規</em></i>
						<i class="svm-list__row svm-list__row--3"><b></b><em class="svm-tag">見積</em></i>
						<i class="svm-list__row svm-list__row--4"><b></b><em class="svm-tag">新規</em></i>
					</span>
					<span class="svm-ph-toast">✓ 更新しました</span>
				<?php else : ?>
					<span class="svm-phone__app"><span class="svm-live">● LIVE</span>オンライン参加</span>
					<span class="svm-meet">
						<i class="svm-tile svm-tile--host"><b></b></i><i class="svm-tile"><b></b></i>
						<i class="svm-tile svm-tile--ask"><b></b></i><i class="svm-tile"><b></b></i>
					</span>
					<span class="svm-cc">
						<span class="svm-cc__t svm-cc__t--1">生成AIでできること</span>
						<span class="svm-cc__t svm-cc__t--2">指示は具体的に</span>
						<span class="svm-cc__t svm-cc__t--3">議事録を要約してみよう</span>
						<span class="svm-cc__t svm-cc__t--4">今日のまとめ</span>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * お問い合わせ（全サービスページ共通の Google フォームへのボタン）。
 * フォームの URL が未設定のあいだは何も出さない。
 */
function fde_svp_contact( string $service_key = '' ): void {
	$url = fde_contact_form_url( $service_key );
	if ( '' === $url ) {
		return;
	}
	$label = trim( (string) fde_field( 'contact_btn_label', '' ) );
	$text  = trim( (string) fde_field( 'contact_text', '' ) );
	?>
	<section class="section section--dark svp-contact" id="contact">
		<div class="section__inner">
			<div class="svp-contact__box">
				<span class="svp-contact__bg" aria-hidden="true">Let's talk</span>
				<div class="svp-contact__copy">
					<p class="svp-contact__eyebrow mono">Contact</p>
					<h2 class="svp-contact__title">お問い合わせ</h2>
					<p class="svp-contact__text"><?php echo nl2br( esc_html( '' !== $text ? $text : 'ご相談・お見積もりのご依頼は、こちらのフォームからお送りください。内容を確認のうえ、ご連絡します。' ) ); ?></p>
				</div>
				<div class="svp-contact__action">
					<a class="svp-contact__btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
						<span><?php echo esc_html( '' !== $label ? $label : 'お問い合わせフォームへ' ); ?></span>
						<span class="svp-contact__btn-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg></span>
					</a>
					<p class="svp-contact__note">Google フォームが新しいタブで開きます。</p>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * ほかのサービスへのリンク（ページ下部）。
 */
function fde_svp_others( string $current ): void {
	$links = [];
	foreach ( fde_services() as $key => $service ) {
		$url = fde_service_url( $key );
		if ( $key !== $current && '' !== $url ) {
			$links[] = [ 'no' => $service['no'], 'title' => $service['title'], 'url' => $url ];
		}
	}
	if ( ! $links ) {
		return;
	}
	?>
	<nav class="section section--dark svp-others" aria-label="ほかのサービス">
		<div class="section__inner">
			<p class="svp-others__label mono">Other Services</p>
			<ul class="svp-others__list">
				<?php foreach ( $links as $link ) : ?>
					<li>
						<a class="svp-others__link" href="<?php echo esc_url( $link['url'] ); ?>">
							<span class="svp-others__no mono"><?php echo esc_html( $link['no'] ); ?></span>
							<span class="svp-others__title"><?php echo esc_html( $link['title'] ); ?></span>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</nav>
	<?php
}
