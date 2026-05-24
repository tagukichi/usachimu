<?php
/**
 * §02 — About.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_portrait     = fde_option( 'about_portrait' );
$fde_portrait_fig = (string) fde_option( 'about_portrait_fig', 'FIG. 01 — CHIM' );
$fde_stats        = fde_option(
	'about_stats',
	[
		[ 'k' => 'BASED',     'v' => 'Tokyo, JP' ],
		[ 'k' => 'SINCE',     'v' => '2021' ],
		[ 'k' => 'DELIVERED', 'v' => '23 案件' ],
		[ 'k' => 'CURRENT',   'v' => '3 件 稼働中' ],
	]
);
$fde_lead = (string) fde_option(
	'about_lead',
	"「コンサルが書いた絵を、別の誰かが実装し、また別の誰かが運用する」\n——その分業の継ぎ目で、AIプロジェクトはよく失敗します。"
);
$fde_body = (string) fde_option(
	'about_body',
	"CHIM WORKSは、ヒアリングから設計、実装、評価、本番運用、内製化までを **一人称で連続的に** 引き受けます。元はWeb受託の開発者として5年、その後事業会社で社内データ基盤を3年担当。「現場の言葉」と「コードの言葉」を行き来する型を、長く練習してきました。\n\n個人でやっているのは、規模を求めていないからです。同時に動かす案件は3件まで。その代わり、関わる案件には深く入り、止まらないところまで持っていく ── それが屋号の意味です。"
);
$fde_tags = fde_split_tags( (string) fde_option( 'about_tags', 'Forward Deployed, AI / LLM, Data Engineering, Solo, NDA OK' ) );
?>
<section class="section" id="about" data-section="about">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 02</span>
				<h2 class="sec-head__title">About — 私について。</h2>
			</div>
			<span class="sec-head__meta">PROFILE</span>
		</header>

		<div class="about__grid">
			<aside>
				<figure class="about__portrait">
					<?php if ( is_array( $fde_portrait ) && ! empty( $fde_portrait['url'] ) ) : ?>
						<img src="<?php echo esc_url( $fde_portrait['url'] ); ?>"
						     alt="<?php echo esc_attr( $fde_portrait['alt'] ?? '' ); ?>"
						     loading="lazy"
						     width="<?php echo esc_attr( $fde_portrait['width'] ?? '' ); ?>"
						     height="<?php echo esc_attr( $fde_portrait['height'] ?? '' ); ?>">
					<?php else : ?>
						<div class="about__portrait-placeholder">
							<div class="about__portrait-placeholder-label">[ PORTRAIT ]</div>
							<div class="about__portrait-placeholder-note">後ほど差し替えてください</div>
						</div>
					<?php endif; ?>
					<?php if ( $fde_portrait_fig ) : ?>
						<figcaption class="about__portrait-fig"><?php echo esc_html( $fde_portrait_fig ); ?></figcaption>
					<?php endif; ?>
				</figure>

				<?php if ( is_array( $fde_stats ) && ! empty( $fde_stats ) ) : ?>
					<dl class="about__stats">
						<?php foreach ( $fde_stats as $stat ) : ?>
							<div class="about__stat">
								<dt class="about__stat-k"><?php echo esc_html( $stat['k'] ?? '' ); ?></dt>
								<dd class="about__stat-v"><?php echo esc_html( $stat['v'] ?? '' ); ?></dd>
							</div>
						<?php endforeach; ?>
					</dl>
				<?php endif; ?>
			</aside>

			<div class="about__body">
				<p class="about__lead"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
				<?php echo wp_kses( fde_paragraphs( $fde_body ), [ 'p' => [], 'b' => [], 'br' => [] ] ); ?>

				<?php if ( ! empty( $fde_tags ) ) : ?>
					<div class="about__tags">
						<?php foreach ( $fde_tags as $tag ) : ?>
							<span class="about__tag"><?php echo esc_html( $tag ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
