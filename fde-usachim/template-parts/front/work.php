<?php
/**
 * §05 — Work (case studies, alternating magazine spreads).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'works',
		'posts_per_page'      => 5,
		'orderby'             => 'menu_order date',
		'order'               => 'ASC',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

$fde_total = $fde_query->found_posts ?: $fde_query->post_count;
?>
<section class="section" id="work" data-section="work">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 05</span>
				<h2 class="sec-head__title">Selected Work — 仕事の記録。</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( sprintf( '%d CASES · 2024 → %s', $fde_total, date_i18n( 'Y' ) ) ); ?></span>
		</header>

		<?php if ( $fde_query->have_posts() ) : ?>
			<?php
			// --- Index ---
			$fde_index = [];
			$fde_i     = 0;
			?>
			<div class="work__index">
				<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); $fde_i++; ?>
					<?php
					$fde_industry = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
					$fde_index[] = [
						'id'       => sprintf( '%02d', $fde_i ),
						'industry' => $fde_industry,
						'post_id'  => get_the_ID(),
					];
					?>
					<div class="work__index-cell">
						<span class="work__index-no"><?php echo esc_html( sprintf( '%02d / %02d', $fde_i, $fde_query->post_count ) ); ?></span>
						<span class="work__index-industry"><?php echo esc_html( $fde_industry ?: get_the_title() ); ?></span>
					</div>
				<?php endwhile; ?>
			</div>

			<?php $fde_query->rewind_posts(); $fde_i = 0; ?>
			<div class="work__spreads">
				<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); $fde_i++; ?>
					<?php
					$id_str        = sprintf( '%02d', $fde_i );
					$flipped       = ( $fde_i % 2 === 0 ) ? 'true' : 'false';
					$post_id       = get_the_ID();
					$industry      = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
					$summary       = function_exists( 'get_field' ) ? (string) get_field( 'summary' ) : '';
					$headline      = function_exists( 'get_field' ) ? (string) get_field( 'headline' ) : '';
					$headline_lbl  = function_exists( 'get_field' ) ? (string) get_field( 'headline_label' ) : '';
					$year          = function_exists( 'get_field' ) ? (string) get_field( 'year' ) : '';
					$scale         = function_exists( 'get_field' ) ? (string) get_field( 'scale' ) : '';
					$role          = function_exists( 'get_field' ) ? (string) get_field( 'role' ) : '';
					$kpi           = [];
					if ( function_exists( 'get_field' ) ) {
						foreach ( [ 1, 2, 3, 4 ] as $kpi_n ) {
							$k = (string) get_field( "kpi_{$kpi_n}_k" );
							$v = (string) get_field( "kpi_{$kpi_n}_v" );
							if ( '' !== $k || '' !== $v ) {
								$kpi[] = [ 'k' => $k, 'v' => $v ];
							}
						}
					}
					$tags_raw      = function_exists( 'get_field' ) ? (string) get_field( 'tags' ) : '';
					$tags          = fde_split_tags( $tags_raw );
					$title         = get_the_title();
					$headline_len  = mb_strlen( $headline );
					$headline_attr = '';
					if ( $headline_len <= 4 ) {
						$headline_attr = 'data-short="true"';
					} elseif ( $headline_len > 6 ) {
						$headline_attr = 'data-long="true"';
					}
					?>
					<article class="work-spread" data-flipped="<?php echo esc_attr( $flipped ); ?>">
						<aside class="work-spread__poster">
							<div class="work-spread__poster-top">
								<span class="work-spread__poster-fig">FIG. <?php echo esc_html( $id_str ); ?> — Headline</span>
								<span class="work-spread__poster-year"><?php echo esc_html( $year ); ?></span>
							</div>
							<div class="work-spread__poster-headline serif" <?php echo $headline_attr; // phpcs:ignore ?>>
								<?php echo esc_html( $headline ?: '—' ); ?>
							</div>
							<div class="work-spread__poster-bottom">
								<span class="work-spread__poster-bottom-l jp"><?php echo esc_html( $headline_lbl ); ?></span>
								<span class="work-spread__poster-bottom-r"><?php echo esc_html( $role ); ?></span>
							</div>
							<span class="work-spread__poster-tick work-spread__poster-tick--tl" aria-hidden="true"></span>
							<span class="work-spread__poster-tick work-spread__poster-tick--tr" aria-hidden="true"></span>
							<span class="work-spread__poster-tick work-spread__poster-tick--bl" aria-hidden="true"></span>
							<span class="work-spread__poster-tick work-spread__poster-tick--br" aria-hidden="true"></span>
						</aside>

						<div class="work-spread__content">
							<header class="work-spread__meta">
								<div class="work-spread__meta-l">
									<span class="work-spread__id mono">
										<?php echo esc_html( $id_str ); ?>
										<span class="work-spread__id-total">/ <?php echo esc_html( sprintf( '%02d', $fde_query->post_count ) ); ?></span>
									</span>
									<?php if ( $industry ) : ?>
										<span class="work-spread__industry"><?php echo esc_html( $industry ); ?></span>
									<?php endif; ?>
								</div>
								<span class="work-spread__year-scale"><?php echo esc_html( trim( $year . ( $scale ? ' · ' . $scale : '' ) ) ); ?></span>
							</header>

							<h3 class="work-spread__title">
								<a href="<?php the_permalink(); ?>"><?php echo esc_html( $title ); ?></a>
							</h3>

							<?php if ( $summary ) : ?>
								<p class="work-spread__summary jp"><?php echo esc_html( $summary ); ?></p>
							<?php endif; ?>

							<div class="work-spread__details">
								<?php if ( ! empty( $kpi ) ) : ?>
									<div>
										<div class="work-spread__details-label">Key Result</div>
										<div>
											<?php foreach ( $kpi as $row ) : ?>
												<div class="work-spread__kpi-row">
													<span class="work-spread__kpi-k jp"><?php echo esc_html( $row['k'] ?? '' ); ?></span>
													<span class="work-spread__kpi-v mono"><?php echo esc_html( $row['v'] ?? '' ); ?></span>
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if ( ! empty( $tags ) ) : ?>
									<div>
										<div class="work-spread__details-label">Stack</div>
										<div class="work-spread__stack">
											<?php foreach ( $tags as $t ) : ?>
												<span class="chip mono"><?php echo esc_html( $t ); ?></span>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

		<?php else : ?>
			<p class="work__disclaimer jp">実績データはまだありません。管理画面 →「実績」から登録してください。</p>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
