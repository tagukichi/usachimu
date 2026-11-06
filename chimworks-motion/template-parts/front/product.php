<?php
/**
 * 調速 — 不動産調査アプリ（LP の先頭部分を TOP に引用）。
 * LP（usachim.com/cho-haya/）のヒーローをそのまま持ち込む：
 * キャッチコピー・リード・4 つのポイント・製品モック（調査画面のアニメーション）・
 * データの出どころが流れる帯。モックとアニメーションは CSS だけで動く。
 * 色は LP のブルー系を使い、TOP の中で「引用」として読めるようにする。
 * ACF のトグル（chousoku_show）が OFF のあいだは丸ごと出力しない。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! (bool) fde_field( 'chousoku_show', false ) ) {
	return;
}

// 見出し：LP のコピー。「約5分」はアクセント色（{{ }} で囲むと強調）
$fde_title = (string) fde_field(
	'chousoku_title',
	"住所を入力して、{{約5分}}。\n不動産調査業務を効率化する\nクラウド型ソフトウェア。"
);
$fde_lead = (string) fde_field(
	'chousoku_lead',
	"{{公的データ × AI}} で、不動産調査業務の生産性を最大化。\n公的データの取得・整理から価格判断、提案資料、案件管理までを、ひとつの流れで支援します。"
);
$fde_logo  = fde_field( 'chousoku_logo' );
$fde_url   = (string) fde_field( 'chousoku_url', 'https://usachim.com/cho-haya/' );
$fde_cta   = (string) fde_field( 'chousoku_cta_label', '調速のサービスサイトを見る' );

$fde_points = [];
foreach (
	[
		1 => '調査業務の時間を約1/10に',
		2 => '価格判断の支援まで',
		3 => '案件管理表と連携',
		4 => 'スマホ対応',
	] as $fde_pn => $fde_pd
) {
	$fde_pk = (string) fde_field( "chousoku_point_{$fde_pn}_k", $fde_pd );
	if ( '' !== trim( $fde_pk ) ) {
		$fde_points[] = $fde_pk;
	}
}

$fde_logo_url = is_array( $fde_logo ) && ! empty( $fde_logo['url'] )
	? $fde_logo['url']
	: fde_asset_url( 'assets/img/chosoku-logo.png' );

/**
 * {{強調}} をアクセント span に、改行を <br> に変換する。
 */
