<?php
/**
 * Latest news — list view, displayed directly below the Hero.
 * Pulls posts from the "news" / 「お知らせ」 category.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_news_term = get_term_by( 'slug', 'news', 'category' );
if ( ! $fde_news_term || is_wp_error( $fde_news_term ) ) {
	$fde_news_term = get_term_by( 'name', 'お知らせ', 'category' );
}

$fde_news_link = $fde_news_term && ! is_wp_error( $fde_news_term )
	? get_category_link( $fde_news_term->term_id )
	: home_url( '/category/news/' );

$fde_news = [];

if ( $fde_news_term && ! is_wp_error( $fde_news_term ) ) {
	$fde_news_query = new WP_Query(
		[
			'post_type'           => 'post',
			'posts_per_page'      => 5,
			'cat'                 => $fde_news_term->term_id,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		]
	);

	if ( $fde_news_query->have_posts() ) {
		while ( $fde_news_query->have_posts() ) {
			$fde_news_query->the_post();

			$fde_news_tag  = 'NOTE';
			$fde_post_tags = get_the_tags();
			if ( ! empty( $fde_post_tags ) ) {
				$fde_news_tag = mb_strtoupper( $fde_post_tags[0]->name );
			}

			$fde_news[] = [
				'date' => get_the_date( 'Y.m.d' ),
				'tag'  => $fde_news_tag,
				'body' => get_the_title(),
				'url'  => get_permalink(),
			];
		}
		wp_reset_postdata();
	}
}

// フォールバック：該当カテゴリも投稿も無い場合はデモ3件
if ( empty( $fde_news ) ) {
	$fde_news = [
		[ 'date' => '2026.05.20', 'tag' => 'NOTE',    'body' => '個人事業として5年目に入りました。新規相談は引き続き受付中。', 'url' => '#' ],
		[ 'date' => '2026.04.18', 'tag' => 'WRITING', 'body' => '「PoCで終わる」を構造的に避けるための、評価指標の握り方 ─ 公開しました。', 'url' => '#writing' ],
		[ 'date' => '2026.03.02', 'tag' => 'CASE',    'body' => '基礎自治体向け 過去議会答弁のDB化プロジェクトを完了。所感を後日掲載予定。', 'url' => '#writing' ],
	];
}
?>
<section class="section news section--decor" id="news" data-section="news">
	<?php fde_tri_field( 'tl', 9 ); ?>
	<?php fde_tri_field( 'br', 12 ); ?>

	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">// LATEST</span>
				<h2 class="sec-head__title">お知らせ</h2>
			</div>
			<a class="sec-head__meta news__more" href="<?php echo esc_url( $fde_news_link ); ?>">ALL →</a>
		</header>

		<ul class="news__list">
			<?php foreach ( $fde_news as $item ) : ?>
				<?php $href = ! empty( $item['url'] ) ? $item['url'] : '#'; ?>
				<li class="news__item">
					<a class="news__link" href="<?php echo esc_url( $href ); ?>">
						<span class="news__date mono"><?php echo esc_html( $item['date'] ); ?></span>
						<span class="news__tag mono"><?php echo esc_html( $item['tag'] ); ?></span>
						<span class="news__body jp"><?php echo esc_html( $item['body'] ); ?></span>
						<span class="news__arrow mono" aria-hidden="true">→</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
