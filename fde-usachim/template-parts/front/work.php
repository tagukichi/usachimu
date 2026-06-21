<?php
/**
 * §04 — Work (simplified case grid).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'works',
		'posts_per_page'      => 6,
		'orderby'             => 'menu_order date',
		'order'               => 'ASC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

$fde_total = $fde_query->found_posts ?: $fde_query->post_count;
?>
<section class="section section--decor" id="work" data-section="work">
	<?php fde_tri_field( 'bl', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 04</span>
				<h2 class="sec-head__title">Work</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( sprintf( '%d CASES', $fde_total ) ); ?></span>
		</header>

		<?php if ( $fde_query->have_posts() ) : ?>
			<div class="wk">
				<?php $fde_i = 0; while ( $fde_query->have_posts() ) : $fde_query->the_post(); $fde_i++; ?>
					<?php
					$industry = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
					$summary  = function_exists( 'get_field' ) ? (string) get_field( 'summary' ) : '';
					$headline = function_exists( 'get_field' ) ? (string) get_field( 'headline' ) : '';
					$headline_lbl = function_exists( 'get_field' ) ? (string) get_field( 'headline_label' ) : '';
					$year     = function_exists( 'get_field' ) ? (string) get_field( 'year' ) : '';
					$tags     = fde_split_tags( function_exists( 'get_field' ) ? (string) get_field( 'tags' ) : '' );
					?>
					<a class="wk__item" href="<?php the_permalink(); ?>">
						<div class="wk__top">
							<span class="wk__no mono"><?php echo esc_html( sprintf( '%02d', $fde_i ) ); ?></span>
							<span class="wk__year mono"><?php echo esc_html( $year ); ?></span>
						</div>
						<?php if ( $headline ) : ?>
							<div class="wk__kpi serif"><?php echo esc_html( $headline ); ?></div>
							<?php if ( $headline_lbl ) : ?>
								<div class="wk__kpi-label jp"><?php echo esc_html( $headline_lbl ); ?></div>
							<?php endif; ?>
						<?php endif; ?>
						<h3 class="wk__title"><?php the_title(); ?></h3>
						<?php if ( $industry ) : ?>
							<span class="wk__industry jp"><?php echo esc_html( $industry ); ?></span>
						<?php endif; ?>
						<?php if ( $summary ) : ?>
							<p class="wk__summary jp"><?php echo esc_html( wp_trim_words( $summary, 38, '…' ) ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $tags ) ) : ?>
							<div class="wk__tags">
								<?php foreach ( array_slice( $tags, 0, 4 ) as $t ) : ?>
									<span class="chip mono"><?php echo esc_html( $t ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</a>
				<?php endwhile; ?>
			</div>
		<?php else : ?>
			<p class="wk__empty jp">実績データはまだありません。管理画面 →「実績」から登録してください。</p>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
