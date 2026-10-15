<?php
/**
 * Header brand — テーマ同梱の CHIM WORKS ワードマーク。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_brand    = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
?>
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $fde_brand ); ?>">
	<img class="brand__logo" src="<?php echo esc_url( fde_brand_logo_url() ); ?>" alt="<?php echo esc_attr( $fde_brand ); ?>" width="1200" height="93">
</a>
