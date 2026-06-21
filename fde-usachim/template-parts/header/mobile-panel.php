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
?>
<div
	id="nav-panel"
	class="nav-panel"
	aria-label="<?php esc_attr_e( 'モバイルメニュー', 'fde-usachim' ); ?>"
	data-nav-panel
>
	<button
		type="button"
		class="nav-panel__close"
		aria-label="<?php esc_attr_e( 'メニューを閉じる', 'fde-usachim' ); ?>"
		data-nav-close
	>
		<span class="nav-panel__close-bar" aria-hidden="true"></span>
		<span class="nav-panel__close-bar" aria-hidden="true"></span>
	</button>

	<ul class="nav-panel__list">
		<?php foreach ( $fde_items as $item ) : ?>
			<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
