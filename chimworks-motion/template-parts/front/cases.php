<?php
/**
 * TOP — 実績。
 * 上に「研修 → 伴走 → 協業」の関わり方の深さを示し、その順に事例を 1 件ずつ縦に並べる。
 * 各事例は「CASE 番号・関わり方 → お客さま → 大きなキャッチ → 説明 → 取り組んだこと」。
 * 文言は TOP 固定ページの ACF「実績」タブで変更できる。
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
$fde_title  = fde_lp_text( 'cases_title' );
$fde_lead   = fde_lp_text( 'cases_lead' );
$fde_stages = array_values( array_filter( array_column( $fde_cases, 'stage' ), 'strlen' ) );
$fde_check  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';
?>
<section class="section section--dark lp-cases" id="cases" data-section="cases">
	<div class="section__inner">
		<?php fde_svp_head( 'Cases', '実績' ); ?>

		<div class="lp-depth">
			<div class="lp-depth__copy">
				<?php if ( '' !== $fde_title ) : ?>
					<h3 class="lp-depth__title"><?php echo esc_html( $fde_title ); ?></h3>
				<?php endif; ?>
				<?php if ( '' !== $fde_lead ) : ?>
					<p class="lp-depth__lead"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( count( $fde_stages ) > 1 ) : ?>
				<ol class="lp-depth__steps" aria-label="関わり方の深さ">
					<?php foreach ( $fde_stages as $fde_i => $fde_stage ) : ?>
						<li class="lp-depth__step">
							<span class="lp-depth__no mono"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
							<span class="lp-depth__label"><?php echo esc_html( $fde_stage ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php endif; ?>
		</div>

		<div class="lp-cases__list">
			<?php foreach ( $fde_cases as $fde_i => $fde_case ) : ?>
				<article class="lp-case<?php echo '' !== $fde_case['image']['url'] ? ' has-image' : ''; ?>">
					<div class="lp-case__main">
						<p class="lp-case__meta mono">
							<span class="lp-case__no">CASE <?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
							<?php if ( '' !== $fde_case['stage'] ) : ?>
								<span class="lp-case__stage"><?php echo esc_html( $fde_case['stage'] ); ?></span>
							<?php endif; ?>
						</p>
						<p class="lp-case__client"><?php echo esc_html( $fde_case['title'] ); ?></p>
						<?php if ( '' !== $fde_case['catch'] ) : ?>
							<h3 class="lp-case__catch"><?php echo esc_html( $fde_case['catch'] ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $fde_case['text'] ) : ?>
							<p class="lp-case__text"><?php echo nl2br( esc_html( $fde_case['text'] ) ); ?></p>
						<?php endif; ?>
					</div>
					<div class="lp-case__side">
						<?php if ( '' !== $fde_case['image']['url'] ) : ?>
							<div class="lp-case__img"><img src="<?php echo esc_url( $fde_case['image']['url'] ); ?>" alt="<?php echo esc_attr( $fde_case['image']['alt'] ); ?>" loading="lazy"></div>
						<?php endif; ?>
						<?php if ( $fde_case['points'] ) : ?>
							<div class="lp-case__box">
								<p class="lp-case__box-label">取り組んだこと</p>
								<ul class="lp-case__points">
									<?php foreach ( $fde_case['points'] as $fde_point ) : ?>
										<li><?php echo $fde_check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 固定の SVG ?><span><?php echo esc_html( $fde_point ); ?></span></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
