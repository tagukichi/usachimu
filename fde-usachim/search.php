<?php
/**
 * Search results.
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="container container--narrow">
		<header class="page-head">
			<p class="page-head__eyebrow">Search</p>
			<h1 class="page-head__title">「<?php echo esc_html( get_search_query() ); ?>」の検索結果</h1>
		</header>

		<?php get_search_form(); ?>

		<?php if ( have_posts() ) : ?>
			<ul class="search-results">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="search-result">
						<a href="<?php the_permalink(); ?>" class="search-result__link">
							<h2 class="search-result__title"><?php the_title(); ?></h2>
							<?php if ( get_the_excerpt() ) : ?>
								<p class="search-result__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 40 ) ); ?></p>
							<?php endif; ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

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
			<p>該当する結果がありませんでした。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
