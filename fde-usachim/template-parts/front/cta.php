<?php
/**
 * Bottom CTA block on the front page.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="front-cta section" aria-labelledby="front-cta-title">
	<div class="container container--narrow">
		<h2 id="front-cta-title" class="front-cta__title">「何から始めればいいか」から、一緒に考えます。</h2>
		<p class="front-cta__lead">構想段階のご相談だけでも歓迎です。まずは気軽にどうぞ。</p>
		<p>
			<a class="button button--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">相談する</a>
		</p>
	</div>
</section>
