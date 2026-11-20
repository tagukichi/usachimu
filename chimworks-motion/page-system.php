<?php
/**
 * Template Name: サービス — システム開発
 *
 * 固定ページ「システム開発」（スラッグ system なら自動で適用）。
 * 開発できるもの → 技術スタック → 開発の進め方 → 自社サービス（調速。TOP と同じブロック）→ お問い合わせ。
 * 文言はページ編集画面の ACF で変更できる（空欄なら既定文）。
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	$fde_stack = fde_pipe_rows( fde_service_text( 'sys_stack' ) );
	?>
	<article class="svp svp--system">
		<?php fde_svp_hero( 'system', fde_service_text( 'sys_lead' ) ); ?>

		<section class="section section--dark svp-sec">
			<div class="section__inner">
				<?php fde_svp_head( 'What We Build', '開発できるもの' ); ?>
				<?php fde_svp_cards( fde_pipe_rows( fde_service_text( 'sys_products' ) ), 'products' ); ?>
			</div>
		</section>

		<?php if ( $fde_stack ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Tech Stack', '技術スタック' ); ?>
					<dl class="svp-stack">
						<?php foreach ( $fde_stack as $fde_row ) : ?>
							<div class="svp-stack__row">
								<dt class="svp-stack__k"><?php echo esc_html( $fde_row[0] ); ?></dt>
								<dd class="svp-stack__v">
									<ul class="svp-chips">
										<?php foreach ( preg_split( '/[、,]/u', $fde_row[1] ?? '' ) ?: [] as $fde_tech ) : ?>
											<?php
											$fde_tech = trim( $fde_tech );
											if ( '' === $fde_tech ) {
												continue;
											}
											$fde_logo = fde_brand_icon( $fde_tech );
											?>
											<li>
												<?php if ( $fde_logo ) : ?>
													<img class="svp-chips__icon" src="<?php echo esc_url( $fde_logo ); ?>" alt="" width="22" height="22" loading="lazy">
												<?php endif; ?>
												<?php echo esc_html( $fde_tech ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</dd>
							</div>
						<?php endforeach; ?>
					</dl>
				</div>
			</section>
		<?php endif; ?>

		<?php $fde_steps = fde_pipe_rows( fde_service_text( 'sys_steps' ) ); ?>
		<?php if ( $fde_steps ) : ?>
			<section class="section svp-sec svp-sec--ink">
				<div class="section__inner">
					<?php fde_svp_head( 'Process', '開発の進め方' ); ?>
					<?php fde_svp_steps( $fde_steps ); ?>
				</div>
			</section>
		<?php endif; ?>

		<section class="section section--dark svp-sec svp-sec--product" id="product">
			<div class="section__inner">
				<?php fde_svp_head( 'Our Product', '自社サービス' ); ?>
				<?php $fde_product_lead = fde_service_text( 'sys_product_lead' ); ?>
				<?php if ( '' !== $fde_product_lead ) : ?>
					<p class="svp-sec__lead"><?php echo nl2br( esc_html( $fde_product_lead ) ); ?></p>
				<?php endif; ?>
				<?php fde_render_chosoku(); ?>
			</div>
		</section>

		<?php fde_svp_contact( 'system' ); ?>
		<?php fde_svp_others( 'system' ); ?>
	</article>
	<?php
endwhile;

get_footer();
