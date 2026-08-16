<?php
/**
 * Header brand — custom logo image (falls back to brand name).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_brand    = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_logo_id  = (int) get_theme_mod( 'custom_logo' );
$fde_logo_src = $fde_logo_id ? wp_get_attachment_image_src( $fde_logo_id, 'full' ) : false;
?>
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $fde_brand ); ?>">
	<?php if ( $fde_logo_src ) : ?>
		<img class="brand__logo" src="<?php echo esc_url( $fde_logo_src[0] ); ?>" alt="<?php echo esc_attr( $fde_brand ); ?>" width="<?php echo (int) $fde_logo_src[1]; ?>" height="<?php echo (int) $fde_logo_src[2]; ?>">
	<?php else : ?>
		<span class="brand__text"><?php echo esc_html( $fde_brand ); ?></span>
	<?php endif; ?>
</a>
