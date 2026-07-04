<?php
/**
 * Blog — latest posts (blog + news) as eyecatch cards.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'post',
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

if ( ! $fde_query->have_posts() ) {
	return;
}
?>
<section class="section section--dark section--decor blog" id="blog" data-section="blog">
	<?php fde_tri_field( 'tr', 8 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">04</span>
				<h2 class="sec-head__title">Blog</h2>
			</div>
			<span class="sec-head__meta">ブログ・お知らせ</span>
		</header>

		<div class="blog2-grid">
			<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); ?>
				<?php
				$cats  = get_the_category();
				$cat   = ! empty( $cats ) ? $cats[0]->name : '';
				$thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
				$title = get_the_title();
				?>
				<a class="blog2-card glass" href="<?php the_permalink(); ?>">
					<div class="blog2-card__thumb">
						<?php if ( $thumb ) : ?>
							<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
						<?php else : ?>
							<div class="blog2-card__thumb-ph" aria-hidden="true">
								<span class="serif"><?php echo esc_html( mb_substr( $title, 0, 1 ) ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( $cat ) : ?>
							<span class="blog2-card__cat mono"><?php echo esc_html( $cat ); ?></span>
						<?php endif; ?>
					</div>
					<div class="blog2-card__body">
						<span class="blog2-card__date mono"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
						<h3 class="blog2-card__title jp"><?php echo esc_html( $title ); ?></h3>
					</div>
				</a>
			<?php endwhile; ?>
		</div>

		<div class="blog2-more">
			<a class="blog2-more__link mono" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/?post_type=post' ) ); ?>">
				すべての記事を見る →
			</a>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
