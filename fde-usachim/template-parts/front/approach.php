<?php
/**
 * Approach (4-phase) section.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_phases = [
	[ 'label' => 'Discover',  'desc' => '現場に入り、本当の課題を見つける。' ],
	[ 'label' => 'Prototype', 'desc' => '数日で動くものを作り、議論を前に進める。' ],
	[ 'label' => 'Build',     'desc' => '本番品質に磨き上げ、運用に乗せる。' ],
	[ 'label' => 'Operate',   'desc' => '使い続けられるよう、改善し続ける。' ],
];
?>
<section class="approach section" aria-labelledby="approach-title">
	<div class="container">
		<header class="section-head">
			<p class="section-head__eyebrow">Approach</p>
			<h2 id="approach-title" class="section-head__title">4フェーズで伴走する</h2>
		</header>

		<ol class="approach__list">
			<?php foreach ( $fde_phases as $i => $phase ) : ?>
				<li class="approach__item">
					<span class="approach__step"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3 class="approach__label"><?php echo esc_html( $phase['label'] ); ?></h3>
					<p class="approach__desc"><?php echo esc_html( $phase['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
