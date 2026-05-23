<?php
/**
 * Single work (case study).
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();

	$fde_id          = get_the_ID();
	$fde_client      = function_exists( 'get_field' ) ? (string) get_field( 'client_name' ) : '';
	$fde_industry    = function_exists( 'get_field' ) ? (string) get_field( 'client_industry' ) : '';
	$fde_period      = function_exists( 'get_field' ) ? (string) get_field( 'project_period' ) : '';
	$fde_role        = function_exists( 'get_field' ) ? (string) get_field( 'project_role' ) : '';
	$fde_tech        = function_exists( 'get_field' ) ? (string) get_field( 'tech_stack' ) : '';
	$fde_challenge   = function_exists( 'get_field' ) ? (string) get_field( 'challenge' ) : '';
	$fde_approach    = function_exists( 'get_field' ) ? get_field( 'approach' ) : '';
	$fde_outcome     = function_exists( 'get_field' ) ? get_field( 'outcome' ) : '';
	$fde_external    = function_exists( 'get_field' ) ? (string) get_field( 'external_url' ) : '';
	$fde_hero        = function_exists( 'get_field' ) ? get_field( 'hero_image' ) : null;
	$fde_gallery     = function_exists( 'get_field' ) ? get_field( 'gallery' ) : [];
	$fde_terms       = get_the_terms( $fde_id, 'work_category' );
	$fde_tech_chips  = $fde_tech ? array_filter( array_map( 'trim', explode( ',', $fde_tech ) ) ) : [];
	?>
	<article <?php post_class( 'single-work' ); ?>>
		<header class="single-work__head section">
			<div class="container container--narrow">
				<?php if ( ! empty( $fde_terms ) && ! is_wp_error( $fde_terms ) ) : ?>
					<ul class="single-work__terms">
						<?php foreach ( $fde_terms as $fde_term ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $fde_term ) ); ?>"><?php echo esc_html( $fde_term->name ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<h1 class="single-work__title"><?php the_title(); ?></h1>

				<?php if ( get_the_excerpt() ) : ?>
					<p class="single-work__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<dl class="single-work__meta">
					<?php if ( $fde_client ) : ?>
						<div><dt>クライアント</dt><dd><?php echo esc_html( $fde_client ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $fde_industry ) : ?>
						<div><dt>業界</dt><dd><?php echo esc_html( $fde_industry ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $fde_period ) : ?>
						<div><dt>期間</dt><dd><?php echo esc_html( $fde_period ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $fde_role ) : ?>
						<div><dt>担当範囲</dt><dd><?php echo esc_html( $fde_role ); ?></dd></div>
					<?php endif; ?>
					<?php if ( ! empty( $fde_tech_chips ) ) : ?>
						<div>
							<dt>使用技術</dt>
							<dd>
								<ul class="single-work__tech">
									<?php foreach ( $fde_tech_chips as $chip ) : ?>
										<li><?php echo esc_html( $chip ); ?></li>
									<?php endforeach; ?>
								</ul>
							</dd>
						</div>
					<?php endif; ?>
					<?php if ( $fde_external ) : ?>
						<div>
							<dt>公開URL</dt>
							<dd><a href="<?php echo esc_url( $fde_external ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $fde_external ); ?></a></dd>
						</div>
					<?php endif; ?>
				</dl>
			</div>
		</header>

		<?php
		$fde_hero_url = null;
		$fde_hero_alt = get_the_title();
		if ( is_array( $fde_hero ) && ! empty( $fde_hero['url'] ) ) {
			$fde_hero_url = $fde_hero['url'];
			$fde_hero_alt = $fde_hero['alt'] ?: get_the_title();
		} elseif ( has_post_thumbnail() ) {
			$fde_hero_url = get_the_post_thumbnail_url( $fde_id, 'work-hero' );
		}
		?>
		<?php if ( $fde_hero_url ) : ?>
			<figure class="single-work__hero">
				<img src="<?php echo esc_url( $fde_hero_url ); ?>" alt="<?php echo esc_attr( $fde_hero_alt ); ?>" loading="eager">
			</figure>
		<?php endif; ?>

		<div class="single-work__body section">
			<div class="container container--narrow prose">
				<?php if ( $fde_challenge ) : ?>
					<section class="single-work__section">
						<h2>課題</h2>
						<?php echo wp_kses_post( wpautop( $fde_challenge ) ); ?>
					</section>
				<?php endif; ?>

				<?php if ( $fde_approach ) : ?>
					<section class="single-work__section">
						<h2>アプローチ</h2>
						<?php echo wp_kses_post( $fde_approach ); ?>
					</section>
				<?php endif; ?>

				<?php if ( $fde_outcome ) : ?>
					<section class="single-work__section">
						<h2>成果</h2>
						<?php echo wp_kses_post( $fde_outcome ); ?>
					</section>
				<?php endif; ?>

				<?php if ( get_the_content() ) : ?>
					<section class="single-work__section">
						<?php the_content(); ?>
					</section>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( is_array( $fde_gallery ) && ! empty( $fde_gallery ) ) : ?>
			<section class="single-work__gallery section">
				<div class="container">
					<h2 class="screen-reader-text">ギャラリー</h2>
					<ul class="single-work__gallery-list">
						<?php foreach ( $fde_gallery as $image ) : ?>
							<?php if ( empty( $image['url'] ) ) continue; ?>
							<li>
								<img src="<?php echo esc_url( $image['url'] ); ?>"
								     alt="<?php echo esc_attr( $image['alt'] ?? '' ); ?>"
								     loading="lazy">
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>
	</article>
	<?php
endwhile;

require FDE_USACHIM_DIR . '/template-parts/common/cta-block.php';

get_footer();
