<?php
/**
 * Contact — フォームは置かず、メールアドレスと「営業お断り」を本文サイズの 1 行で。
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

// 1 行の案内文。{email} の位置にメールアドレスのリンクが入る
$fde_lead   = (string) fde_field( 'contact_lead', 'お問い合わせは {email} まで。' );
$fde_notice = (string) fde_field( 'contact_notice', '営業・勧誘のご連絡はお断りしております。' );

$fde_link = sprintf( '<a class="contact-mail__addr" href="mailto:%1$s">%2$s</a>', esc_attr( $fde_email ), esc_html( $fde_email ) );
// アドレスと直後の「まで。」は折り返さずに一塊で扱う
if ( false !== strpos( $fde_lead, '{email}' ) ) {
	list( $fde_before, $fde_after ) = array_pad( explode( '{email}', $fde_lead, 2 ), 2, '' );
	$fde_line = esc_html( $fde_before ) . '<span class="contact-mail__nowrap">' . $fde_link . esc_html( $fde_after ) . '</span>';
} else {
	$fde_line = esc_html( $fde_lead ) . ' <span class="contact-mail__nowrap">' . $fde_link . '</span>';
}
if ( '' !== trim( $fde_notice ) ) {
	// PC では同じ行に続け、スマホでは 2 行目に送る（語の途中で折れないように）
	$fde_line .= '<span class="contact-mail__notice">' . esc_html( $fde_notice ) . '</span>';
}
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

		<p class="contact-mail jp"><?php echo $fde_line; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 各部品を esc_html / esc_attr 済み ?></p>
	</div>
</section>
