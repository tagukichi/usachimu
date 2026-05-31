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

	<svg class="hero__visual hero__visual--sm" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
		<g class="hero__visual-lines" stroke="currentColor" stroke-width="0.8" fill="none">
			<line x1="40"  y1="40"  x2="130" y2="70"  />
			<line x1="130" y1="70"  x2="170" y2="160" />
			<line x1="40"  y1="40"  x2="70"  y2="150" />
			<line x1="70"  y1="150" x2="170" y2="160" />
		</g>
		<g class="hero__visual-nodes" fill="currentColor">
			<circle cx="40"  cy="40"  r="3" />
			<circle cx="130" cy="70"  r="4.5" />
			<circle cx="170" cy="160" r="3" />
			<circle cx="70"  cy="150" r="3" />
		</g>
		<g class="hero__visual-pulse" fill="none" stroke="currentColor" stroke-width="0.6">
			<circle cx="130" cy="70" r="10" />
		</g>
	</svg>

	<svg class="hero__visual hero__visual--lg" viewBox="0 0 200 200" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false" data-hero-poly>
		<g class="hero__poly-edges" stroke="currentColor" stroke-width="0.6" fill="none"></g>
		<g class="hero__poly-verts" fill="currentColor"></g>
		<g class="hero__poly-pulses" fill="currentColor"></g>
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
