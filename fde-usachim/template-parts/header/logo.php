<?php
/**
 * Header brand (text mark).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_brand    = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_brand_sl = (string) fde_option( 'brand_subtitle', '// FORWARD DEPLOYED' );
?>
<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $fde_brand ); ?>">
	<span><?php echo esc_html( $fde_brand ); ?></span>
	<?php if ( $fde_brand_sl ) : ?>
		<span class="brand__slash"><?php echo esc_html( $fde_brand_sl ); ?></span>
	<?php endif; ?>
</a>
