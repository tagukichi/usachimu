<?php
/**
 * Single Work (case study detail).
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	$post_id      = get_the_ID();
	$industry     = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
	$summary      = function_exists( 'get_field' ) ? (string) get_field( 'summary' ) : '';
	$headline     = function_exists( 'get_field' ) ? (string) get_field( 'headline' ) : '';
	$headline_lbl = function_exists( 'get_field' ) ? (string) get_field( 'headline_label' ) : '';
	$year         = function_exists( 'get_field' ) ? (string) get_field( 'year' ) : '';
	$scale        = function_exists( 'get_field' ) ? (string) get_field( 'scale' ) : '';
	$role         = function_exists( 'get_field' ) ? (string) get_field( 'role' ) : '';
	$kpi          = function_exists( 'get_field' ) ? (array) get_field( 'kpi' ) : [];
	$tags         = fde_split_tags( function_exists( 'get_field' ) ? (string) get_field( 'tags' ) : '' );
	$external     = function_exists( 'get_field' ) ? (string) get_field( 'external_url' ) : '';
	?>
	<article <?php post_class( 'single-work' ); ?>>
		<header>
			<p class="page-head__eyebrow">§ Case Study</p>
			<h1 class="single-work__title"><?php the_title(); ?></h1>
			<?php if ( $summary ) : ?>
				<p class="single-work__lead jp"><?php echo nl2br( esc_html( $summary ) ); ?></p>
			<?php endif; ?>
		</header>

		<dl class="single-work__meta">
			<?php
			$rows = array_filter(
				[
					[ 'INDUSTRY', $industry ],
					[ 'YEAR',     $year ],
					[ 'SCALE',    $scale ],
					[ 'ROLE',     $role ],
					[ 'HEADLINE', $headline . ( $headline_lbl ? ' — ' . $headline_lbl : '' ) ],
					[ 'EXTERNAL', $external ],
				],
				static fn( $r ) => '' !== trim( $r[1] )
			);
			?>
			<?php foreach ( $rows as $row ) : ?>
				<div class="single-work__meta-row">
					<dt class="single-work__meta-k"><?php echo esc_html( $row[0] ); ?></dt>
					<dd class="single-work__meta-v">
						<?php if ( 'EXTERNAL' === $row[0] ) : ?>
							<a href="<?php echo esc_url( $row[1] ); ?>" target="_blank" rel="noopener" style="border-bottom:1px solid currentColor;"><?php echo esc_html( $row[1] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $row[1] ); ?>
						<?php endif; ?>
					</dd>
				</div>
			<?php endforeach; ?>
		</dl>

		<?php if ( ! empty( $kpi ) ) : ?>
			<section style="margin-bottom:48px;">
				<h2 class="page-head__eyebrow" style="margin-bottom:16px;">Key Result</h2>
				<div>
					<?php foreach ( $kpi as $row ) : ?>
						<div class="work-spread__kpi-row">
							<span class="work-spread__kpi-k jp"><?php echo esc_html( $row['k'] ?? '' ); ?></span>
							<span class="work-spread__kpi-v mono"><?php echo esc_html( $row['v'] ?? '' ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! empty( $tags ) ) : ?>
			<section style="margin-bottom:48px;">
				<h2 class="page-head__eyebrow" style="margin-bottom:16px;">Stack</h2>
				<div class="work-spread__stack">
					<?php foreach ( $tags as $t ) : ?>
						<span class="chip mono"><?php echo esc_html( $t ); ?></span>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( get_the_content() ) : ?>
			<div class="prose"><?php the_content(); ?></div>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
