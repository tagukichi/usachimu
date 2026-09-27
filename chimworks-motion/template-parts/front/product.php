<?php
/**
 * 調速 — 物件調査アプリ。
 * FV（ビジョン）の直下に置き、事業内容より先に見せる。
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

$fde_lead    = (string) fde_field( 'chousoku_lead', '' );      // 任意。空なら出さない
$fde_tagline = (string) fde_field( 'chousoku_tagline', '' );   // 任意。空なら出さない
$fde_cat     = (string) fde_field( 'chousoku_category', '物件調査アプリ' );
$fde_desc    = (string) fde_field(
	'chousoku_desc',
	'不動産の物件調査をスムーズに行うためのAI搭載アプリ。物件情報の収集・整理を自動化し、調査業務にかかる時間を大幅に短縮します。'
);
$fde_logo    = fde_field( 'chousoku_logo' );
$fde_image   = fde_field( 'chousoku_image' );
$fde_url     = (string) fde_field( 'chousoku_url', 'https://usachim.com/cho-haya/' );
$fde_cta     = (string) fde_field( 'chousoku_cta_label', '調速のサービスサイトを見る' );

$fde_points = [];
foreach (
	[
		1 => [ '情報収集を自動化', '物件に関する各種情報をまとめて取得します。' ],
		2 => [ 'AIが整理・要約', '集めた情報を調査書式に沿って整理します。' ],
		3 => [ '調査時間を大幅に短縮', '1件あたりの調査にかかる時間を圧縮します。' ],
	] as $fde_pn => $fde_pd
) {
	$fde_pk = (string) fde_field( "chousoku_point_{$fde_pn}_k", $fde_pd[0] );
	$fde_pv = (string) fde_field( "chousoku_point_{$fde_pn}_v", $fde_pd[1] );
	if ( '' !== trim( $fde_pk ) ) {
		$fde_points[] = [ 'k' => $fde_pk, 'v' => $fde_pv ];
	}
}

$fde_has_image = is_array( $fde_image ) && ! empty( $fde_image['url'] );
$fde_has_logo  = is_array( $fde_logo ) && ! empty( $fde_logo['url'] );

// 特長カードのアイコン（並び順＝ 収集 → AI 整理 → 時間短縮）
$fde_point_icons = [
	'<path d="M12 3v10"/><path d="M8 9l4 4 4-4"/><path d="M4 15v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/>',
	'<path d="M12 3l1.8 4.7 4.7 1.8-4.7 1.8L12 16l-1.8-4.7-4.7-1.8 4.7-1.8z"/><path d="M19 15l.7 1.8 1.8.7-1.8.7L19 20l-.7-1.8-1.8-.7 1.8-.7z"/>',
	'<path d="M4.5 17a8 8 0 1 1 15 0"/><path d="M12 13.5l4-4.5"/><circle cx="12" cy="13.5" r="1.3"/>',
];
?>
<section class="section section--dark product" id="product" data-section="product">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Product</h2>
			</div>
		</header>

		<?php if ( '' !== trim( $fde_lead ) ) : ?>
			<p class="product__lead jp"><?php echo esc_html( $fde_lead ); ?></p>
		<?php endif; ?>

		<div class="product__grid<?php echo $fde_has_image ? ' product__grid--media' : ''; ?>">
			<div class="product__main">
				<div class="product__brand">
					<?php if ( $fde_has_logo ) : ?>
						<img class="product__mark"
						     src="<?php echo esc_url( $fde_logo['url'] ); ?>"
						     alt=""
						     loading="lazy">
					<?php endif; ?>
					<h3 class="product__name">調速</h3>
					<?php if ( '' !== trim( $fde_cat ) ) : ?>
						<span class="product__cat jp"><?php echo esc_html( $fde_cat ); ?></span>
					<?php endif; ?>
				</div>

				<?php if ( '' !== trim( $fde_tagline ) ) : ?>
					<p class="product__tagline jp"><?php echo esc_html( $fde_tagline ); ?></p>
				<?php endif; ?>
				<p class="product__desc jp"><?php echo esc_html( $fde_desc ); ?></p>

				<?php if ( $fde_url ) : ?>
					<a class="btn btn--solid product__cta" href="<?php echo esc_url( $fde_url ); ?>" target="_blank" rel="noopener">
						<?php echo esc_html( $fde_cta ); ?><span aria-hidden="true">→</span>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( $fde_has_image ) : ?>
				<figure class="product__media">
					<img src="<?php echo esc_url( $fde_image['url'] ); ?>"
					     alt="<?php echo esc_attr( ! empty( $fde_image['alt'] ) ? $fde_image['alt'] : '調速の画面' ); ?>"
					     loading="lazy">
				</figure>
			<?php endif; ?>
		</div>

		<?php if ( $fde_points ) : ?>
			<ul class="product__points">
				<?php foreach ( $fde_points as $fde_i => $fde_pt ) : ?>
					<li class="product__point">
						<span class="product__point-no" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
						<span class="product__point-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $fde_point_icons[ $fde_i % 3 ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 固定の SVG パス ?></svg>
						</span>
						<h4 class="product__point-k jp"><?php echo esc_html( $fde_pt['k'] ); ?></h4>
						<?php if ( '' !== trim( $fde_pt['v'] ) ) : ?>
							<p class="product__point-v jp"><?php echo esc_html( $fde_pt['v'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
