<?php
/**
 * Fullscreen nav overlay panel.
 * 全ブレークポイントで使用。開閉は nav.js（ARIA / フォーカストラップ）、
 * 演出は motion.js が data-nav-open の変化を監視して駆動する。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_items   = fde_main_nav_items();
$fde_items[] = [ 'label' => 'Contact', 'url' => home_url( '/' ) . '#contact' ];

$fde_panel_email = (string) fde_option( 'contact_email', '' );
$fde_panel_x_url = (string) fde_option( 'sns_x_url', '' );
$fde_panel_x_h   = (string) fde_option( 'sns_x_handle', '' );
?>
<div
	id="nav-panel"
	class="nav-panel"
	aria-label="<?php esc_attr_e( 'メニュー', 'fde-usachim' ); ?>"
	data-nav-panel
>
	<span class="nav-panel__curtain" aria-hidden="true"></span>

	<button
		type="button"
		class="nav-panel__close"
		aria-label="<?php esc_attr_e( 'メニューを閉じる', 'fde-usachim' ); ?>"
		data-nav-close
	>
		<span class="nav-panel__close-bar" aria-hidden="true"></span>
		<span class="nav-panel__close-bar" aria-hidden="true"></span>
	</button>

	<div class="nav-panel__inner">
		<ul class="nav-panel__list">
			<?php foreach ( $fde_items as $fde_i => $item ) : ?>
				<li class="nav-panel__item">
					<a href="<?php echo esc_url( $item['url'] ); ?>" data-nav-link>
						<span class="nav-panel__no mono"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
						<span class="nav-panel__label"><?php echo esc_html( $item['label'] ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php if ( $fde_panel_email || $fde_panel_x_url ) : ?>
			<div class="nav-panel__meta">
				<?php if ( $fde_panel_email ) : ?>
					<div class="nav-panel__meta-row">
						<span class="nav-panel__meta-k mono">EMAIL</span>
						<a class="nav-panel__meta-v" href="mailto:<?php echo esc_attr( $fde_panel_email ); ?>"><?php echo esc_html( $fde_panel_email ); ?></a>
					</div>
				<?php endif; ?>
				<?php if ( $fde_panel_x_url ) : ?>
					<div class="nav-panel__meta-row">
						<span class="nav-panel__meta-k mono">X</span>
						<a class="nav-panel__meta-v" href="<?php echo esc_url( $fde_panel_x_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $fde_panel_x_h ? $fde_panel_x_h : 'X' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
