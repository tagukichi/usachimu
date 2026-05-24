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
$fde_stmt_l1 = (string) fde_field( 'hero_statement_l1',  '書類で動くAIではなく、' );
$fde_stmt_la = (string) fde_field( 'hero_statement_l2_a', '現場で動く' );
$fde_stmt_lb = (string) fde_field( 'hero_statement_l2_b', 'AIを。' );
$fde_lede    = (string) fde_field(
	'hero_lede',
	"CHIM WORKSは、AI/データ領域の **Forward Deployed Engineer** です。\n発注をいただいてから作るのではなく、現場に入り、何を作るべきかを一緒に決めるところから始めます。\n作るのも、運用するのも、引き継ぐのも、ひとり。"
);

$fde_stat_defaults = [
	[ 'k' => 'SINCE',     'v' => '2021',    'sub' => '個人事業として' ],
	[ 'k' => 'DELIVERED', 'v' => '23',      'sub' => '案件 (NDA含む)' ],
	[ 'k' => 'NEXT SLOT', 'v' => '2026.07', 'sub' => '相談スロット' ],
];
$fde_stats = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$i = $n - 1;
	$fde_stats[] = [
		'k'   => (string) fde_field( "hero_stat_{$n}_k",   $fde_stat_defaults[ $i ]['k'] ),
		'v'   => (string) fde_field( "hero_stat_{$n}_v",   $fde_stat_defaults[ $i ]['v'] ),
		'sub' => (string) fde_field( "hero_stat_{$n}_sub", $fde_stat_defaults[ $i ]['sub'] ),
	];
}

$fde_cta_p = (string) fde_field( 'hero_cta_primary',   '案件を相談する →' );
$fde_cta_s = (string) fde_field( 'hero_cta_secondary', '実績を見る' );

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

		<div class="hero__bar">
			<div class="hero__stats">
				<?php foreach ( $fde_stats as $stat ) : ?>
					<div class="hero__stat">
						<span class="hero__stat-k"><?php echo esc_html( $stat['k'] ); ?></span>
						<span class="hero__stat-v serif"><?php echo esc_html( $stat['v'] ); ?></span>
						<span class="hero__stat-sub"><?php echo esc_html( $stat['sub'] ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="hero__cta">
				<a class="btn btn--invert" href="#contact"><?php echo esc_html( $fde_cta_p ); ?></a>
				<a class="btn btn--ghost is-on-dark" href="#work"><?php echo esc_html( $fde_cta_s ); ?></a>
			</div>
		</div>

	</div>
</section>
