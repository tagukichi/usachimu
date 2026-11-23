<?php
/**
 * TOP — 実績（数字で見る実績 + 事例カード）。
 * 事例は TOP 固定ページの ACF「実績」タブの 6 枠（「A社」「B市」のような表記で、何をやったか）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_cases = fde_lp_cases();
$fde_stats = fde_pipe_rows( fde_lp_text( 'case_stats' ) );
if ( ! $fde_cases && ! $fde_stats ) {
	return;
}
?>
<section class="section section--dark lp-cases" id="cases" data-section="cases">
	<div class="section__inner">
		<?php fde_svp_head( 'Cases', '実績' ); ?>

		<?php if ( $fde_stats ) : ?>
			<dl class="lp-stats">
				<?php foreach ( $fde_stats as $fde_stat ) : ?>
					<div class="lp-stats__item">
						<dt class="lp-stats__v"><?php echo esc_html( $fde_stat[0] ); ?></dt>
						<?php if ( ! empty( $fde_stat[1] ) ) : ?>
							<dd class="lp-stats__k"><?php echo esc_html( $fde_stat[1] ); ?></dd>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>

		<?php if ( $fde_cases ) : ?>
			<div class="lp-cases__list lp-cases__list--<?php echo esc_attr( (string) min( count( $fde_cases ), 3 ) ); ?>">
				<?php foreach ( $fde_cases as $fde_case ) : ?>
					<article class="lp-case">
						<?php if ( '' !== $fde_case['image']['url'] ) : ?>
							<div class="lp-case__img"><img src="<?php echo esc_url( $fde_case['image']['url'] ); ?>" alt="<?php echo esc_attr( $fde_case['image']['alt'] ); ?>" loading="lazy"></div>
						<?php endif; ?>
						<div class="lp-case__body">
							<p class="lp-case__meta">
								<?php if ( '' !== $fde_case['tag'] ) : ?>
									<span class="lp-case__tag"><?php echo esc_html( $fde_case['tag'] ); ?></span>
								<?php endif; ?>
								<?php if ( '' !== $fde_case['client'] ) : ?>
									<span class="lp-case__client"><?php echo esc_html( $fde_case['client'] ); ?></span>
								<?php endif; ?>
							</p>
							<h3 class="lp-case__title"><?php echo esc_html( $fde_case['title'] ); ?></h3>
							<?php if ( '' !== $fde_case['text'] ) : ?>
								<p class="lp-case__text"><?php echo nl2br( esc_html( $fde_case['text'] ) ); ?></p>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
