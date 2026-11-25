<?php
/**
 * TOP — 実績（支援した組織と取り組みを、横にスライドするカードで見せる）。
 * 各カードは「事例 01」＋業界の見出し。事例は TOP 固定ページの ACF「実績」タブの 6 枠。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_cases = fde_lp_cases();
if ( ! $fde_cases ) {
	return;
}
?>
<section class="section section--dark lp-cases" id="cases" data-section="cases">
	<div class="section__inner">
		<?php fde_svp_head( 'Cases', '実績' ); ?>

		<?php if ( $fde_cases ) : ?>
			<div class="svp-slider" data-slider>
				<div class="svp-slider__track" data-slider-track tabindex="0" aria-label="事例（横にスクロールできます）">
					<?php foreach ( $fde_cases as $fde_i => $fde_case ) : ?>
						<article class="lp-case">
							<?php if ( '' !== $fde_case['image']['url'] ) : ?>
								<div class="lp-case__img"><img src="<?php echo esc_url( $fde_case['image']['url'] ); ?>" alt="<?php echo esc_attr( $fde_case['image']['alt'] ); ?>" loading="lazy" draggable="false"></div>
							<?php endif; ?>
							<div class="lp-case__body">
								<p class="lp-case__no mono">事例 <?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></p>
								<h3 class="lp-case__title"><?php echo esc_html( $fde_case['title'] ); ?></h3>
								<?php if ( '' !== $fde_case['text'] ) : ?>
									<p class="lp-case__text"><?php echo nl2br( esc_html( $fde_case['text'] ) ); ?></p>
								<?php endif; ?>
								<?php if ( $fde_case['points'] ) : ?>
									<ul class="lp-case__points">
										<?php foreach ( $fde_case['points'] as $fde_point ) : ?>
											<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg><span><?php echo esc_html( $fde_point ); ?></span></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="svp-slider__ui">
					<div class="svp-slider__bar" aria-hidden="true"><span data-slider-bar></span></div>
					<button type="button" class="svp-slider__btn" data-slider-prev aria-label="前の事例へ">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M11 6l-6 6 6 6"/></svg>
					</button>
					<button type="button" class="svp-slider__btn" data-slider-next aria-label="次の事例へ">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
