<?php
/**
 * 調速 — ビジョンを形にする取り組みの 1 つ。
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

$fde_lead    = (string) fde_field( 'chousoku_lead', '「明日が少し待ち遠しくなる」を、まず現場の仕事から。' );
$fde_tagline = (string) fde_field( 'chousoku_tagline', '物件調査を、速く。' );
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
?>
<section class="section section--dark product" id="product" data-section="product">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Product</h2>
			</div>
			<span class="sec-head__meta">ビジョンを形にする取り組み</span>
		</header>

		<?php if ( '' !== trim( $fde_lead ) ) : ?>
			<p class="product__lead jp"><?php echo esc_html( $fde_lead ); ?></p>
		<?php endif; ?>

		<div class="product__grid<?php echo $fde_has_image ? ' product__grid--media' : ''; ?>">
			<div class="product__main">
				<div class="product__brand">
					<?php if ( is_array( $fde_logo ) && ! empty( $fde_logo['url'] ) ) : ?>
						<img class="product__logo"
						     src="<?php echo esc_url( $fde_logo['url'] ); ?>"
						     alt="<?php echo esc_attr( ! empty( $fde_logo['alt'] ) ? $fde_logo['alt'] : '調速' ); ?>"
						     loading="lazy">
					<?php else : ?>
						<span class="product__name">調速</span>
						<span class="product__read mono">CHO-HAYA</span>
					<?php endif; ?>
				</div>

				<h3 class="product__tagline jp"><?php echo esc_html( $fde_tagline ); ?></h3>
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
						<span class="product__point-no mono"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
						<span class="product__point-k jp"><?php echo esc_html( $fde_pt['k'] ); ?></span>
						<?php if ( '' !== trim( $fde_pt['v'] ) ) : ?>
							<span class="product__point-v jp"><?php echo esc_html( $fde_pt['v'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
