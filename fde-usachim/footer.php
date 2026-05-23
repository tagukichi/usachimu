<?php
/**
 * Site footer.
 *
 * @package fde-usachim
 */
?>
</main><!-- /.site-main -->

<footer class="site-footer" role="contentinfo">
	<div class="container site-footer__inner">
		<div class="site-footer__brand">
			<span class="site-footer__brand-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<p class="site-footer__brand-bio"><?php esc_html_e( '中小企業の DX を、構想からプロダクションまで伴走する Forward Deployed Engineer。', 'fde-usachim' ); ?></p>
		</div>

		<?php
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu(
				[
					'theme_location' => 'footer',
					'container'      => 'nav',
					'menu_class'     => 'site-footer__nav',
					'depth'          => 1,
					'fallback_cb'    => false,
				]
			);
		}
		?>

		<?php
		$fde_sns_x        = fde_option( 'sns_x_url' );
		$fde_sns_facebook = fde_option( 'sns_facebook_url' );
		if ( $fde_sns_x || $fde_sns_facebook ) :
			?>
			<div class="site-footer__social">
				<?php if ( $fde_sns_x ) : ?>
					<a href="<?php echo esc_url( $fde_sns_x ); ?>" rel="noopener" target="_blank" aria-label="X (Twitter)">X</a>
				<?php endif; ?>
				<?php if ( $fde_sns_facebook ) : ?>
					<a href="<?php echo esc_url( $fde_sns_facebook ); ?>" rel="noopener" target="_blank" aria-label="Facebook">Facebook</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<p class="site-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
