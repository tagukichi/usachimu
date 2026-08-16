<?php
/**
 * Search results.
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ Search</span>
				<h2 class="sec-head__title">「<?php echo esc_html( get_search_query() ); ?>」</h2>
			</div>
		</header>

		<?php get_search_form(); ?>

		<?php if ( have_posts() ) : ?>
			<ul class="writing__grid" style="margin-top:32px;">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="writing-card">
						<h3 class="writing-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<?php if ( get_the_excerpt() ) : ?>
							<p class="jp" style="font-size:14px; color:var(--ink-3);"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></p>
						<?php endif; ?>
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
			<p class="work__disclaimer jp" style="margin-top:32px;">該当する結果がありませんでした。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
