<?php
/**
 * Header navigation.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="site-nav" aria-label="<?php esc_attr_e( 'プライマリ', 'fde-usachim' ); ?>">
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
