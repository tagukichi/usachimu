<?php
/**
 * Header navigation (desktop + mobile overlay).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<button
	type="button"
	class="site-nav__toggle"
	aria-expanded="false"
	aria-controls="site-nav-panel"
	aria-label="<?php esc_attr_e( 'メニューを開く', 'fde-usachim' ); ?>"
	data-nav-toggle
>
	<span class="site-nav__toggle-bar" aria-hidden="true"></span>
	<span class="site-nav__toggle-bar" aria-hidden="true"></span>
</button>

<nav
	id="site-nav-panel"
	class="site-nav"
	aria-label="<?php esc_attr_e( 'プライマリ', 'fde-usachim' ); ?>"
	data-nav-panel
>
	<?php
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu(
			[
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'site-nav__list',
				'depth'          => 1,
				'fallback_cb'    => false,
			]
		);
	} else {
		?>
		<ul class="site-nav__list">
			<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
			<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Services</a></li>
			<li><a href="<?php echo esc_url( home_url( '/works/' ) ); ?>">Works</a></li>
			<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
		</ul>
		<?php
	}
	?>
	<a class="button button--primary site-nav__cta" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
		<?php esc_html_e( 'お問い合わせ', 'fde-usachim' ); ?>
	</a>
</nav>
