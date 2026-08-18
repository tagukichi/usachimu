<?php
/**
 * §02 — Service. Two rich blocks:
 *   1) WEB開発（HP制作 / システム・アプリ開発=調速）+ 制作実績
 *   2) 業務効率化支援（自治体DX / 民間企業）
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ---- Block 1 : WEB開発 ----
$fde_web_desc = (string) fde_field(
	'svc_web_desc',
	'ホームページ制作からシステム・アプリ開発まで。企画から公開後の運用まで、一貫体制で対応します。'
);
$fde_web_hp_desc = (string) fde_field(
	'svc_web_hp_desc',
	'通算200件以上の制作実績。コーポレートサイトからLP、ECサイトまで、目的に合わせて設計・制作します。'
);
$fde_sys_desc = (string) fde_field(
	'svc_sys_desc',
	'業務システム・Webアプリ・API連携まで、現場の課題に合わせてゼロから設計・開発します。生成AIやMCPサーバーを組み込んだ、次世代のアプリケーション開発も得意としています。'
);
// 調速フィーチャーの表示切替（ACF のトグル。未設定時は表示）
$fde_chousoku_show = (bool) fde_field( 'chousoku_show', true );
$fde_chousoku_desc = (string) fde_field(
	'chousoku_desc',
	'不動産の物件調査をスムーズに行うための、AI搭載アプリ。物件情報の収集・整理を自動化し、調査業務にかかる時間を大幅に短縮します。'
);
$fde_chousoku_logo = fde_field( 'chousoku_logo' );
$fde_chousoku_url  = (string) fde_field( 'chousoku_url', 'https://usachim.com/cho-haya/' );

// ---- Block 2 : 業務効率化支援 ----
$fde_dx_desc = (string) fde_field(
	'svc_dx_desc',
	'行政・民間企業の現場に入り込み、伴走型で業務のデジタル化を支援します。「導入して終わり」にしない、現場で回り続ける仕組みづくりが強みです。'
);
$fde_dx_detail = (string) fde_field(
	'svc_dx_detail',
	'行政職員としての経験を活かした自治体のDX推進から、民間企業の業務改善まで。生成AI研修やAIアプリ作成、業務フロー改善、PoC作成などを通じて、現場に定着する仕組みをつくります。'
);

$fde_svc_web_img = fde_field( 'service_1_image' );

// 業務効率化支援：BOX内の画像（画像 1〜3。複数時はクロスフェード）
$fde_svc_dx_images = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$img = fde_field( "service_2_image_{$n}" );
	if ( is_array( $img ) && ! empty( $img['url'] ) ) {
		$fde_svc_dx_images[] = [ 'url' => $img['url'], 'alt' => $img['alt'] ?? '' ];
	}
}
?>
<section class="section section--dark section--decor svc" id="services" data-section="services">
	<?php fde_tri_field( 'tr', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">02</span>
				<h2 class="sec-head__title">Service</h2>
			</div>
			<span class="sec-head__meta">事業内容</span>
		</header>

		<!-- ============ Block 1 : WEB開発 ============ -->
		<article class="svc-block">
			<header class="svc-block__head">
				<span class="svc-block__no mono">SERVICE 01</span>
				<h3 class="svc-block__title">WEB開発</h3>
				<p class="svc-block__desc jp"><?php echo esc_html( $fde_web_desc ); ?></p>
			</header>

			<?php if ( is_array( $fde_svc_web_img ) && ! empty( $fde_svc_web_img['url'] ) ) : ?>
				<figure class="svc-block__media">
					<img src="<?php echo esc_url( $fde_svc_web_img['url'] ); ?>"
					     alt="<?php echo esc_attr( ! empty( $fde_svc_web_img['alt'] ) ? $fde_svc_web_img['alt'] : 'WEB開発' ); ?>"
					     loading="lazy">
				</figure>
			<?php endif; ?>

			<div class="svc-block__subs">
				<div class="svc-sub glass">
					<span class="svc-sub__label mono">01 — WEBSITE</span>
					<h4 class="svc-sub__title">ホームページ制作</h4>
					<p class="svc-sub__desc jp"><?php echo esc_html( $fde_web_hp_desc ); ?></p>
					<div class="svc-sub__stat">
						<span class="svc-sub__stat-v serif">200<span class="svc-sub__stat-unit">件+</span></span>
						<span class="svc-sub__stat-k mono">PROJECTS DELIVERED</span>
					</div>
				</div>

				<div class="svc-sub glass">
					<span class="svc-sub__label mono">02 — SYSTEM / APP</span>
					<h4 class="svc-sub__title">システム開発・アプリ開発</h4>
					<p class="svc-sub__desc jp"><?php echo esc_html( $fde_sys_desc ); ?></p>
					<ul class="svc-sub__list jp">
						<li>業務システム・Webアプリ開発</li>
						<li>API・外部サービス連携</li>
						<li>生成AI・MCPサーバーの実装</li>
					</ul>
				</div>
			</div>

			<!-- ============ 自社サービス：調速 フィーチャー ============ -->
			<?php if ( $fde_chousoku_show ) : ?>
			<div class="svc-feature glass">
				<div class="svc-feature__grid">
					<div class="svc-feature__brand">
						<?php if ( is_array( $fde_chousoku_logo ) && ! empty( $fde_chousoku_logo['url'] ) ) : ?>
							<img class="svc-feature__logo"
							     src="<?php echo esc_url( $fde_chousoku_logo['url'] ); ?>"
							     alt="<?php echo esc_attr( ! empty( $fde_chousoku_logo['alt'] ) ? $fde_chousoku_logo['alt'] : '調速' ); ?>"
							     loading="lazy">
						<?php else : ?>
							<span class="svc-feature__name serif">調速</span>
							<span class="svc-feature__read mono">ちょうはや</span>
						<?php endif; ?>
					</div>
					<div class="svc-feature__body">
						<p class="svc-feature__desc jp"><?php echo esc_html( $fde_chousoku_desc ); ?></p>
						<?php if ( $fde_chousoku_url ) : ?>
							<a class="svc-feature__link" href="<?php echo esc_url( $fde_chousoku_url ); ?>" target="_blank" rel="noopener">
								<span class="mono">調速のサービスサイトを見る</span>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</article>

		<!-- ============ Block 2 : 業務効率化支援 ============ -->
		<article class="svc-block">
			<header class="svc-block__head">
				<span class="svc-block__no mono">SERVICE 02</span>
				<h3 class="svc-block__title">業務効率化支援</h3>
				<p class="svc-block__desc jp"><?php echo esc_html( $fde_dx_desc ); ?></p>
			</header>

			<div class="svc-sub glass svc-sub--wide">
				<div class="svc-sub__wide-grid">
					<div class="svc-sub__wide-text">
						<span class="svc-sub__label mono">GOV / BUSINESS</span>
						<h4 class="svc-sub__title">行政や企業におけるDX推進支援</h4>
						<p class="svc-sub__desc jp"><?php echo esc_html( $fde_dx_detail ); ?></p>
						<ul class="svc-sub__list jp">
							<li>生成AI研修の実施</li>
							<li>AIアプリ・業務ツールの作成</li>
							<li>業務フロー改善・PoC作成</li>
							<li>Google Workspace 研修</li>
							<li>インフラ検討・個別サポート</li>
						</ul>
					</div>
					<figure class="svc-sub__wide-media<?php echo count( $fde_svc_dx_images ) > 1 ? ' is-slideshow' : ''; ?>"<?php echo count( $fde_svc_dx_images ) > 1 ? ' data-img-fade' : ''; ?>>
						<?php foreach ( $fde_svc_dx_images as $i => $img ) : ?>
							<img
								class="svc-sub__wide-img<?php echo 0 === $i ? ' is-active' : ''; ?>"
								src="<?php echo esc_url( $img['url'] ); ?>"
								alt="<?php echo esc_attr( $img['alt'] ?: '業務効率化支援' ); ?>"
								loading="lazy">
						<?php endforeach; ?>
					</figure>
				</div>
			</div>
		</article>
	</div>
</section>
