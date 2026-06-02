<?php
/**
 * §02 — About. Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_portrait     = fde_field( 'about_portrait' );
$fde_portrait_fig = (string) fde_field( 'about_portrait_fig', 'FIG. 01 — CHIM' );

$fde_name     = (string) fde_field( 'about_name',     '' );
$fde_position = (string) fde_field( 'about_position', '' );
$fde_location = (string) fde_field( 'about_location', '' );
$fde_since    = (string) fde_field( 'about_since',    '' );

$fde_lead = (string) fde_field(
	'about_lead',
	'ITの力で、今日より少し楽しい明日を。'
);
$fde_body = (string) fde_field(
	'about_body',
	"公務員として働き始めた頃、目の前の仕事に「これは本当に必要なのか」と感じる場面がたびたびありました。紙の書類、繰り返される手入力、止まらない問い合わせ──業務そのものよりも、業務の周りにある“非効率”に、多くの人の時間が奪われていました。\n\nそして、そういう現場には決まって、悩みを抱えたまま声を上げられずに働く人がいた。忙しさに追われ、本来感じられるはずの仕事の充実を、いつのまにか忘れてしまっている。**「毎日を、もう少し楽しく働ける社会になってほしい」** ── それが、自分の出発点です。\n\nITの力で、困っている誰かの“今日の終わり方”を少しだけ軽くする。明日、ほんのわずかでも楽しい気持ちで仕事を始められるように。自分が現場で得た知識と技術で、少しでも多くの人に貢献し、よりよい世界をつくっていきたい。それが、CHIM WORKSの願いです。"
);
?>
<section class="section" id="about" data-section="about">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 02</span>
				<h2 class="sec-head__title">About</h2>
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

				<?php if ( $fde_name || $fde_position || $fde_location || $fde_since ) : ?>
					<dl class="about__profile">
						<?php if ( $fde_name ) : ?>
							<div class="about__profile-row about__profile-row--name">
								<dt class="about__profile-k">NAME</dt>
								<dd class="about__profile-name serif"><?php echo esc_html( $fde_name ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $fde_position ) : ?>
							<div class="about__profile-row">
								<dt class="about__profile-k">ROLE</dt>
								<dd class="about__profile-v"><?php echo esc_html( $fde_position ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $fde_location ) : ?>
							<div class="about__profile-row">
								<dt class="about__profile-k">BASED</dt>
								<dd class="about__profile-v"><?php echo esc_html( $fde_location ); ?></dd>
							</div>
						<?php endif; ?>
						<?php if ( $fde_since ) : ?>
							<div class="about__profile-row">
								<dt class="about__profile-k">SINCE</dt>
								<dd class="about__profile-v"><?php echo esc_html( $fde_since ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>
			</aside>

			<div class="about__body">
				<p class="about__lead"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
				<?php echo wp_kses( fde_paragraphs( $fde_body ), [ 'p' => [], 'b' => [], 'br' => [] ] ); ?>
			</div>
		</div>
	</div>
</section>
