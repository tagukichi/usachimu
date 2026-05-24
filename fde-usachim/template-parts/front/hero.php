<?php
/**
 * Hero section (dark). Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_top_l   = (string) fde_field( 'hero_eyebrow_left',  'CHIM WORKS — INDEX' );
$fde_top_r   = (string) fde_field( 'hero_eyebrow_right', 'EST. 2021 · TOKYO, JP' );
$fde_stmt_l1 = (string) fde_field( 'hero_statement_l1',  '資料の中のAIではなく、' );
$fde_stmt_la = (string) fde_field( 'hero_statement_l2_a', '現場の手の中で動く' );
$fde_stmt_lb = (string) fde_field( 'hero_statement_l2_b', 'AIを。' );
$fde_lede    = (string) fde_field(
	'hero_lede',
	"**AIと業務データを、現場で使われる仕組みに変える。**\n発注を受けてから作るのではなく、現場に入り、何を作るべきかを一緒に決めるところから始めます。"
);

/**
 * News list — latest posts in the "お知らせ" category.
 */
$fde_news_term = get_term_by( 'slug', 'news', 'category' );
if ( ! $fde_news_term || is_wp_error( $fde_news_term ) ) {
	$fde_news_term = get_term_by( 'name', 'お知らせ', 'category' );
}

$fde_news      = [];
$fde_news_link = '';

if ( $fde_news_term && ! is_wp_error( $fde_news_term ) ) {
	$fde_news_link  = get_category_link( $fde_news_term->term_id );
	$fde_news_query = new WP_Query(
		[
			'post_type'           => 'post',
			'posts_per_page'      => 3,
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

if ( empty( $fde_news ) ) {
	$fde_news = [
		[ 'date' => '2026.05.20', 'tag' => 'NOTE',    'body' => '個人事業として5年目に入りました。新規相談は引き続き受付中。', 'url' => '#' ],
		[ 'date' => '2026.04.18', 'tag' => 'WRITING', 'body' => '「PoCで終わる」を構造的に避けるための、評価指標の握り方 ─ 公開しました。', 'url' => '#writing' ],
		[ 'date' => '2026.03.02', 'tag' => 'CASE',    'body' => '基礎自治体向け 過去議会答弁のDB化プロジェクトを完了。所感を後日掲載予定。', 'url' => '#work' ],
	];
}
if ( ! $fde_news_link ) {
	$fde_news_link = home_url( '/category/news/' );
}
?>
<section class="hero" id="top" aria-label="Hero">
	<div class="hero__grid" aria-hidden="true"></div>
	<div class="hero__inner">

		<div class="hero__top">
			<span><?php echo esc_html( $fde_top_l ); ?></span>
			<span><?php echo esc_html( $fde_top_r ); ?></span>
		</div>

		<h1 class="hero__statement jp">
			<?php echo esc_html( $fde_stmt_l1 ); ?><br>
			<?php if ( $fde_stmt_la ) : ?>
				<span class="hero__statement-mute"><?php echo esc_html( $fde_stmt_la ); ?></span><?php endif; ?>
			<?php echo esc_html( $fde_stmt_lb ); ?>
		</h1>

		<div class="hero__sub">
			<p class="hero__lede"><?php echo wp_kses( str_replace( "\n", '<br>', fde_inline_text( $fde_lede ) ), [ 'b' => [], 'br' => [], 'strong' => [] ] ); ?></p>

			<?php if ( ! empty( $fde_news ) ) : ?>
				<div class="hero__news">
					<div class="hero__news-head">
						<span>// LATEST — お知らせ</span>
						<a href="<?php echo esc_url( $fde_news_link ); ?>">ALL →</a>
					</div>
					<div class="hero__news-list">
						<?php foreach ( $fde_news as $item ) : ?>
							<?php $href = ! empty( $item['url'] ) ? $item['url'] : '#'; ?>
							<a class="hero__news-item" href="<?php echo esc_url( $href ); ?>">
								<span class="hero__news-date"><?php echo esc_html( $item['date'] ?? '' ); ?></span>
								<span class="hero__news-tag"><?php echo esc_html( $item['tag'] ?? '' ); ?></span>
								<span class="hero__news-body jp"><?php echo esc_html( $item['body'] ?? '' ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>
