<?php
/**
 * Site footer.
 * ロゴの下に会社概要と連絡先メールを控えめに記載する（TOP 固定ページの
 * ACF から取得。入力済みの行だけ並ぶ）。
 *
 * @package fde-usachim
 */

$fde_brand    = (string) fde_option( 'brand_name', get_bloginfo( 'name' ) );
$fde_nav      = fde_main_nav_items();
$fde_logo_id  = (int) get_theme_mod( 'custom_logo' );
$fde_logo_src = $fde_logo_id ? wp_get_attachment_image_src( $fde_logo_id, 'full' ) : false;

$fde_company_rows = [];
foreach (
	[
		'company_name'     => [ '商号', '合同会社CHIM WORKS' ],
		'company_founded'  => [ '設立', '' ],
		'company_ceo'      => [ '代表者', '' ],
		'company_capital'  => [ '資本金', '' ],
		'company_address'  => [ '所在地', '' ],
		'company_business' => [ '事業内容', "WEB制作・システム開発\n業務効率化・DX支援" ],
	] as $fde_ck => $fde_cl
) {
	$fde_cv = (string) fde_field( $fde_ck, $fde_cl[1] );
	if ( '' !== trim( $fde_cv ) ) {
		$fde_company_rows[] = [ 'k' => $fde_cl[0], 'v' => $fde_cv ];
	}
}
$fde_contact = fde_contact_email();

foreach ( [ 1, 2 ] as $fde_cn ) {
	$fde_ck = (string) fde_field( "company_extra_{$fde_cn}_k", '' );
	$fde_cv = (string) fde_field( "company_extra_{$fde_cn}_v", '' );
	if ( '' !== trim( $fde_ck ) && '' !== trim( $fde_cv ) ) {
		$fde_company_rows[] = [ 'k' => $fde_ck, 'v' => $fde_cv ];
	}
}
?>
</main><!-- /.site-main -->

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__inner">

		<div class="site-footer__top">
			<div class="site-footer__id" id="company">
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

				<?php if ( $fde_company_rows || $fde_contact ) : ?>
					<dl class="footer-company" aria-label="<?php esc_attr_e( '会社概要', 'fde-usachim' ); ?>">
						<?php foreach ( $fde_company_rows as $fde_row ) : ?>
							<div class="footer-company__row">
								<dt class="footer-company__k"><?php echo esc_html( $fde_row['k'] ); ?></dt>
								<dd class="footer-company__v"><?php echo nl2br( esc_html( $fde_row['v'] ) ); ?></dd>
							</div>
						<?php endforeach; ?>
						<?php if ( $fde_contact ) : ?>
							<div class="footer-company__row">
								<dt class="footer-company__k">連絡先</dt>
								<dd class="footer-company__v"><a href="mailto:<?php echo esc_attr( $fde_contact ); ?>"><?php echo esc_html( $fde_contact ); ?></a></dd>
							</div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>
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
