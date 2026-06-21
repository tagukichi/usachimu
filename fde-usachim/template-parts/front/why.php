<?php
/**
 * §01 — Why (simplified). Premise + 3 concise pillars.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_title       = (string) fde_field( 'why_title', 'Why' );
$fde_premise     = (string) fde_field( 'why_premise', '自社のデータ、活かせていますか。' );
$fde_premise_sub = (string) fde_field(
	'why_premise_sub',
	'これからの10年を分けるのは「どんなAIを買ったか」ではなく、自社のデータを自社の手で活かせる状態を作れたか。'
);

$fde_pillar_defaults = [
	[ 'head' => 'データは眠っている',   'body' => '社内のあちこちに在るのに、横断して使えない。' ],
	[ 'head' => 'AIは土台の上に乗る',   'body' => '整ったデータがあって初めて、AIは役に立つ。' ],
	[ 'head' => '回り続ける仕組みを',   'body' => '作って終わりではなく、現場で動き続ける形に。' ],
];
$fde_pillars = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$i = $n - 1;
	$fde_pillars[] = [
		'head' => (string) fde_field( "why_pillar_{$n}_head", $fde_pillar_defaults[ $i ]['head'] ),
		'body' => (string) fde_field( "why_pillar_{$n}_body", $fde_pillar_defaults[ $i ]['body'] ),
	];
}

$fde_pillar_icons = [
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="3" width="12" height="18" rx="2"></rect><line x1="8" y1="7" x2="12" y2="7"></line><line x1="8" y1="11" x2="12" y2="11"></line><line x1="8" y1="15" x2="12" y2="15"></line><circle cx="18" cy="18" r="3"></circle></svg>',
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="12 3 3 8 12 13 21 8 12 3"></polygon><polyline points="3 12 12 17 21 12"></polyline><polyline points="3 16 12 21 21 16"></polyline></svg>',
	'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 0 1-15 6.7"></path><polyline points="6 18 6 13 11 13"></polyline><path d="M3 12a9 9 0 0 1 15-6.7"></path><polyline points="18 6 18 11 13 11"></polyline></svg>',
];
?>
<section class="section section--decor" id="why" data-section="why">
	<?php fde_tri_field( 'br', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 01</span>
				<h2 class="sec-head__title"><?php echo esc_html( $fde_title ); ?></h2>
			</div>
			<span class="sec-head__meta">WHY</span>
		</header>

		<div class="why2">
			<p class="why2__premise jp"><?php echo wp_kses( fde_inline_text( $fde_premise ), [ 'b' => [] ] ); ?></p>
			<?php if ( $fde_premise_sub ) : ?>
				<p class="why2__sub jp"><?php echo esc_html( $fde_premise_sub ); ?></p>
			<?php endif; ?>
		</div>

		<div class="why2__pillars">
			<?php foreach ( $fde_pillars as $i => $p ) : ?>
				<div class="why2__pillar">
					<?php if ( isset( $fde_pillar_icons[ $i ] ) ) : ?>
						<span class="why2__pillar-icon" aria-hidden="true"><?php echo $fde_pillar_icons[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<h3 class="why2__pillar-head"><?php echo esc_html( $p['head'] ); ?></h3>
					<p class="why2__pillar-body jp"><?php echo esc_html( $p['body'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
