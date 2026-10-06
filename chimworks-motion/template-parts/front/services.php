<?php
/**
 * 事業内容。
 * 数字や実績は出さず、扱っている事業を 1〜2 文ずつ、読みやすい行で並べる。
 * 行ごとに特設ページの URL が設定されていれば、行全体がリンクになり、
 * ホバーで緑の帯が走って文字が白抜きになる。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_svc_intro = (string) fde_field(
	'svc_intro',
	'WEBサイト・システムの制作から、行政・企業の業務効率化まで対応します。'
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
		'url'   => (string) fde_field( 'svc_web_url', '' ),
	],
	[
		'no'    => '02',
		'title' => '業務効率化・DX支援',
		'desc'  => (string) fde_field(
			'svc_dx_desc',
			'行政職員としての経験を活かし、自治体・企業の現場に伴走して業務のデジタル化を支援します。生成AI研修、AIアプリや業務ツールの作成、業務フロー改善、PoC作成など。'
		),
		'tags'  => (string) fde_field( 'svc_dx_tags', '生成AI研修 / AIアプリ作成 / 業務フロー改善 / PoC作成 / Google Workspace' ),
		'url'   => (string) fde_field( 'svc_dx_url', '' ),
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
				<?php
				if ( '' === trim( $fde_svc['desc'] ) ) {
					continue;
				}
				$fde_is_link = '' !== trim( $fde_svc['url'] );
				$fde_tag     = $fde_is_link ? 'a' : 'article';
				?>
				<<?php echo $fde_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 'a' か 'article' の固定値 ?>
					class="svc-row<?php echo $fde_is_link ? ' svc-row--link' : ''; ?>"
					<?php if ( $fde_is_link ) : ?>href="<?php echo esc_url( $fde_svc['url'] ); ?>"<?php endif; ?>>
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
					<?php if ( $fde_is_link ) : ?>
						<span class="svc-row__go" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
						</span>
					<?php endif; ?>
				</<?php echo $fde_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
