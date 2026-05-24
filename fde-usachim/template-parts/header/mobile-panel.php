<?php
/**
 * Mobile nav overlay panel.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_home = home_url( '/' );
$fde_items = [
	[ 'label' => 'About',    'url' => $fde_home . '#about' ],
	[ 'label' => 'Services', 'url' => $fde_home . '#services' ],
	[ 'label' => 'Process',  'url' => $fde_home . '#process' ],
	[ 'label' => 'Work',     'url' => $fde_home . '#work' ],
	[ 'label' => 'Stack',    'url' => $fde_home . '#stack' ],
	[ 'label' => 'Writing',  'url' => $fde_home . '#writing' ],
	[ 'label' => 'Contact',  'url' => $fde_home . '#contact' ],
];
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
	<a class="nav-panel__cta" href="<?php echo esc_url( $fde_home . '#contact' ); ?>">
		<?php echo esc_html( $fde_cta_label ); ?>
	</a>
</div>
