<?php
/**
 * Services excerpt on the front page.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_services = [
	[
		'no'    => '01',
		'title' => 'AI 業務自動化支援',
		'desc'  => 'Dify / Claude を活用した社内業務の自動化、ワークフロー設計。',
	],
	[
		'no'    => '02',
		'title' => 'Web サイト・LP 制作',
		'desc'  => 'コーポレートサイト、採用サイト、LP、WordPress 開発。',
	],
	[
		'no'    => '03',
		'title' => 'DX 顧問・継続伴走',
		'desc'  => '月額契約での技術顧問・改善支援。',
	],
	[
		'no'    => '04',
		'title' => 'PoC・プロトタイプ開発',
		'desc'  => '「動くもの」を素早く作り、検証する。',
	],
];
?>
<section class="services-excerpt section" aria-labelledby="services-excerpt-title">
	<div class="container">
		<header class="section-head">
			<p class="section-head__eyebrow">Services</p>
			<h2 id="services-excerpt-title" class="section-head__title">提供サービス</h2>
		</header>

		<ul class="services-excerpt__list">
			<?php foreach ( $fde_services as $service ) : ?>
				<li class="service-card">
					<span class="service-card__no"><?php echo esc_html( $service['no'] ); ?></span>
					<h3 class="service-card__title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="service-card__desc"><?php echo esc_html( $service['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

		<p class="section-cta">
			<a class="button button--ghost" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">すべてのサービスを見る</a>
		</p>
	</div>
</section>
