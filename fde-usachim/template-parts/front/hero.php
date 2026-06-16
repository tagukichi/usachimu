<?php
/**
 * Hero section (dark). Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_stmt_l1 = (string) fde_field( 'hero_statement_l1',  '未来を切り開くのは、' );
$fde_stmt_la = (string) fde_field( 'hero_statement_l2_a', '現場の' );
$fde_stmt_lb = (string) fde_field( 'hero_statement_l2_b', '手の中にある。' );
$fde_lede    = (string) fde_field(
	'hero_lede',
	"**AIと業務データを現場で使われる仕組みに変える。**\n発注を受けてから作るのではなく、現場に入り、何を作るべきかを一緒に決めるところから始めます。"
);
?>
<section class="hero" id="top" aria-label="Hero" data-hero-time>
	<div class="hero__aurora" aria-hidden="true"></div>
	<div class="hero__grid" aria-hidden="true"></div>

	<svg class="hero__visual hero__visual--lg hero__globe" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false" data-hero-globe>
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
				<?php echo esc_html( $fde_stmt_lb ); ?>
			</span>
		</h1>

		<p class="hero__lede"><?php echo wp_kses( str_replace( "\n", '<br>', fde_inline_text( $fde_lede ) ), [ 'b' => [], 'br' => [], 'strong' => [] ] ); ?></p>

	</div>
</section>
