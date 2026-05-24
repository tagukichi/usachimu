<?php
/**
 * Fallback template (blog index / archives).
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 07</span>
				<h2 class="sec-head__title">Writing</h2>
			</div>
			<span class="sec-head__meta">ARCHIVE</span>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="writing__grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
					$tag = function_exists( 'get_field' ) ? (string) get_field( 'tag_label' ) : '';
					$read = function_exists( 'get_field' ) ? (string) get_field( 'read_time' ) : '';
					?>
					<li class="writing-card">
						<div class="writing-card__head">
							<span class="writing-card__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<?php if ( $read ) : ?><span class="writing-card__read"><?php echo esc_html( $read ); ?> read</span><?php endif; ?>
						</div>
						<h3 class="writing-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="writing-card__foot">
							<?php if ( $tag ) : ?>
								<span class="writing-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php else : ?><span></span><?php endif; ?>
							<a href="<?php the_permalink(); ?>" class="writing-card__more">read →</a>
						</div>
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
			<p class="work__disclaimer jp">記事はまだありません。</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
