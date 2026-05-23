<?php
/**
 * Works excerpt on the front page.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_works = new WP_Query(
	[
		'post_type'           => 'works',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

if ( ! $fde_works->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section class="works-excerpt section" aria-labelledby="works-excerpt-title">
	<div class="container">
		<header class="section-head">
			<p class="section-head__eyebrow">Works</p>
			<h2 id="works-excerpt-title" class="section-head__title">最近の実績</h2>
		</header>

		<ul class="works-grid">
			<?php while ( $fde_works->have_posts() ) : $fde_works->the_post(); ?>
				<li>
					<?php require FDE_USACHIM_DIR . '/template-parts/works/card.php'; ?>
				</li>
			<?php endwhile; ?>
		</ul>

		<p class="section-cta">
			<a class="button button--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'works' ) ); ?>">すべての実績を見る</a>
		</p>
	</div>
</section>
<?php
wp_reset_postdata();
