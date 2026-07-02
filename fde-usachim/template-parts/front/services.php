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
$fde_chousoku_desc = (string) fde_field(
	'chousoku_desc',
	'自社サービス「調速」を開発・運営。不動産の物件調査をスムーズに行うための、AI搭載アプリケーションです。'
);
$fde_chousoku_logo = fde_field( 'chousoku_logo' );
$fde_chousoku_url  = (string) fde_field( 'chousoku_url', '' );

// ---- Block 2 : 業務効率化支援 ----
$fde_dx_desc = (string) fde_field(
	'svc_dx_desc',
	'行政・民間企業の現場に入り込み、伴走型で業務のデジタル化を支援します。「導入して終わり」にしない、現場で回り続ける仕組みづくりが強みです。'
);
$fde_dx_gov_desc = (string) fde_field(
	'svc_dx_gov_desc',
	'生成AI研修の実施、現課への個別サポート、AIアプリの作成、インフラ検討まで。行政職員としての経験を活かし、庁内の実情に合わせて進めます。'
);
$fde_dx_biz_desc = (string) fde_field(
	'svc_dx_biz_desc',
	'Google Workspace研修、業務フロー改善の提案、GASなどを使ったPoC作成。小さく試して、効果を確かめながら広げていきます。'
);

$fde_svc_web_img = fde_field( 'service_1_image' );
$fde_svc_dx_img  = fde_field( 'service_2_image' );

// ---- 制作実績（works CPT）----
$fde_works = new WP_Query(
	[
		'post_type'           => 'works',
		'posts_per_page'      => 6,
		'orderby'             => 'menu_order date',
		'order'               => 'ASC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);
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
					<p class="svc-sub__desc jp"><?php echo esc_html( $fde_chousoku_desc ); ?></p>

					<div class="svc-sub__product">
						<?php if ( is_array( $fde_chousoku_logo ) && ! empty( $fde_chousoku_logo['url'] ) ) : ?>
							<img class="svc-sub__product-logo"
							     src="<?php echo esc_url( $fde_chousoku_logo['url'] ); ?>"
							     alt="<?php echo esc_attr( ! empty( $fde_chousoku_logo['alt'] ) ? $fde_chousoku_logo['alt'] : '調速' ); ?>"
							     loading="lazy">
						<?php else : ?>
							<span class="svc-sub__product-name serif">調速</span>
						<?php endif; ?>
						<?php if ( $fde_chousoku_url ) : ?>
							<a class="svc-sub__product-link mono" href="<?php echo esc_url( $fde_chousoku_url ); ?>" target="_blank" rel="noopener">
								サービスサイトへ →
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( $fde_works->have_posts() ) : ?>
				<div class="svc-works">
					<span class="svc-works__label mono">WORKS — 制作実績</span>
					<div class="svc-works__grid">
						<?php $fde_i = 0; while ( $fde_works->have_posts() ) : $fde_works->the_post(); $fde_i++; ?>
							<?php
							$industry = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
							$year     = function_exists( 'get_field' ) ? (string) get_field( 'year' ) : '';
							$thumb    = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium_large' ) : '';
							?>
							<a class="svc-work" href="<?php the_permalink(); ?>">
								<div class="svc-work__thumb">
									<?php if ( $thumb ) : ?>
										<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
									<?php else : ?>
										<span class="svc-work__thumb-no mono"><?php echo esc_html( sprintf( '%02d', $fde_i ) ); ?></span>
									<?php endif; ?>
								</div>
								<div class="svc-work__meta">
									<span class="svc-work__title jp"><?php the_title(); ?></span>
									<span class="svc-work__sub mono"><?php echo esc_html( trim( $industry . ( $year ? ' · ' . $year : '' ) ) ); ?></span>
								</div>
							</a>
						<?php endwhile; ?>
					</div>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</article>

		<!-- ============ Block 2 : 業務効率化支援 ============ -->
		<article class="svc-block">
			<header class="svc-block__head">
				<span class="svc-block__no mono">SERVICE 02</span>
				<h3 class="svc-block__title">業務効率化支援</h3>
				<p class="svc-block__desc jp"><?php echo esc_html( $fde_dx_desc ); ?></p>
			</header>

			<?php if ( is_array( $fde_svc_dx_img ) && ! empty( $fde_svc_dx_img['url'] ) ) : ?>
				<figure class="svc-block__media">
					<img src="<?php echo esc_url( $fde_svc_dx_img['url'] ); ?>"
					     alt="<?php echo esc_attr( ! empty( $fde_svc_dx_img['alt'] ) ? $fde_svc_dx_img['alt'] : '業務効率化支援' ); ?>"
					     loading="lazy">
				</figure>
			<?php endif; ?>

			<div class="svc-block__subs">
				<div class="svc-sub glass">
					<span class="svc-sub__label mono">01 — GOVERNMENT</span>
					<h4 class="svc-sub__title">自治体でのDX推進支援</h4>
					<p class="svc-sub__desc jp"><?php echo esc_html( $fde_dx_gov_desc ); ?></p>
					<ul class="svc-sub__list jp">
						<li>生成AI研修の実施</li>
						<li>現課への個別サポート</li>
						<li>AIアプリの作成</li>
						<li>インフラ検討</li>
					</ul>
				</div>

				<div class="svc-sub glass">
					<span class="svc-sub__label mono">02 — BUSINESS</span>
					<h4 class="svc-sub__title">民間企業支援</h4>
					<p class="svc-sub__desc jp"><?php echo esc_html( $fde_dx_biz_desc ); ?></p>
					<ul class="svc-sub__list jp">
						<li>Google Workspace 研修</li>
						<li>業務フロー改善の提案</li>
						<li>GAS等を使用したPoC作成</li>
					</ul>
				</div>
			</div>
		</article>
	</div>
</section>
