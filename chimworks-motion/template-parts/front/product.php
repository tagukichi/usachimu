<?php
/**
 * 調速 — 不動産調査アプリ（LP の先頭部分を TOP に引用）。
 * LP（usachim.com/cho-haya/）のヒーローをそのまま持ち込む：
 * キャッチコピー・リード・4 つのポイント・製品モック（CSS 組み）。
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
	"{{公的データ × AI}} で、不動産調査業務の生産性を最大化。\n公的データの取得・整理から提案文・A4資料の作成までを、1つの画面で支援します。"
);
$fde_logo  = fde_field( 'chousoku_logo' );
$fde_url   = (string) fde_field( 'chousoku_url', 'https://usachim.com/cho-haya/' );
$fde_cta   = (string) fde_field( 'chousoku_cta_label', '調速のサービスサイトを見る' );
$fde_note  = (string) fde_field( 'chousoku_note', '事業の中から生まれた、自社の不動産調査アプリです。' );

$fde_points = [];
foreach (
	[
		1 => '調査業務の時間を約1/10に',
		2 => '公的データ100%',
		3 => 'スマホ対応',
		4 => '案件管理ダッシュボード',
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
$fde_rich = static function ( string $text ): string {
	$out = esc_html( $text );
	$out = preg_replace( '/\{\{(.+?)\}\}/u', '<span class="chq__accent">$1</span>', $out );
	return nl2br( $out );
};
?>
<section class="section section--dark product" id="product" data-section="product">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Product</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( $fde_note ); ?></span>
		</header>

		<!-- LP ヒーローの引用ブロック -->
		<div class="chq">
			<div class="chq__bg" aria-hidden="true"></div>

			<div class="chq__inner">
				<div class="chq__copy">
					<a class="chq__logo" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
						<img src="<?php echo esc_url( $fde_logo_url ); ?>" alt="調速 — 不動産調査アプリ" loading="lazy">
					</a>

					<h3 class="chq__title jp"><?php echo $fde_rich( $fde_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></h3>
					<p class="chq__lead jp"><?php echo $fde_rich( $fde_lead ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- esc_html 済み ?></p>

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

					<?php if ( $fde_url ) : ?>
						<a class="chq__cta" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $fde_cta ); ?><span aria-hidden="true">→</span>
						</a>
					<?php endif; ?>
				</div>

				<!-- 製品モック（PC + スマホ）：LP と同じ CSS 組み -->
				<div class="chq__visual" aria-hidden="true">
					<div class="mock-window">
						<div class="mock-bar">
							<span class="mock-dot"></span><span class="mock-dot"></span><span class="mock-dot"></span>
							<span class="mock-url">app.chosoku.jp</span>
						</div>
						<div class="mock-body">
							<div class="mock-search">
								<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
								<span class="mock-search-text">東京都〇〇区〇〇 1-2-3</span>
								<span class="mock-search-btn">調査</span>
							</div>
							<div class="mock-grid">
								<div class="mock-panel mock-panel--map">
									<span class="mock-panel-tag">地図</span>
									<div class="mock-map"><span class="mock-pin"></span></div>
								</div>
								<div class="mock-panel"><span class="mock-panel-tag">用途地域</span><span class="mock-skel w70"></span><span class="mock-skel w40"></span></div>
								<div class="mock-panel"><span class="mock-panel-tag">建蔽率 / 容積率</span><span class="mock-skel w60"></span><span class="mock-skel w50"></span></div>
								<div class="mock-panel"><span class="mock-panel-tag">ハザード</span><span><span class="mock-chip">浸水</span><span class="mock-chip">土砂</span><span class="mock-chip">地震</span></span></div>
								<div class="mock-panel"><span class="mock-panel-tag">学区</span><span class="mock-skel w80"></span><span class="mock-skel w50"></span></div>
								<div class="mock-panel"><span class="mock-panel-tag">地価</span><span class="mock-skel w70"></span><span class="mock-link-skel">国税庁で確認 ↗</span></div>
								<div class="mock-panel"><span class="mock-panel-tag">取引事例</span><span class="mock-bars"><i style="height:40%"></i><i style="height:70%"></i><i style="height:55%"></i><i style="height:85%"></i></span></div>
								<div class="mock-panel"><span class="mock-panel-tag">生活利便施設</span><span class="mock-skel w90"></span><span class="mock-skel w60"></span></div>
							</div>
						</div>
					</div>

					<div class="mock-phone">
						<div class="mock-phone-notch"></div>
						<div class="mock-phone-screen">
							<div class="mock-phone-search"><span></span></div>
							<div class="mock-phone-map"><span class="mock-pin"></span></div>
							<div class="mock-phone-panels">
								<div class="mock-phone-panel"><span class="mock-panel-tag">用途地域</span><span class="mock-skel w70"></span></div>
								<div class="mock-phone-panel"><span class="mock-panel-tag">ハザード</span><span><span class="mock-chip">浸水</span><span class="mock-chip">地震</span></span></div>
								<div class="mock-phone-panel"><span class="mock-panel-tag">地価</span><span class="mock-skel w60"></span></div>
								<div class="mock-phone-panel"><span class="mock-panel-tag">取引事例</span><span class="mock-bars"><i style="height:50%"></i><i style="height:80%"></i><i style="height:60%"></i></span></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<a class="chq__source mono" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
				FROM <?php echo esc_html( preg_replace( '#^https?://#', '', rtrim( $fde_url, '/' ) ) ); ?> ↗
			</a>
		</div>
	</div>
</section>
