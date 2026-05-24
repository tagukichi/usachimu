<?php
/**
 * Mobile nav overlay panel.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_items = fde_main_nav_items();
$fde_items[] = [ 'label' => 'Contact', 'url' => home_url( '/' ) . '#contact' ];
$fde_cta_label = (string) fde_option( 'cta_label', '相談を始める →' );
?>
<div
	id="nav-panel"
	class="nav-panel"
	aria-label="<?php esc_attr_e( 'モバイルメニュー', 'fde-usachim' ); ?>"
	data-nav-panel
>
	<ul class="nav-panel__list">
		<?php foreach ( $fde_items as $item ) : ?>
			<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<a class="nav-panel__cta" href="<?php echo esc_url( home_url( '/' ) . '#contact' ); ?>">
		<?php echo esc_html( $fde_cta_label ); ?>
	</a>
</div>
