<?php
/**
 * §04 — Contact (dark, CF7).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_meta  = (string) fde_field( 'contact_meta', 'RESPONSE WITHIN 1 BIZ DAY' );
$fde_lead  = (string) fde_field( 'contact_lead', 'まずはお気軽にご相談ください。' );
$fde_note  = (string) fde_field( 'contact_note', "お仕事のご依頼・ご相談、研修のお問い合わせなど、\nなんでもお気軽にどうぞ。" );
$fde_cf7   = (string) fde_field( 'cf7_shortcode', '' );

$fde_email = (string) fde_option( 'contact_email', '' );
$fde_x_url = (string) fde_option( 'sns_x_url', '' );
$fde_x_h   = (string) fde_option( 'sns_x_handle', '' );
?>
<section class="section section--dark section--decor" id="contact" data-section="contact">
	<?php fde_tri_field( 'tr', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">05</span>
				<h2 class="sec-head__title">Contact</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( $fde_meta ); ?></span>
		</header>

		<a class="bigcta" href="#contact-form" data-bigcta>
			<span class="bigcta__text" data-bigcta-text>LET'S TALK</span>
			<span class="bigcta__arrow" aria-hidden="true">→</span>
		</a>

		<div class="contact__grid contact__grid--single">
			<aside class="contact__aside">
				<p class="contact__aside-lead"><?php echo esc_html( $fde_lead ); ?></p>
				<p class="contact__aside-note jp"><?php echo nl2br( esc_html( $fde_note ) ); ?></p>

				<div class="contact__channels">
					<?php if ( $fde_email ) : ?>
						<div class="contact__channel">
							<span class="contact__channel-k">EMAIL</span>
							<span class="contact__channel-v"><a href="mailto:<?php echo esc_attr( $fde_email ); ?>"><?php echo esc_html( $fde_email ); ?></a></span>
						</div>
					<?php endif; ?>
					<?php if ( $fde_x_h || $fde_x_url ) : ?>
						<div class="contact__channel">
							<span class="contact__channel-k">X (Twitter)</span>
							<span class="contact__channel-v"><?php if ( $fde_x_url ) : ?><a href="<?php echo esc_url( $fde_x_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $fde_x_h ?: $fde_x_url ); ?></a><?php else : ?><?php echo esc_html( $fde_x_h ); ?><?php endif; ?></span>
						</div>
					<?php endif; ?>
				</div>
			</aside>

			<div class="contact__form" id="contact-form">
				<?php if ( $fde_cf7 ) : ?>
					<?php echo do_shortcode( $fde_cf7 ); ?>
				<?php else : ?>
					<p class="contact__aside-note jp">
						フォーム未設定です。<br>
						固定ページ編集画面の「§08 Contact → CF7 ショートコード」に Contact Form 7 のショートコードを貼り付けてください。
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
