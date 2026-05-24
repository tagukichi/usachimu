<?php
/**
 * Site footer.
 *
 * @package fde-usachim
 */

$fde_brand        = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_footer_left  = (string) fde_option( 'footer_meta_left', "EST. 2021 · TOKYO, JAPAN\nFORWARD DEPLOYED ENGINEER (SOLE PROPRIETOR)\nNO TRACKERS · BUILT BY HAND" );
$fde_footer_right = (string) fde_option( 'footer_meta_right', sprintf( "v%s\n© %s %s", date_i18n( 'Y.m' ), date_i18n( 'Y' ), $fde_brand ) );
$fde_nav          = fde_main_nav_items();
$fde_logo_id      = (int) get_theme_mod( 'custom_logo' );
$fde_logo_src     = $fde_logo_id ? wp_get_attachment_image_src( $fde_logo_id, 'full' ) : false;
?>
</main><!-- /.site-main -->

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">

		<div class="site-footer__top">
			<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $fde_brand ); ?>">
				<?php if ( $fde_logo_src ) : ?>
					<img
						src="<?php echo esc_url( $fde_logo_src[0] ); ?>"
						alt="<?php echo esc_attr( $fde_brand ); ?>"
						width="<?php echo (int) $fde_logo_src[1]; ?>"
						height="<?php echo (int) $fde_logo_src[2]; ?>"
						loading="lazy"
					>
				<?php else : ?>
					<span class="site-footer__logo-text"><?php echo esc_html( $fde_brand ); ?></span>
				<?php endif; ?>
			</a>

			<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'フッター', 'fde-usachim' ); ?>">
				<?php foreach ( $fde_nav as $item ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
		</div>

		<div class="site-footer__meta">
			<div><?php echo nl2br( esc_html( $fde_footer_left ) ); ?></div>
			<div><?php echo nl2br( esc_html( $fde_footer_right ) ); ?></div>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
