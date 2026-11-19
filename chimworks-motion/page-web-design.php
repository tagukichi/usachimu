<?php
/**
 * Template Name: サービス — WEB制作
 *
 * 固定ページ「WEB制作」（スラッグ web-design なら自動で適用）。
 * 対応できるサイトの種類 → 制作事例（/web のカスタム投稿）→ 料金表 → お問い合わせ。
 * 文言はページ編集画面の ACF で変更できる（空欄なら既定文）。
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();

	// 制作事例：/web で公開しているカスタム投稿の新着
	$fde_works_type  = fde_works_post_type();
	$fde_works_count = (int) fde_page_field( 'web_works_count', 6 );
	$fde_works       = null;
	if ( '' !== $fde_works_type ) {
		$fde_works = new WP_Query(
			[
				'post_type'           => $fde_works_type,
				'posts_per_page'      => $fde_works_count > 0 ? $fde_works_count : 6,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			]
		);
	}
	$fde_prices = fde_pipe_rows( fde_service_text( 'web_prices' ) );
	?>
	<article class="svp svp--web">
		<?php fde_svp_hero( 'web', fde_service_text( 'web_lead' ) ); ?>

		<section class="section section--dark svp-sec">
			<div class="section__inner">
				<?php fde_svp_head( 'Website Types', '対応できるWebサイト' ); ?>
				<?php fde_svp_cards( fde_pipe_rows( fde_service_text( 'web_types' ) ), 'types' ); ?>
			</div>
		</section>

		<?php if ( $fde_works && $fde_works->have_posts() ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Works', '制作事例' ); ?>
					<div class="svp-works">
						<?php
						while ( $fde_works->have_posts() ) :
							$fde_works->the_post();
							$fde_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
							$fde_cat   = '';
							foreach ( get_object_taxonomies( $fde_works_type ) as $fde_tax ) {
								$fde_terms = get_the_terms( get_the_ID(), $fde_tax );
								if ( is_array( $fde_terms ) && $fde_terms ) {
									$fde_cat = $fde_terms[0]->name;
									break;
								}
							}
							?>
							<a class="svp-work" href="<?php the_permalink(); ?>">
								<div class="svp-work__thumb">
									<?php if ( $fde_thumb ) : ?>
										<img src="<?php echo esc_url( $fde_thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
									<?php else : ?>
										<span class="svp-work__ph" aria-hidden="true"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
									<?php endif; ?>
								</div>
								<div class="svp-work__body">
									<?php if ( $fde_cat ) : ?>
										<span class="svp-work__cat mono"><?php echo esc_html( $fde_cat ); ?></span>
									<?php endif; ?>
									<h3 class="svp-work__title"><?php echo esc_html( get_the_title() ); ?></h3>
								</div>
							</a>
						<?php endwhile; ?>
					</div>
					<?php
					wp_reset_postdata();
					$fde_archive = get_post_type_archive_link( $fde_works_type );
					if ( $fde_archive ) :
						?>
						<p class="svp-more"><a class="svp-more__link" href="<?php echo esc_url( $fde_archive ); ?>">制作事例をもっと見る →</a></p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $fde_prices ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Price', '料金' ); ?>
					<div class="svp-prices">
						<?php foreach ( $fde_prices as $fde_price ) : ?>
							<article class="svp-price">
								<h3 class="svp-price__name"><?php echo esc_html( $fde_price[0] ); ?></h3>
								<?php if ( ! empty( $fde_price[1] ) ) : ?>
									<p class="svp-price__value"><?php echo esc_html( $fde_price[1] ); ?></p>
								<?php endif; ?>
								<?php if ( ! empty( $fde_price[2] ) ) : ?>
									<p class="svp-price__desc"><?php echo esc_html( $fde_price[2] ); ?></p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
					<?php $fde_note = fde_service_text( 'web_price_note' ); ?>
					<?php if ( '' !== $fde_note ) : ?>
						<p class="svp-note"><?php echo nl2br( esc_html( $fde_note ) ); ?></p>
					<?php endif; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php fde_svp_contact(); ?>
		<?php fde_svp_others( 'web' ); ?>
	</article>
	<?php
endwhile;

get_footer();
