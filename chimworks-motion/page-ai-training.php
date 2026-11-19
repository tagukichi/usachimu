<?php
/**
 * Template Name: サービス — AI研修・勉強会
 *
 * 固定ページ「AI研修・勉強会」（スラッグ ai-training なら自動で適用）。
 * これまでの実績 → 研修メニュー → 環境に合わせた対応 → 料金（別途お見積もり）→ お問い合わせ。
 * 文言はページ編集画面の ACF で変更できる（空欄なら既定文）。
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	$fde_record     = fde_service_text( 'ai_record' );
	$fde_record_url = esc_url_raw( trim( (string) fde_page_field( 'ai_record_url', '' ) ) );
	$fde_price_note = fde_service_text( 'ai_price_note' );
	?>
	<article class="svp svp--ai">
		<?php fde_svp_hero( 'ai', fde_service_text( 'ai_lead' ) ); ?>

		<?php if ( '' !== $fde_record ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Record', 'これまでの実績' ); ?>
					<div class="svp-record">
						<p class="svp-record__text"><?php echo nl2br( esc_html( $fde_record ) ); ?></p>
						<?php if ( '' !== $fde_record_url ) : ?>
							<a class="svp-more__link" href="<?php echo esc_url( $fde_record_url ); ?>">研修の様子をブログで読む →</a>
						<?php endif; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="section section--dark svp-sec">
			<div class="section__inner">
				<?php fde_svp_head( 'Menu', '研修メニュー' ); ?>
				<?php fde_svp_cards( fde_pipe_rows( fde_service_text( 'ai_menu' ) ), 'menu' ); ?>
			</div>
		</section>

		<?php $fde_flex = fde_pipe_rows( fde_service_text( 'ai_flex' ) ); ?>
		<?php if ( $fde_flex ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Flexible', '環境に合わせて柔軟に対応' ); ?>
					<?php fde_svp_cards( $fde_flex, 'flex' ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( '' !== $fde_price_note ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Price', '料金' ); ?>
					<div class="svp-estimate">
						<p class="svp-estimate__v">別途お見積もり</p>
						<p class="svp-estimate__text"><?php echo nl2br( esc_html( $fde_price_note ) ); ?></p>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php fde_svp_contact(); ?>
		<?php fde_svp_others( 'ai' ); ?>
	</article>
	<?php
endwhile;

get_footer();
