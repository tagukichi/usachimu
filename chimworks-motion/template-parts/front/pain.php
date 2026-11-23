<?php
/**
 * TOP — こんなお悩み、ありませんか？（悩みを並べて、伴走支援への答えにつなぐ）
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_items = fde_svp_lines( fde_lp_text( 'pain_items' ) );
if ( ! $fde_items ) {
	return;
}
$fde_answer = fde_lp_text( 'pain_answer' );
?>
<section class="section section--dark lp-pain" id="pain" data-section="pain">
	<div class="section__inner">
		<p class="lp-pain__eyebrow mono">Problems</p>
		<h2 class="lp-pain__title"><?php echo esc_html( fde_lp_text( 'pain_title' ) ); ?></h2>

		<ul class="lp-pain__list">
			<?php foreach ( $fde_items as $fde_item ) : ?>
				<li class="lp-pain__item">
					<span class="lp-pain__mark" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></span>
					<span class="lp-pain__text"><?php echo esc_html( $fde_item ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( '' !== $fde_answer ) : ?>
			<div class="lp-pain__answer">
				<span class="lp-pain__arrow" aria-hidden="true"></span>
				<p class="lp-pain__answer-text"><?php echo nl2br( esc_html( $fde_answer ) ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
