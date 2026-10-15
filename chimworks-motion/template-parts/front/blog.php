<?php
/**
 * Blog — 最新記事（ブログ・お知らせ）をアイキャッチ付きカードで並べる。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'post',
		'posts_per_page'      => 9,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

if ( ! $fde_query->have_posts() ) {
	return;
}

$fde_blog_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/?post_type=post' );
?>
<section class="section section--dark blog" id="blog" data-section="blog">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Blog</h2>
			</div>
			<span class="sec-head__meta">ブログ・お知らせ</span>
		</header>

		<div class="blog2-grid">
			<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); ?>
				<?php
				$fde_cats  = get_the_category();
				$fde_cat   = ! empty( $fde_cats ) ? $fde_cats[0]->name : '';
				$fde_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
				$fde_title = get_the_title();
				?>
				<a class="blog2-card glass" href="<?php the_permalink(); ?>">
					<div class="blog2-card__thumb">
						<?php if ( $fde_thumb ) : ?>
							<img class="blog2-card__thumb-bg" src="<?php echo esc_url( $fde_thumb ); ?>" alt="" aria-hidden="true" loading="lazy">
							<img class="blog2-card__thumb-img" src="<?php echo esc_url( $fde_thumb ); ?>" alt="<?php echo esc_attr( $fde_title ); ?>" loading="lazy">
						<?php else : ?>
							<div class="blog2-card__thumb-ph" aria-hidden="true">
								<span class="serif"><?php echo esc_html( mb_substr( $fde_title, 0, 1 ) ); ?></span>
							</div>
						<?php endif; ?>
						<?php if ( $fde_cat ) : ?>
							<span class="blog2-card__cat mono"><?php echo esc_html( $fde_cat ); ?></span>
						<?php endif; ?>
					</div>
					<div class="blog2-card__body">
						<span class="blog2-card__date mono"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
						<h3 class="blog2-card__title jp"><?php echo esc_html( $fde_title ); ?></h3>
					</div>
				</a>
			<?php endwhile; ?>
		</div>

		<div class="blog2-more">
			<a class="blog2-more__link mono" href="<?php echo esc_url( $fde_blog_url ); ?>">すべての記事を見る →</a>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
