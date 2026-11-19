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
 * @return array<string,array{no:string,slug:string,title:string,en:string,note:string}>
 */
function fde_services(): array {
	return [
		'web'    => [
			'no'    => '01',
			'slug'  => 'web-design',
			'title' => 'WEB制作',
			'en'    => 'Web Design',
			'note'  => 'これまでに200件以上のWebサイトを制作。コーポレートサイトやLPを、企画・デザインから公開後の運用まで手がけます。',
		],
		'dx'     => [
			'no'    => '02',
			'slug'  => 'dx',
			'title' => '業務効率化・DX支援',
			'en'    => 'DX Support',
			'note'  => '業務の流れを整理し、ツールの導入や自動化で、日々の手間を減らします。',
		],
		'system' => [
			'no'    => '03',
			'slug'  => 'system',
			'title' => 'システム開発',
			'en'    => 'System Development',
			'note'  => '業務システムやWebアプリを、要件の整理から設計・開発・運用まで一貫して手がけます。生成AIを組み込んだ開発にも対応します。',
		],
		'ai'     => [
			'no'    => '04',
			'slug'  => 'ai-training',
			'title' => 'AI研修・勉強会',
			'en'    => 'AI Training',
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
 */
function fde_contact_form_url(): string {
	return esc_url_raw( trim( (string) fde_field( 'contact_form_url', '' ) ) );
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

		$groups = [
			'web'    => [
				$field( 'web_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'web_types', '対応できるWebサイトの種類', 'textarea', $pipe ),
				$field( 'web_prices', '料金表', 'textarea', '1行に1プラン。「プラン名｜料金｜説明」の順に全角「｜」で区切ります。' ),
				$field( 'web_price_note', '料金表の下の注記' ),
				$field( 'web_works_count', '制作事例の表示件数（空欄なら 6）', 'number' ),
				$field( 'web_works_post_type', '制作事例の投稿タイプ名（通常は空欄。/web の投稿タイプを自動で使います）', 'text' ),
			],
			'dx'     => [
				$field( 'dx_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'dx_pillar_1', '支援内容 1', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_pillar_2', '支援内容 2', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_pillar_3', '支援内容 3', 'textarea', '1行目に「見出し｜説明」、2行目以降にチェック項目を1行ずつ。' ),
				$field( 'dx_examples', '自動化の例（1行に1つ）' ),
				$field( 'dx_tools', '対応ツール', 'textarea', $pipe ),
				$field( 'dx_steps', '進め方', 'textarea', $pipe ),
			],
			'system' => [
				$field( 'sys_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'sys_products', '開発できるもの', 'textarea', $pipe ),
				$field( 'sys_stack', '技術スタック', 'textarea', '1行に1分類。「分類｜技術、技術、…」のように書きます。' ),
				$field( 'sys_steps', '開発の進め方', 'textarea', $pipe ),
				$field( 'sys_product_lead', '自社サービス（調速）の上の一文' ),
			],
			'ai'     => [
				$field( 'ai_lead', 'リード文（ページ上部の説明。改行はそのまま反映）' ),
				$field( 'ai_record', 'これまでの実績' ),
				$field( 'ai_record_url', '実績を紹介したブログ記事の URL（任意。入れるとリンクを表示）', 'url' ),
				$field( 'ai_menu', '研修メニュー', 'textarea', $pipe ),
				$field( 'ai_flex', '環境に合わせた対応', 'textarea', $pipe ),
				$field( 'ai_price_note', '料金についての一文' ),
			],
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
 *
 * @param array<int,array<int,string>> $rows
 */
function fde_svp_cards( array $rows, string $modifier = '' ): void {
	if ( ! $rows ) {
		return;
	}
	?>
	<div class="svp-cards<?php echo $modifier ? ' svp-cards--' . esc_attr( $modifier ) : ''; ?>">
		<?php foreach ( $rows as $row ) : ?>
			<article class="svp-card">
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
 * ページ上部（番号・英字名・ページタイトル・リード文）。
 */
function fde_svp_hero( string $key, string $lead ): void {
	$service = fde_services()[ $key ];
	?>
	<header class="svp-hero">
		<div class="svp-hero__inner">
			<p class="svp-hero__eyebrow mono"><span class="svp-hero__no">SERVICE <?php echo esc_html( $service['no'] ); ?></span><?php echo esc_html( $service['en'] ); ?></p>
			<h1 class="svp-hero__title"><?php echo esc_html( get_the_title() ); ?></h1>
			<?php if ( '' !== $lead ) : ?>
				<p class="svp-hero__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
			<?php endif; ?>
		</div>
		<span class="svp-hero__num" aria-hidden="true"><?php echo esc_html( $service['no'] ); ?></span>
	</header>
	<?php
}

/**
 * お問い合わせ（全サービスページ共通の Google フォームへのボタン）。
 * フォームの URL が未設定のあいだは何も出さない。
 */
function fde_svp_contact(): void {
	$url = fde_contact_form_url();
	if ( '' === $url ) {
		return;
	}
	$label = trim( (string) fde_field( 'contact_btn_label', '' ) );
	$text  = trim( (string) fde_field( 'contact_text', '' ) );
	?>
	<section class="section section--dark svp-contact" id="contact">
		<div class="section__inner">
			<div class="svp-contact__box">
				<p class="svp-contact__eyebrow mono">Contact</p>
				<h2 class="svp-contact__title">お問い合わせ</h2>
				<p class="svp-contact__text"><?php echo nl2br( esc_html( '' !== $text ? $text : 'ご相談・お見積もりのご依頼は、こちらのフォームからお送りください。内容を確認のうえ、ご連絡します。' ) ); ?></p>
				<a class="svp-contact__btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
					<?php echo esc_html( '' !== $label ? $label : 'お問い合わせフォームへ' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
				</a>
				<p class="svp-contact__note">Google フォームが新しいタブで開きます。</p>
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
