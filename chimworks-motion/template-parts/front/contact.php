<?php
/**
 * Contact — フォームは置かず、メールアドレスと「営業お断り」の一文だけ。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_email = fde_contact_email();
if ( '' === $fde_email ) {
	return;
}

$fde_lead   = (string) fde_field( 'contact_lead', 'お問い合わせは、下記のメールアドレスまでご連絡ください。' );
$fde_notice = (string) fde_field( 'contact_notice', '※ 営業・勧誘を目的としたご連絡はお断りしております。' );
?>
<section class="section section--dark contact" id="contact" data-section="contact">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Contact</h2>
			</div>
			<span class="sec-head__meta">お問い合わせ</span>
		</header>

		<div class="contact-mail">
			<?php if ( '' !== trim( $fde_lead ) ) : ?>
				<p class="contact-mail__lead jp"><?php echo esc_html( $fde_lead ); ?></p>
			<?php endif; ?>

			<a class="contact-mail__addr" href="mailto:<?php echo esc_attr( $fde_email ); ?>">
				<span class="contact-mail__text"><?php echo esc_html( $fde_email ); ?></span>
				<span class="contact-mail__arrow" aria-hidden="true">→</span>
			</a>

			<?php if ( '' !== trim( $fde_notice ) ) : ?>
				<p class="contact-mail__notice jp"><?php echo esc_html( $fde_notice ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
