<?php
/**
 * Works archive (minimal list — main display is the front page §05 Work).
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';
?>
<section class="section">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 05</span>
				<h2 class="sec-head__title">
					<?php
					if ( is_tax( 'work_category' ) ) {
						single_term_title();
					} else {
						esc_html_e( 'Selected Work', 'fde-usachim' );
					}
					?>
				</h2>
			</div>
			<span class="sec-head__meta">ARCHIVE</span>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="writing__grid">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php
					$industry = function_exists( 'get_field' ) ? (string) get_field( 'industry' ) : '';
					$year     = function_exists( 'get_field' ) ? (string) get_field( 'year' ) : '';
					?>
					<li class="writing-card">
						<div class="writing-card__head">
							<span class="writing-card__date"><?php echo esc_html( $year ); ?></span>
							<?php if ( $industry ) : ?>
								<span class="writing-card__read"><?php echo esc_html( $industry ); ?></span>
							<?php endif; ?>
						</div>
						<h3 class="writing-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="writing-card__foot">
							<span></span>
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
			<p class="work__disclaimer jp">該当する実績はまだありません。</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
