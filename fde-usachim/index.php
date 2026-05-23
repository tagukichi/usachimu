<?php
/**
 * Generic fallback template.
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="container container--narrow">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<header>
						<h1><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>

			<?php
			the_posts_pagination(
				[
					'prev_text' => __( '前へ', 'fde-usachim' ),
					'next_text' => __( '次へ', 'fde-usachim' ),
				]
			);
			?>
		<?php else : ?>
			<h1><?php esc_html_e( 'コンテンツが見つかりませんでした', 'fde-usachim' ); ?></h1>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
