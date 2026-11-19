<?php
/**
 * TOP — Product：自社サービス「調速」（LP の FV を引用したブロック）。
 * ブロック本体は template-parts/common/chosoku.php（システム開発ページと共通）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section section--dark product" id="product" data-section="product">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Product</h2>
			</div>
		</header>

		<?php fde_render_chosoku(); ?>
	</div>
</section>
