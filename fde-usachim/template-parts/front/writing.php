<?php
/**
 * §07 — Writing (latest blog posts).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'post',
		'posts_per_page'      => 4,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

$fde_has_posts = $fde_query->have_posts();

$fde_fallback = [
	[ 'd' => '2026.04.18', 't' => '「PoCで終わる」を構造的に避けるための、評価指標の握り方',         'tag' => 'AI / 評価', 'read' => '12 min', 'url' => '#' ],
	[ 'd' => '2026.03.02', 't' => 'Difyの本番運用で気をつけている10のこと（自治体案件で学んだ）',  'tag' => '運用',     'read' => '8 min',  'url' => '#' ],
	[ 'd' => '2026.01.21', 't' => '個人事業のFDEとして、3年間どんな契約形態でやってきたか',         'tag' => 'Business', 'read' => '15 min', 'url' => '#' ],
	[ 'd' => '2025.11.04', 't' => 'RAGのデータ前処理：紙資料が混ざる現場で実用化するために',       'tag' => 'RAG',     'read' => '10 min', 'url' => '#' ],
];
?>
<section class="section" id="writing" data-section="writing">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 05</span>
				<h2 class="sec-head__title">Blog</h2>
			</div>
			<span class="sec-head__meta">MONTHLY 1–2 POSTS</span>
		</header>

		<div class="writing__grid">
			<?php if ( $fde_has_posts ) : ?>
				<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); ?>
					<?php
					$tag  = function_exists( 'get_field' ) ? (string) get_field( 'tag_label' ) : '';
					if ( ! $tag ) {
						$cats = get_the_category();
						if ( ! empty( $cats ) ) {
							$tag = $cats[0]->name;
						}
					}
					$read = function_exists( 'get_field' ) ? (string) get_field( 'read_time' ) : '';
					?>
					<article class="writing-card">
						<div class="writing-card__head">
							<span class="writing-card__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<?php if ( $read ) : ?>
								<span class="writing-card__read"><?php echo esc_html( $read ); ?> read</span>
							<?php endif; ?>
						</div>
						<h3 class="writing-card__title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<div class="writing-card__foot">
							<?php if ( $tag ) : ?>
								<span class="writing-card__tag"><?php echo esc_html( $tag ); ?></span>
							<?php else : ?>
								<span></span>
							<?php endif; ?>
							<a href="<?php the_permalink(); ?>" class="writing-card__more">read →</a>
						</div>
					</article>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ( $fde_fallback as $p ) : ?>
					<article class="writing-card">
						<div class="writing-card__head">
							<span class="writing-card__date"><?php echo esc_html( $p['d'] ); ?></span>
							<span class="writing-card__read"><?php echo esc_html( $p['read'] ); ?> read</span>
						</div>
						<h3 class="writing-card__title">
							<a href="<?php echo esc_url( $p['url'] ); ?>"><?php echo esc_html( $p['t'] ); ?></a>
						</h3>
						<div class="writing-card__foot">
							<span class="writing-card__tag"><?php echo esc_html( $p['tag'] ); ?></span>
							<a href="<?php echo esc_url( $p['url'] ); ?>" class="writing-card__more">read →</a>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
