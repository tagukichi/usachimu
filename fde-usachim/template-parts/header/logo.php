<?php
/**
 * Header logo (text logo, swap with SVG later).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
</a>
