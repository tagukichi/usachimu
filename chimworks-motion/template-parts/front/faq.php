<?php
/**
 * TOP — よくある質問（開閉できる一覧）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_items = fde_pipe_rows( fde_lp_text( 'faq_items' ) );
if ( ! $fde_items ) {
	return;
}
?>
<section class="section section--dark lp-faq" id="faq" data-section="faq">
	<div class="section__inner">
		<?php fde_svp_head( 'FAQ', 'よくある質問' ); ?>
		<div class="lp-faq__list">
			<?php foreach ( $fde_items as $fde_i => $fde_item ) : ?>
				<details class="lp-faq__item"<?php echo 0 === $fde_i ? ' open' : ''; ?>>
					<summary class="lp-faq__q"><span class="lp-faq__mark mono" aria-hidden="true">Q</span><span class="lp-faq__q-text"><?php echo esc_html( $fde_item[0] ); ?></span><span class="lp-faq__icon" aria-hidden="true"></span></summary>
					<?php if ( ! empty( $fde_item[1] ) ) : ?>
						<div class="lp-faq__a"><span class="lp-faq__mark lp-faq__mark--a mono" aria-hidden="true">A</span><p><?php echo esc_html( $fde_item[1] ); ?></p></div>
					<?php endif; ?>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
