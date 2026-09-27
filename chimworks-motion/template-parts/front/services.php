<?php
/**
 * 事業内容。
 * 数字や実績は出さず、扱っている事業を 1〜2 文ずつ、読みやすい行で並べる。
 * 調速は product.php で独立セクションとして扱う。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_svc_intro = (string) fde_field(
	'svc_intro',
	'WEBサイトやシステムの制作から、行政・企業の業務効率化まで。企画から運用まで一貫して対応します。'
);

$fde_services = [
	[
		'no'    => '01',
		'title' => 'WEB制作・システム開発',
		'desc'  => (string) fde_field(
			'svc_web_desc',
			'コーポレートサイトやLPの制作から、業務システム・Webアプリ・API連携の開発まで。生成AIを組み込んだアプリケーション開発にも対応します。'
		),
		'tags'  => (string) fde_field( 'svc_web_tags', 'WEBサイト / WEBシステム / WEBアプリ / UI・UXデザイン' ),
	],
	[
		'no'    => '02',
		'title' => '業務効率化・DX支援',
		'desc'  => (string) fde_field(
			'svc_dx_desc',
			'行政職員としての経験を活かし、自治体・企業の現場に伴走して業務のデジタル化を支援します。生成AI研修、AIアプリや業務ツールの作成、業務フロー改善、PoC作成など。'
		),
		'tags'  => (string) fde_field( 'svc_dx_tags', '生成AI研修 / AIアプリ作成 / 業務フロー改善 / PoC作成 / Google Workspace' ),
	],
	[
		'no'    => '03',
		'title' => '動画制作',
		'desc'  => (string) fde_field(
			'svc_video_desc',
			'YouTube動画やショート・リール動画の企画・撮影・編集。WEBと組み合わせた発信の設計もあわせて行います。'
		),
		'tags'  => (string) fde_field( 'svc_video_tags', 'YouTube / ショート動画 / リール動画' ),
	],
];
?>
<section class="section section--dark svc" id="services" data-section="services">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Service</h2>
			</div>
			<span class="sec-head__meta">事業内容</span>
		</header>

		<?php if ( '' !== trim( $fde_svc_intro ) ) : ?>
			<p class="svc-intro jp"><?php echo esc_html( $fde_svc_intro ); ?></p>
		<?php endif; ?>

		<div class="svc-rows">
			<?php foreach ( $fde_services as $fde_svc ) : ?>
				<?php if ( '' === trim( $fde_svc['desc'] ) ) : continue; endif; ?>
				<article class="svc-row">
					<div class="svc-row__head">
						<span class="svc-row__no mono"><?php echo esc_html( $fde_svc['no'] ); ?></span>
						<h3 class="svc-row__title"><?php echo esc_html( $fde_svc['title'] ); ?></h3>
					</div>
					<div class="svc-row__body">
						<p class="svc-row__desc jp"><?php echo esc_html( $fde_svc['desc'] ); ?></p>
						<?php if ( '' !== trim( $fde_svc['tags'] ) ) : ?>
							<p class="svc-row__tags mono"><?php echo esc_html( $fde_svc['tags'] ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
