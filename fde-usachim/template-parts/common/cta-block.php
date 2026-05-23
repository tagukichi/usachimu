<?php
/**
 * Reusable CTA block (used at the bottom of inner pages).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_cta_title = $args['title'] ?? '「何から始めればいいか」から、一緒に考えます。';
$fde_cta_lead  = $args['lead']  ?? '構想段階のご相談だけでも歓迎です。お気軽にどうぞ。';
?>
<section class="cta-block section" aria-labelledby="cta-block-title">
	<div class="container container--narrow">
		<h2 id="cta-block-title" class="cta-block__title"><?php echo esc_html( $fde_cta_title ); ?></h2>
		<p class="cta-block__lead"><?php echo esc_html( $fde_cta_lead ); ?></p>
		<p>
			<a class="button button--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">相談する</a>
		</p>
	</div>
</section>
