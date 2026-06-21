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
?>
<nav class="site-nav" aria-label="<?php esc_attr_e( 'プライマリ', 'fde-usachim' ); ?>">
	<?php foreach ( $fde_nav as $item ) : ?>
		<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
	<?php endforeach; ?>
</nav>

<div class="site-header__right">
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
