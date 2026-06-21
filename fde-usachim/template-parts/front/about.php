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
	'公務員として現場の“非効率”を見てきました。困っている人の毎日を、ITの力で少し軽くしたい。現場で得た知識と技術で、よりよい世界をつくります。'
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
