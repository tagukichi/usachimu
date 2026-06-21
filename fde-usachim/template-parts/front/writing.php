<?php
/**
 * §04 — Blog (eyecatch card grid).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_query = new WP_Query(
	[
		'post_type'           => 'post',
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	]
);

$fde_has_posts = $fde_query->have_posts();

$fde_fallback = [
	[ 'd' => '2026.04.18', 't' => '「PoCで終わる」を構造的に避けるための、評価指標の握り方',         'tag' => 'AI / 評価', 'url' => '#' ],
	[ 'd' => '2026.03.02', 't' => 'Difyの本番運用で気をつけている10のこと（自治体案件で学んだ）',  'tag' => '運用',     'url' => '#' ],
	[ 'd' => '2026.01.21', 't' => '個人事業のFDEとして、3年間どんな契約形態でやってきたか',         'tag' => 'Business', 'url' => '#' ],
	[ 'd' => '2025.11.04', 't' => 'RAGのデータ前処理：紙資料が混ざる現場で実用化するために',       'tag' => 'RAG',     'url' => '#' ],
	[ 'd' => '2025.09.12', 't' => 'kintone × 生成AIで実現する、見積もり業務の自動化',              'tag' => 'DX',      'url' => '#' ],
	[ 'd' => '2025.07.30', 't' => 'Next.js App Router で SaaS を作るときの最初の設計判断',         'tag' => 'SaaS',    'url' => '#' ],
];
?>
<section class="section section--decor" id="writing" data-section="writing">
	<?php fde_tri_field( 'bl', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 04</span>
				<h2 class="sec-head__title">Blog</h2>
			</div>
			<span class="sec-head__meta">RECENT POSTS</span>
		</header>

		<div class="blog-grid">
			<?php if ( $fde_has_posts ) : ?>
				<?php while ( $fde_query->have_posts() ) : $fde_query->the_post(); ?>
					<?php
					$tag = function_exists( 'get_field' ) ? (string) get_field( 'tag_label' ) : '';
					if ( ! $tag ) {
						$cats = get_the_category();
						if ( ! empty( $cats ) ) {
							$tag = $cats[0]->name;
						}
					}
					$thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : '';
					$title = get_the_title();
					?>
					<a class="blog-card" href="<?php the_permalink(); ?>">
						<div class="blog-card__thumb">
							<?php if ( $thumb ) : ?>
								<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
							<?php else : ?>
								<div class="blog-card__thumb-fallback" aria-hidden="true">
									<span class="blog-card__thumb-mark serif"><?php echo esc_html( mb_substr( $title, 0, 1 ) ); ?></span>
								</div>
							<?php endif; ?>
							<?php if ( $tag ) : ?>
								<span class="blog-card__tag mono"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
						</div>
						<div class="blog-card__body">
							<span class="blog-card__date mono"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
							<h3 class="blog-card__title"><?php echo esc_html( $title ); ?></h3>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ( $fde_fallback as $p ) : ?>
					<a class="blog-card" href="<?php echo esc_url( $p['url'] ); ?>">
						<div class="blog-card__thumb">
							<div class="blog-card__thumb-fallback" aria-hidden="true">
								<span class="blog-card__thumb-mark serif"><?php echo esc_html( mb_substr( $p['t'], 0, 1 ) ); ?></span>
							</div>
							<span class="blog-card__tag mono"><?php echo esc_html( $p['tag'] ); ?></span>
						</div>
						<div class="blog-card__body">
							<span class="blog-card__date mono"><?php echo esc_html( $p['d'] ); ?></span>
							<h3 class="blog-card__title"><?php echo esc_html( $p['t'] ); ?></h3>
						</div>
					</a>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
