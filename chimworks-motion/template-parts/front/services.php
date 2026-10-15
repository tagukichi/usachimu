<?php
/**
 * 事業内容。
 * 説明文やキーワードは置かず、事業名だけのタイルを 2 枚並べる。
 * 行ごとに特設ページの URL が設定されていれば、行全体がリンクになり、
 * ホバーで緑の帯が走って文字が白抜きになる。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_services = [
	[
		'no'    => '01',
		'title' => 'WEB制作・システム開発',
		'url'   => (string) fde_field( 'svc_web_url', '' ),
	],
	[
		'no'    => '02',
		'title' => '業務効率化・DX支援',
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

		<div class="svc-rows svc-rows--titles">
			<?php foreach ( $fde_services as $fde_svc ) : ?>
				<?php
				$fde_is_link = '' !== trim( $fde_svc['url'] );
				$fde_tag     = $fde_is_link ? 'a' : 'article';
				?>
				<<?php echo $fde_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 'a' か 'article' の固定値 ?>
					class="svc-row<?php echo $fde_is_link ? ' svc-row--link' : ''; ?>"
					data-no="<?php echo esc_attr( $fde_svc['no'] ); ?>"
					<?php if ( $fde_is_link ) : ?>href="<?php echo esc_url( $fde_svc['url'] ); ?>"<?php endif; ?>>
					<div class="svc-row__head">
						<span class="svc-row__no mono"><?php echo esc_html( $fde_svc['no'] ); ?></span>
						<h3 class="svc-row__title"><?php echo esc_html( $fde_svc['title'] ); ?></h3>
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
