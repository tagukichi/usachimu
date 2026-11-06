<?php
/**
 * Site footer.
 * ロゴ・ナビ・© だけの控えめなフッター。会社の情報は固定ページ「会社概要」に置く。
 *
 * @package fde-usachim
 */

$fde_brand = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_nav   = fde_main_nav_items();
?>
</main><!-- /.site-main -->

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">

		<div class="site-footer__top">
			<div class="site-footer__id">
				<a class="site-footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( $fde_brand ); ?>">
					<img class="site-footer__logo-img" src="<?php echo esc_url( fde_brand_logo_url() ); ?>" alt="<?php echo esc_attr( $fde_brand ); ?>" width="1200" height="93" loading="lazy">
				</a>
			</div>

			<nav class="site-footer__nav" aria-label="<?php esc_attr_e( 'フッター', 'fde-usachim' ); ?>">
				<?php foreach ( $fde_nav as $item ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
		</div>

		<div class="site-footer__meta">
			<small class="site-footer__copy">© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( $fde_brand ); ?></small>
		</div>

	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
