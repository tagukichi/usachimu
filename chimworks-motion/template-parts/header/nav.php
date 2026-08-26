<?php
/**
 * Desktop header nav + CTA + hamburger toggle.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_nav       = fde_main_nav_items();
$fde_cta_label = (string) fde_option( 'cta_label', 'お問い合わせ' );

// PC はこのナビだけが導線になる（ハンバーガー / CTA は CSS で非表示）
// ため、Contact を末尾に加えて問い合わせ動線を確保する。
$fde_nav[] = [
	'label' => 'Contact',
	'url'   => home_url( '/' ) . '#contact',
];
?>
<nav class="site-nav" aria-label="<?php esc_attr_e( 'プライマリ', 'fde-usachim' ); ?>">
	<?php foreach ( $fde_nav as $item ) : ?>
		<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
	<?php endforeach; ?>
</nav>

<div class="site-header__right">
	<a class="site-cta" href="<?php echo esc_url( home_url( '/' ) . '#contact' ); ?>"><?php echo esc_html( $fde_cta_label ); ?></a>
	<button
		type="button"
		class="nav-toggle"
		aria-expanded="false"
		aria-controls="nav-panel"
		aria-label="<?php esc_attr_e( 'メニューを開く', 'fde-usachim' ); ?>"
		data-nav-toggle
	>
		<span class="nav-toggle__bar" aria-hidden="true"></span>
		<span class="nav-toggle__bar" aria-hidden="true"></span>
		<span class="nav-toggle__bar" aria-hidden="true"></span>
	</button>
</div>
