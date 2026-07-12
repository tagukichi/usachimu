<?php
/**
 * §01 — Concept. The "why" behind the work, told in three beats.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_lead = (string) fde_field(
	'concept_lead',
	'「つくる」ことで、誰かの役に立ちたい。'
);
$fde_body = (string) fde_field(
	'concept_body',
	"家を建てたくて、建築を学んだ。\nまちの役に立ちたくて、自治体で働いた。\nもっと多くの人の力になりたくて、独立した。\n\nITを選んだのは、もともと好きだったから。そして、ITに困っている人が、まだたくさんいるから。\n技術と現場の経験を使って、今日の困りごとを、明日の楽しみに変えていきます。"
);
?>
<section class="section section--dark section--decor concept concept--front-tri" id="concept" data-section="concept">
	<?php fde_tri_field( 'tl', 9 ); ?>
	<?php fde_tri_field( 'br', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">01</span>
				<h2 class="sec-head__title">Concept</h2>
			</div>
			<span class="sec-head__meta">考え</span>
		</header>

		<div class="concept__inner">
			<p class="concept__lead jp"><?php echo esc_html( $fde_lead ); ?></p>
			<div class="concept__body jp">
				<?php echo wp_kses( fde_paragraphs( $fde_body ), [ 'p' => [], 'b' => [], 'br' => [] ] ); ?>
			</div>
		</div>
	</div>
</section>
