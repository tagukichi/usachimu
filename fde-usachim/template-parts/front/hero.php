<?php
/**
 * Hero — mission statement over the globe animation.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_stmt_l1 = (string) fde_field( 'hero_statement_l1',  '明日が少し待ち遠しくなる、' );
$fde_stmt_la = (string) fde_field( 'hero_statement_l2_a', '' );
$fde_stmt_lb = (string) fde_field( 'hero_statement_l2_b', '社会の実現へ。' );
$fde_lede    = (string) fde_field(
	'hero_lede',
	'ITの力で、日本の未来を切り開く。'
);
?>
<section class="hero" id="top" aria-label="Hero" data-hero-time>
	<div class="hero__aurora" aria-hidden="true"></div>
	<div class="hero__grid" aria-hidden="true"></div>

	<svg class="hero__visual hero__visual--lg hero__globe" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false" data-hero-globe>
		<g class="hero__globe-grid" stroke="currentColor" fill="none"></g>
		<g class="hero__globe-grid-dots" fill="currentColor"></g>
		<g class="hero__globe-faces" stroke="currentColor" fill="currentColor"></g>
		<g class="hero__globe-floaters" stroke="currentColor" fill="currentColor"></g>
		<g class="hero__globe-dots" fill="currentColor"></g>
	</svg>

	<div class="hero__inner">

		<h1 class="hero__statement jp">
			<span class="hero__statement-line">
				<?php echo esc_html( $fde_stmt_l1 ); ?>
			</span>
			<span class="hero__statement-line">
				<?php if ( $fde_stmt_la ) : ?>
					<span class="hero__statement-mute"><?php echo esc_html( $fde_stmt_la ); ?></span><?php endif; ?>
				<span class="hero__statement-em"><?php echo esc_html( $fde_stmt_lb ); ?></span>
			</span>
		</h1>

		<p class="hero__lede"><?php echo wp_kses( str_replace( "\n", '<br>', fde_inline_text( $fde_lede ) ), [ 'b' => [], 'br' => [], 'strong' => [] ] ); ?></p>

		<div class="hero__scroll mono" aria-hidden="true">
			<span class="hero__scroll-label">SCROLL</span>
			<span class="hero__scroll-line"></span>
		</div>

	</div>
</section>
