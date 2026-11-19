<?php
/**
 * Template Name: サービス — 業務効率化・DX支援
 *
 * 固定ページ「業務効率化・DX支援」（スラッグ dx なら自動で適用）。
 * 支援内容（業務の見直し・自動化・ツール導入）→ 自動化の例 → 対応ツール → 進め方 → お問い合わせ。
 * 文言はページ編集画面の ACF で変更できる（空欄なら既定文）。
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();

	// 支援内容：1 行目「見出し｜説明」、2 行目以降はチェック項目
	$fde_pillars = [];
	foreach ( [ 1, 2, 3 ] as $fde_n ) {
		$fde_lines = fde_svp_lines( fde_service_text( "dx_pillar_{$fde_n}" ) );
		if ( ! $fde_lines ) {
			continue;
		}
		$fde_head      = fde_pipe_rows( array_shift( $fde_lines ) )[0];
		$fde_pillars[] = [ 'title' => $fde_head[0], 'text' => $fde_head[1] ?? '', 'items' => $fde_lines ];
	}
	$fde_tools = fde_pipe_rows( fde_service_text( 'dx_tools' ) );
	?>
	<article class="svp svp--dx">
		<?php fde_svp_hero( 'dx', fde_service_text( 'dx_lead' ) ); ?>

		<?php if ( $fde_pillars ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'What We Do', '支援内容' ); ?>
					<div class="svp-pillars">
						<?php foreach ( $fde_pillars as $fde_i => $fde_pillar ) : ?>
							<article class="svp-pillar">
								<span class="svp-pillar__no mono"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
								<h3 class="svp-pillar__title"><?php echo esc_html( $fde_pillar['title'] ); ?></h3>
								<?php if ( '' !== $fde_pillar['text'] ) : ?>
									<p class="svp-pillar__text"><?php echo esc_html( $fde_pillar['text'] ); ?></p>
								<?php endif; ?>
								<?php fde_svp_checks( $fde_pillar['items'] ); ?>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php $fde_examples = fde_svp_lines( fde_service_text( 'dx_examples' ) ); ?>
		<?php if ( $fde_examples ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Examples', '自動化の例' ); ?>
					<?php fde_svp_checks( $fde_examples, 'grid' ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $fde_tools ) : ?>
			<section class="section section--dark svp-sec">
				<div class="section__inner">
					<?php fde_svp_head( 'Tools', '対応ツール' ); ?>
					<?php fde_svp_cards( $fde_tools, 'tools', 'brand' ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php $fde_steps = fde_pipe_rows( fde_service_text( 'dx_steps' ) ); ?>
		<?php if ( $fde_steps ) : ?>
			<section class="section svp-sec svp-sec--ink">
				<div class="section__inner">
					<?php fde_svp_head( 'Process', '進め方' ); ?>
					<?php fde_svp_steps( $fde_steps ); ?>
				</div>
			</section>
		<?php endif; ?>

		<?php fde_svp_contact(); ?>
		<?php fde_svp_others( 'dx' ); ?>
	</article>
	<?php
endwhile;

get_footer();
