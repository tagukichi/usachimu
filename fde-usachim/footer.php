<?php
/**
 * Site footer.
 *
 * @package fde-usachim
 */

$fde_brand        = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_footer_suffix = (string) fde_option( 'footer_suffix', '/fde' );
$fde_footer_left   = (string) fde_option( 'footer_meta_left', "EST. 2021 · TOKYO, JAPAN\nFORWARD DEPLOYED ENGINEER (SOLE PROPRIETOR)\nNO TRACKERS · BUILT BY HAND" );
$fde_footer_right  = (string) fde_option( 'footer_meta_right', sprintf( "v%s\n© %s %s", date_i18n( 'Y.m' ), date_i18n( 'Y' ), $fde_brand ) );
?>
</main><!-- /.site-main -->

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">
		<div class="site-footer__mark">
			<?php echo esc_html( $fde_brand ); ?><?php if ( $fde_footer_suffix ) : ?><span class="mono site-footer__mark-suffix"><?php echo esc_html( $fde_footer_suffix ); ?></span><?php endif; ?>
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
