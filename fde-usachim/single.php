<?php
/**
 * Single post (Blog) — main article (7) + sidebar with other posts (3).
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();

	$cats     = get_the_category();
	$cat      = ! empty( $cats ) ? $cats[0]->name : '';
	$pub_date = get_the_date( 'Y.m.d' );
	$mod_date = get_the_modified_date( 'Y.m.d' );
	$thumb    = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';

	// Sidebar : other recent posts (exclude current)
	$fde_others = new WP_Query(
		[
			'post_type'           => 'post',
			'posts_per_page'      => 5,
			'post__not_in'        => [ get_the_ID() ],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		]
	);
	?>
	<div class="single-blog">
		<article class="single-blog__main">
			<header class="single-blog__head">
				<?php if ( $cat ) : ?>
					<span class="single-blog__cat mono"><?php echo esc_html( $cat ); ?></span>
				<?php endif; ?>
				<h1 class="single-blog__title"><?php the_title(); ?></h1>
				<div class="single-blog__dates mono">
					<span class="single-blog__date">
						<span class="single-blog__date-k">PUBLISHED</span>
						<?php echo esc_html( $pub_date ); ?>
					</span>
					<?php if ( $mod_date !== $pub_date ) : ?>
						<span class="single-blog__date">
							<span class="single-blog__date-k">UPDATED</span>
							<?php echo esc_html( $mod_date ); ?>
						</span>
					<?php endif; ?>
				</div>
			</header>

			<?php if ( $thumb ) : ?>
				<figure class="single-blog__eyecatch">
					<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>">
				</figure>
			<?php endif; ?>

			<div class="single-blog__body prose"><?php the_content(); ?></div>

			<?php
			wp_link_pages(
				[
					'before' => '<div class="single-blog__pages">',
					'after'  => '</div>',
				]
			);
			?>
		</article>

		<aside class="single-blog__side">
			<div class="single-blog__side-inner">
				<h2 class="single-blog__side-title mono">OTHER POSTS</h2>
				<?php if ( $fde_others->have_posts() ) : ?>
					<ul class="side-posts">
						<?php while ( $fde_others->have_posts() ) : $fde_others->the_post(); ?>
							<?php
							$s_thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'medium' ) : '';
							$s_cats  = get_the_category();
							$s_cat   = ! empty( $s_cats ) ? $s_cats[0]->name : '';
							?>
							<li class="side-post">
								<a class="side-post__link" href="<?php the_permalink(); ?>">
									<div class="side-post__thumb">
										<?php if ( $s_thumb ) : ?>
											<img src="<?php echo esc_url( $s_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
										<?php else : ?>
											<span class="side-post__thumb-ph serif"><?php echo esc_html( mb_substr( get_the_title(), 0, 1 ) ); ?></span>
										<?php endif; ?>
									</div>
									<div class="side-post__meta">
										<span class="side-post__date mono"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?><?php echo $s_cat ? ' · ' . esc_html( $s_cat ) : ''; ?></span>
										<span class="side-post__title jp"><?php the_title(); ?></span>
									</div>
								</a>
							</li>
						<?php endwhile; ?>
					</ul>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="side-posts__empty jp">他の記事はまだありません。</p>
				<?php endif; ?>

				<a class="side-posts__all mono" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">
					すべての記事を見る →
				</a>
			</div>
		</aside>
	</div>
	<?php
endwhile;

get_footer();
