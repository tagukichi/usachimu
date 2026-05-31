<?php
/**
 * Hero section (dark). Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_stmt_l1 = (string) fde_field( 'hero_statement_l1',  '資料のAIではなく、' );
$fde_stmt_la = (string) fde_field( 'hero_statement_l2_a', '現場の' );
$fde_stmt_lb = (string) fde_field( 'hero_statement_l2_b', 'AIを。' );
$fde_lede    = (string) fde_field(
	'hero_lede',
	"**AIと業務データを、現場で使われる仕組みに変える。**\n発注を受けてから作るのではなく、現場に入り、何を作るべきかを一緒に決めるところから始めます。"
);

$fde_hero_image   = fde_field( 'hero_image' );
$fde_hero_caption = (string) fde_field( 'hero_image_caption', 'FIG. 00 — FORWARD DEPLOYED' );
$fde_has_hero_img = is_array( $fde_hero_image ) && ! empty( $fde_hero_image['url'] );

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

		<div class="hero__main">
			<figure class="hero__media<?php echo $fde_has_hero_img ? '' : ' hero__media--placeholder'; ?>">
				<?php if ( $fde_has_hero_img ) : ?>
					<img
						src="<?php echo esc_url( $fde_hero_image['url'] ); ?>"
						alt="<?php echo esc_attr( ! empty( $fde_hero_image['alt'] ) ? $fde_hero_image['alt'] : 'CHIM WORKS — Hero visual' ); ?>"
						loading="eager"
						<?php if ( ! empty( $fde_hero_image['width'] ) ) : ?>width="<?php echo esc_attr( $fde_hero_image['width'] ); ?>"<?php endif; ?>
						<?php if ( ! empty( $fde_hero_image['height'] ) ) : ?>height="<?php echo esc_attr( $fde_hero_image['height'] ); ?>"<?php endif; ?>
					>
				<?php else : ?>
					<div class="hero__media-placeholder" aria-hidden="true">
						<span class="hero__media-placeholder-grid"></span>
						<span class="hero__media-placeholder-note mono">[ HERO IMAGE ]</span>
					</div>
				<?php endif; ?>
				<?php if ( $fde_hero_caption ) : ?>
					<figcaption class="hero__media-caption mono"><?php echo esc_html( $fde_hero_caption ); ?></figcaption>
				<?php endif; ?>
				<span class="hero__media-tick hero__media-tick--tl" aria-hidden="true"></span>
				<span class="hero__media-tick hero__media-tick--tr" aria-hidden="true"></span>
				<span class="hero__media-tick hero__media-tick--bl" aria-hidden="true"></span>
				<span class="hero__media-tick hero__media-tick--br" aria-hidden="true"></span>
			</figure>

			<h1 class="hero__statement jp">
				<span class="hero__statement-line">
					<?php echo esc_html( $fde_stmt_l1 ); ?>
				</span>
				<span class="hero__statement-line">
					<?php if ( $fde_stmt_la ) : ?>
						<span class="hero__statement-mute"><?php echo esc_html( $fde_stmt_la ); ?></span><?php endif; ?>
					<?php echo esc_html( $fde_stmt_lb ); ?>
				</span>
			</h1>
		</div>

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