$fde_rich = static function ( string $text, bool $pc_only_breaks = false ): string {
	$out = esc_html( $text );
	$out = preg_replace( '/\{\{(.+?)\}\}/u', '<span class="chq__accent">$1</span>', $out );
	return $pc_only_breaks
		? str_replace( "\n", '<br class="chq__pc">', $out )
		: nl2br( $out );
};
?>
<section class="section section--dark product" id="product" data-section="product">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Product</h2>
			</div>
		</header>

		<!-- LP ヒーローの引用ブロック -->
		<div class="chq">
			<div class="chq__bg" aria-hidden="true">
				<span class="chq__orb chq__orb--1"></span>
				<span class="chq__orb chq__orb--2"></span>
				<span class="chq__orb chq__orb--3"></span>
			</div>

			<div class="chq__inner">
				<a class="chq__logo" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
					<img src="<?php echo esc_url( $fde_logo_url ); ?>" alt="調速 — 不動産調査アプリ" loading="lazy">
				</a>

				<div class="chq__copy">
					<h3 class="chq__title jp"><?php echo $fde_rich( $fde_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></h3>
					<p class="chq__lead jp"><?php echo $fde_rich( $fde_lead, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></p>

					<?php if ( $fde_url ) : ?>
						<a class="chq__cta" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $fde_cta ); ?><span aria-hidden="true">→</span>
						</a>
					<?php endif; ?>

					<?php if ( $fde_points ) : ?>
						<ul class="chq__points jp">
							<?php foreach ( $fde_points as $fde_pt ) : ?>
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
									<?php echo esc_html( $fde_pt ); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>

				<!-- 製品モック：LP の FV と同じ調査画面のアニメーション（左＝番号付きの調査結果、右＝調査メニュー） -->
				<div class="chq__visual">
					<div class="mock-window" role="img" aria-label="調速の調査画面のイメージ（左に調査結果、右に調査メニュー）">
						<div class="mock-bar">
							<span class="mock-dot"></span><span class="mock-dot"></span><span class="mock-dot"></span>
							<span class="mock-url">app.cho-haya.com</span>
						</div>
						<div class="fvm-body" aria-hidden="true">
							<div class="fvm-search">
								<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
								<span class="fvm-search-field"><span class="fvm-search-text">東京都〇〇区〇〇町１－２－３</span></span>
								<span class="fvm-search-btn">調査</span>
								<svg class="fvm-cursor" viewBox="0 0 24 24"><path d="M5 3l14 8-6.2 1.6L10 19z"/></svg>
								<span class="fvm-loading"></span>
							</div>
							<div class="fvm-results">
								<div class="fvm-main">
									<div class="fvm-track">
										<div class="fvm-card fvm-card--9">
											<h4><span class="fvm-no">9</span>土地評価額</h4>
											<div class="fvm-row"><span class="mock-skel w40"></span><span class="mock-skel w30"></span></div>
										</div>
										<div class="fvm-card fvm-card--10">
											<h4><span class="fvm-no">10</span>周辺取引事例</h4>
											<div class="fvm-seg"><span class="is-on">〜500m</span><span>〜1km</span><span>〜2km</span><span>市全体</span></div>
											<div class="fvm-table">
												<div class="fvm-tr fvm-th"><span>時期</span><span>種類</span><span>取引価格</span><span>面積</span></div>
												<div class="fvm-tr"><i></i><i></i><i class="is-strong"></i><i></i></div>
												<div class="fvm-tr"><i></i><i></i><i class="is-strong"></i><i></i></div>
												<div class="fvm-tr"><i></i><i></i><i class="is-strong"></i><i></i></div>
											</div>
										</div>
										<div class="fvm-card fvm-card--11">
											<h4><span class="fvm-no">11</span>AI参考価格</h4>
											<div class="fvm-cols">
												<div class="fvm-box">
													<span class="fvm-label">AI参考価格（売買）</span>
													<div class="fvm-ai fvm-ai--glow"><span class="fvm-ai-head">AIが作成した内容</span><span class="mock-skel w90"></span><span class="mock-skel w70"></span><span class="mock-skel w80"></span></div>
												</div>
												<div class="fvm-box">
													<span class="fvm-label">AI参考価格（賃貸）</span>
													<div class="fvm-ai"><span class="fvm-ai-head">AIが作成した内容</span><span class="fvm-big"></span><span class="mock-skel w80"></span><span class="mock-skel w60"></span></div>
												</div>
											</div>
										</div>
										<div class="fvm-card fvm-card--12">
											<h4><span class="fvm-no">12</span>ポータルサイトで検索</h4>
											<div class="fvm-chips"><span></span><span></span><span></span><span></span></div>
										</div>
										<div class="fvm-card fvm-card--13">
											<h4><span class="fvm-no">13</span>営業決定価格</h4>
											<div class="fvm-calc"><span class="fvm-cell"></span><b>×</b><span class="fvm-cell"></span><b>=</b><span class="fvm-cell fvm-cell--result"></span></div>
											<div class="fvm-final"><span class="fvm-final-tag">最終判断</span><span class="mock-skel w50"></span></div>
										</div>
										<div class="fvm-card fvm-card--14">
											<h4><span class="fvm-no">14</span>AIレポート</h4>
											<span class="mock-skel w90"></span><span class="mock-skel w70"></span>
										</div>
									</div>
								</div>
								<div class="fvm-menu">
									<span class="fvm-btn fvm-btn--ghost">新規物件調査</span>
									<span class="fvm-btn">プレゼンシートを作成</span>
									<span class="fvm-btn">案件管理表に登録</span>
									<span class="fvm-btn fvm-btn--ghost">印刷 / PDF保存</span>
									<span class="fvm-menu-label">調査メニュー</span>
									<ol class="fvm-menu-list">
										<li class="fvm-menu-active"></li>
										<li><span>9</span>土地評価額</li>
										<li><span>10</span>周辺取引事例</li>
										<li><span>11</span>AI参考価格</li>
										<li><span>12</span>ポータル検索</li>
										<li><span>13</span>営業決定価格</li>
										<li><span>14</span>AIレポート</li>
									</ol>
								</div>
							</div>
						</div>
					</div>

					<div class="mock-phone" aria-hidden="true">
						<div class="mock-phone-notch"></div>
						<div class="mock-phone-screen fvm-phone">
							<span class="fvm-phone-btn">PowerPointで出力</span>
							<div class="fvm-phone-card">
								<b>物件調査サマリー</b>
								<span class="mock-skel w70"></span>
							</div>
							<div class="fvm-phone-card">
								<span class="mock-panel-tag">物件概要</span>
								<div class="fvm-phone-rows"><i></i><span class="mock-skel w80"></span><i></i><span class="mock-skel w60"></span><i></i><span class="mock-skel w70"></span><i></i><span class="mock-skel w50"></span></div>
							</div>
						</div>
					</div>
					<p class="chq__cap">※ 画面はイメージです。</p>
				</div>
			</div>

			<!-- データの出どころ（LP の FV 下の帯と同じ） -->
			<div class="chq__marquee" aria-hidden="true">
				<div class="chq__marquee-track">
					<span>国土交通省</span><span>国税庁</span><span>国土地理院</span><span>e-Stat</span><span>Google Maps Places</span>
					<span>国土交通省</span><span>国税庁</span><span>国土地理院</span><span>e-Stat</span><span>Google Maps Places</span>
				</div>
			</div>
		</div>
	</div>
</section>
