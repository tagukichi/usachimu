<?php
/**
 * Desktop header nav + CTA + hamburger toggle.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_home = home_url( '/' );
$fde_nav  = [
	[ 'label' => 'About',    'url' => $fde_home . '#about' ],
	[ 'label' => 'Services', 'url' => $fde_home . '#services' ],
	[ 'label' => 'Process',  'url' => $fde_home . '#process' ],
	[ 'label' => 'Work',     'url' => $fde_home . '#work' ],
	[ 'label' => 'Stack',    'url' => $fde_home . '#stack' ],
	[ 'label' => 'Writing',  'url' => $fde_home . '#writing' ],
];
$fde_cta_url   = $fde_home . '#contact';
$fde_cta_label = (string) fde_option( 'cta_label', '相談を始める →' );
?>
<nav class="site-nav" aria-label="<?php esc_attr_e( 'プライマリ', 'fde-usachim' ); ?>">
	<?php foreach ( $fde_nav as $item ) : ?>
		<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
	<?php endforeach; ?>
</nav>

<div class="site-header__right">
	<a class="site-cta" href="<?php echo esc_url( $fde_cta_url ); ?>"><?php echo esc_html( $fde_cta_label ); ?></a>
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
