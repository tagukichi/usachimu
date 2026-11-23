<?php
/**
 * TOP ページ（1 ページ構成の伴走支援 LP）の部品と既定文。
 *
 * 構成：FV（ビジョン）→ こんなお悩み → 伴走支援 → 伴走の流れ → 解決の手段 →
 *       実績 → 調速 → よくある質問 → Blog → お問い合わせ
 * 文言は TOP 固定ページの ACF で上書きできる。空欄なら既定文（fde_lp_defaults）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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
 * 行ごとのテキストを配列に（空行は除く）。
 *
 * @return array<int,string>
 */
function fde_svp_lines( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/u', $text ) ?: [] ), 'strlen' ) );
}

/**
 * お問い合わせ（Google フォーム）の URL。
 * TOP ページの ACF が空なら、テーマに設定したフォームを使う。
 */
function fde_contact_form_url(): string {
	$url = trim( (string) fde_field( 'contact_form_url', '' ) );
	if ( '' === $url ) {
		$url = 'https://docs.google.com/forms/d/1Z7TgEWtB7j-METOTrDfhCz1PswBeecEz5Yyl9PZ8XoY/viewform';
	}
	// 編集画面から写したときに付く余計なパラメータは外す
	return esc_url_raw( remove_query_arg( [ 'edit_requested', 'usp' ], $url ) );
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
		'見直し'    => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 3v3h6V3M9 11l2 2 4-4M9 17h6"/>',
		'自動化'    => '<path d="M13 2 4 14h7l-1 8 9-12h-7z"/>',
		'システム'  => '<rect x="3" y="4" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4M7 9h4M7 12h7"/>',
		'Webサイト' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M7 6.5h.01M10 6.5h.01M8 14h8M8 17h5"/>',
		'研修'      => '<path d="M4 6l8-3 8 3-8 3z"/><path d="M6 8v5c0 1.7 2.7 3 6 3s6-1.3 6-3V8"/>',
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
		'Dify'     => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 13h3M8 16h6M15 13h2"/>',
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
 * 以前のサービス詳細ページ（TOP に集約して削除）の URL は、TOP の「解決の手段」へ転送する。
 */
add_action(
	'template_redirect',
	static function (): void {
		if ( is_page( [ 'web-design', 'dx', 'system', 'ai-training' ] ) ) {
			wp_safe_redirect( home_url( '/#solutions' ), 301 );
			exit;
		}
	}
);

/**
 * TOP の既定文（ACF が空欄のときに表示）。
 *
 * @return array<string,string>
 */
function fde_lp_defaults(): array {
	return [
		'hero_cta'      => '無料で相談してみる',

		'pain_title'    => 'こんなお悩み、ありませんか？',
		'pain_items'    => implode(
			"\n",
			[
				'生成AIを導入したけれど、現場で使われていない',
				'Excelや紙の作業に、毎日時間をとられている',
				'DXと言われても、何から手をつければいいか分からない',
				'ツールを入れたのに、業務が楽になっていない',
				'社内にIT・AIに詳しい人がいない',
				'システム会社に頼むと、高くて時間がかかる',
			]
		),
		'pain_answer'   => "その悩み、現場に入り込んで\n一緒に解決します。",

		'support_lead'  => "外から指示を出すだけではなく、現場に入り込み、\n課題の整理から仕組みづくり、社内への定着まで一緒に進めます。",
		'support_items' => implode(
			"\n",
			[
				'現場に入り込む｜現場の声を直接聞き、実際の業務を一緒に見ながら、本当の課題を見つけます。',
				'その場でつくる｜自動化の仕組みやツール、システムを、現場で試しながら素早く形にします。',
				'使えるようにする｜研修や手順書づくりまで行い、社内で使い続けられる状態にします。',
			]
		),

		'strength_title' => '自治体の現場を知る代表が、直接伴走します。',
		'strength_text'  => "代表は元行政職員。庁内の仕事の進め方や現場の実務、情報セキュリティのルールなど、自治体ならではの事情を内側から理解しています。\n技術の話だけでなく、現場の言葉で課題を整理できることが、研修や業務改善を進めるうえでの私たちの強みです。",
		'strength_tags'  => "元行政職員の代表\n自治体の業務と制約を理解\n現場目線の研修・業務改善",

		'flow_items'    => implode(
			"\n",
			[
				'ヒアリング｜困っていることや、いまの業務の進め方をうかがいます。',
				'業務の見える化｜業務の流れを整理し、改善するポイントと優先順位を決めます。',
				'つくる｜自動化・ツール導入・システム開発など、合った手段で形にします。',
				'研修・定着｜使い方の研修や手順書づくりで、社内に根づかせます。',
				'改善を続ける｜使いながら見えてきた課題を、続けて改善します。',
			]
		),
		'flow_period'   => '期間の目安：3か月〜（内容に合わせてご相談します）',

		'solution_lead'  => '課題に合わせて、いちばん合う手段を組み合わせます。',
		'solution_items' => implode(
			"\n",
			[
				'業務の見直し｜いまの業務の流れを一緒に洗い出し、ムダや手戻り、特定の人に頼っている作業を見える化します。',
				'業務の自動化｜Excelの集計・転記や定型作業を、マクロやPower Automate、生成AIで自動化します。',
				'ツール導入支援｜Microsoft 365やDifyなど、目的に合ったツールを選び、設定から定着まで支援します。',
				'システム・アプリ開発｜業務に合わせたシステムやWebアプリを、要件の整理から開発・運用まで手がけます。生成AIの組み込みにも対応します。',
				'Webサイト制作｜これまでに200件以上を制作。事業や業務の改善に合わせて、伝わるWebサイトをつくります。',
				'AI研修・勉強会｜生成AIの基礎から業務での使い方まで、組織のツールや環境に合わせて研修を行います。',
			]
		),
		'solution_tools' => 'Microsoft 365、Dify、Claude、ChatGPT、Copilot、Next.js、React、TypeScript、Python、Supabase、Vercel、Azure、Docker、WordPress',

		'faq_items'     => implode(
			"\n",
			[
				'どのくらいの期間がかかりますか？｜目安は3か月〜です。業務の内容や範囲に合わせてご相談します。',
				'小さく始めることはできますか？｜はい。まずは1つの業務から始めて、効果を見ながら広げていく進め方をおすすめしています。',
				'社内にITに詳しい人がいなくても大丈夫ですか？｜大丈夫です。業務の整理から一緒に進め、使い方の研修や手順書づくりまで行います。',
				'費用はどのくらいですか？｜内容や期間によって変わるため、お話をうかがったうえでお見積もりします。',
				'自治体の環境（三層分離など）でも対応できますか？｜はい。代表は元行政職員で、自治体の業務や環境を理解しています。ネットワーク環境や情報セキュリティのルールに沿った進め方をご提案します。',
			]
		),
	];
}

/**
 * TOP の文言（ACF → 既定文）。
 */
function fde_lp_text( string $key ): string {
	$defaults = fde_lp_defaults();
	return trim( (string) fde_field( $key, $defaults[ $key ] ?? '' ) );
}

/**
 * 実績（ACF の 6 枠。タイトルが入っている枠だけ）。1〜3 枠目には既定の事例を入れておく。
 * 画像は設定した事例だけに表示する。
 *
 * @return array<int,array{title:string,text:string,points:array<int,string>,image:array{url:string,alt:string,w:int,h:int}}>
 */
function fde_lp_cases(): array {
	$defaults = [
		1 => [
			'title'  => 'A市様',
			'text'   => '都内の自治体で、新たに導入した生成AIの定着を支援しています。職員向けの勉強会で使い方を広げるとともに、各課へのヒアリングから紙や手作業が残る業務を洗い出し、デジタル化を一つずつ進めています。外部団体との調整の場にも同席し、将来のAI基盤のあり方についても検討・提案を行っています。',
			'points' => "生成AIの勉強会・定着支援\n各課ヒアリングによるアナログ業務のDX\n外部団体との調整への参加\n将来のAI基盤の検討・提案",
		],
		2 => [
			'title'  => 'B株式会社様',
			'text'   => 'Google Workspaceの活用研修から始まり、Google Apps Scriptを使った社内ツールづくりに伴走しました。つくって終わりにせず、社員の方が自分たちでツールを開発・改善していけるよう、内製化までサポート。外部に頼らず業務の改善を続けられる体制づくりを支援しています。',
			'points' => "Google Workspaceの活用研修\nApps Scriptによる社内ツール開発の伴走\n社内で開発を続けるための内製化支援",
		],
		3 => [
			'title'  => 'C株式会社様',
			'text'   => '既存業務のワークフローを分析し、一部の業務をソフトウェア化してDXを実現しました。さらに、業界に深い知見を持つ専門家とチームを組み、現場で培われた経験やノウハウ、業界全体が抱える課題をヒアリング。それらを形にする新しいサービスを共同で開発しています。支援にとどまらず、パートナーとして協業し、新しい価値をつくっている事例です。',
			'points' => "ワークフロー分析と業務のソフトウェア化\n専門家・業界の課題のヒアリング\n業界に特化したサービスの開発",
		],
	];
	$cases = [];
	foreach ( range( 1, 6 ) as $n ) {
		$d     = $defaults[ $n ] ?? [ 'title' => '', 'text' => '', 'points' => '' ];
		$title = trim( (string) fde_field( "case_{$n}_title", $d['title'] ) );
		if ( '' === $title ) {
			continue;
		}
		$cases[] = [
			'title'  => $title,
			'text'   => trim( (string) fde_field( "case_{$n}_text", $d['text'] ) ),
			'points' => fde_svp_lines( (string) fde_field( "case_{$n}_points", $d['points'] ) ),
			'image'  => fde_image_data( fde_field( "case_{$n}_image", null ) ),
		];
	}
	return $cases;
}

/* ------------------------------------------------------------------
 * 部品（TOP のテンプレートから呼ぶ）
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
	<ol class="svp-steps" style="--steps: <?php echo (int) count( $rows ); ?>">
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
 * {{強調}} をグラデーション文字の span に、改行を <br> に変換する。
 */
function fde_svp_rich( string $text ): string {
	$out = esc_html( $text );
	$out = preg_replace( '/\{\{(.+?)\}\}/u', '<span class="svp-accent">$1</span>', $out );
	return nl2br( (string) $out );
}

/**
 * お問い合わせ（TOP の最後。Google フォームへのボタン）。
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
				<span class="svp-contact__bg" aria-hidden="true">Let's talk</span>
				<div class="svp-contact__copy">
					<p class="svp-contact__eyebrow mono">Contact</p>
					<h2 class="svp-contact__title">お問い合わせ</h2>
					<p class="svp-contact__text"><?php echo nl2br( esc_html( '' !== $text ? $text : "業務のお困りごと、まずはお気軽にご相談ください。\n内容を確認のうえ、ご連絡します。" ) ); ?></p>
				</div>
				<div class="svp-contact__action">
					<a class="svp-contact__btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">
						<span><?php echo esc_html( '' !== $label ? $label : 'お問い合わせフォームへ' ); ?></span>
						<span class="svp-contact__btn-arrow" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg></span>
					</a>
				</div>
			</div>
		</div>
	</section>
	<?php
}
