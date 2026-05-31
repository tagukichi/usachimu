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
	"**AIと業務データを、現場で使われる仕組みに変える。**\n発注を受けてから作るのではなく、現場に入り、何を作るべきかを一緒に決めるところから始めます。"
);
?>
<section class="hero" id="top" aria-label="Hero" data-hero-time>
	<div class="hero__aurora" aria-hidden="true"></div>
	<div class="hero__grid" aria-hidden="true"></div>

	<svg class="hero__visual" viewBox="0 0 400 400" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
		<g class="hero__visual-lines" stroke="currentColor" stroke-width="0.6" fill="none">
			<line x1="80"  y1="100" x2="220" y2="60"  />
			<line x1="220" y1="60"  x2="340" y2="140" />
			<line x1="220" y1="60"  x2="180" y2="220" />
			<line x1="340" y1="140" x2="300" y2="290" />
			<line x1="180" y1="220" x2="300" y2="290" />
			<line x1="80"  y1="100" x2="180" y2="220" />
			<line x1="180" y1="220" x2="110" y2="330" />
			<line x1="300" y1="290" x2="110" y2="330" />
		</g>
		<g class="hero__visual-nodes" fill="currentColor">
			<circle cx="80"  cy="100" r="4" />
			<circle cx="220" cy="60"  r="7" />
			<circle cx="340" cy="140" r="4" />
			<circle cx="180" cy="220" r="5" />
			<circle cx="300" cy="290" r="4" />
			<circle cx="110" cy="330" r="3" />
		</g>
		<g class="hero__visual-pulse" fill="none" stroke="currentColor" stroke-width="0.5">
			<circle cx="220" cy="60" r="14" />
			<circle cx="180" cy="220" r="14" />
		</g>
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
