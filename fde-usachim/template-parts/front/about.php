<?php
/**
 * §03 — About. Portrait + compact story + career timeline.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_portrait = fde_field( 'about_portrait' );
$fde_name     = (string) fde_field( 'about_name',     '' );
$fde_position = (string) fde_field( 'about_position', 'フリーランス デザイナー・エンジニア' );

$fde_lead = (string) fde_field(
	'about_lead',
	'建築、行政、そしてIT。'
);
$fde_body = (string) fde_field(
	'about_body',
	'建築で「つくる」を学び、自治体で「現場」を知り、いまはITで両方を活かしています。図面も、条例も、コードも読める。その越境がいちばんの強みです。'
);

$fde_tl_defaults = [
	[ 'year' => '2011 – 2015', 'text' => '大学で建築学を専攻' ],
	[ 'year' => '2015 – 2023', 'text' => '自治体職員（建築行政職）' ],
	[ 'year' => '2023 –',      'text' => 'フリーランスのデザイナー・エンジニアとして独立' ],
];
$fde_timeline = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$i = $n - 1;
	$fde_timeline[] = [
		'year' => (string) fde_field( "about_tl_{$n}_year", $fde_tl_defaults[ $i ]['year'] ),
		'text' => (string) fde_field( "about_tl_{$n}_text", $fde_tl_defaults[ $i ]['text'] ),
	];
}
?>
<section class="section section--dark section--decor about2" id="about" data-section="about">
	<?php fde_tri_field( 'bl', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">03</span>
				<h2 class="sec-head__title">About</h2>
			</div>
			<span class="sec-head__meta">経歴</span>
		</header>

		<div class="about2__grid">
			<figure class="about2__portrait">
				<?php if ( is_array( $fde_portrait ) && ! empty( $fde_portrait['url'] ) ) : ?>
					<img src="<?php echo esc_url( $fde_portrait['url'] ); ?>"
					     alt="<?php echo esc_attr( ! empty( $fde_portrait['alt'] ) ? $fde_portrait['alt'] : $fde_name ); ?>"
					     loading="lazy">
				<?php else : ?>
					<div class="about2__portrait-ph" aria-hidden="true">
						<span class="mono">[ PORTRAIT ]</span>
					</div>
				<?php endif; ?>
			</figure>

			<div class="about2__body">
				<?php if ( $fde_name || $fde_position ) : ?>
					<div class="about2__id">
						<?php if ( $fde_name ) : ?>
							<span class="about2__name serif"><?php echo esc_html( $fde_name ); ?></span>
						<?php endif; ?>
						<?php if ( $fde_position ) : ?>
							<span class="about2__role mono"><?php echo esc_html( $fde_position ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<p class="about2__lead jp"><?php echo esc_html( $fde_lead ); ?></p>
				<div class="about2__text jp">
					<?php echo wp_kses( fde_paragraphs( $fde_body ), [ 'p' => [], 'b' => [], 'br' => [] ] ); ?>
				</div>

				<ol class="about2__tl">
					<?php foreach ( $fde_timeline as $row ) : ?>
						<li class="about2__tl-row">
							<span class="about2__tl-year mono"><?php echo esc_html( $row['year'] ); ?></span>
							<span class="about2__tl-text jp"><?php echo esc_html( $row['text'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</section>
