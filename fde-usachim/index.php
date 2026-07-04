<?php
/**
 * Blog index / archive — eyecatch card list.
 *
 * @package fde-usachim
 */

get_header();

$fde_heading = 'Blog';
$fde_sub     = 'ブログ・お知らせ';
if ( is_category() ) {
	$fde_heading = single_cat_title( '', false );
	$fde_sub     = 'カテゴリー';
} elseif ( is_tag() ) {
	$fde_heading = single_tag_title( '', false );
	$fde_sub     = 'タグ';
} elseif ( is_date() ) {
	$fde_heading = get_the_archive_title();
	$fde_sub     = 'アーカイブ';
}
?>

<section class="section blog-archive">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">Blog</span>
				<h2 class="sec-head__title"><?php echo esc_html( $fde_heading ); ?></h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( $fde_sub ); ?></span>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="blog2-grid">
				<?php while ( have_posts() ) : the_post(); ?>
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

			<?php
			the_posts_pagination(
				[
					'mid_size'  => 1,
					'prev_text' => __( '前へ', 'fde-usachim' ),
					'next_text' => __( '次へ', 'fde-usachim' ),
				]
			);
			?>
		<?php else : ?>
			<p class="jp" style="color:var(--mute);">記事はまだありません。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
