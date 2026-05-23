<?php
/**
 * About excerpt on the front page.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_image   = fde_option( 'profile_image' );
$fde_name    = (string) fde_option( 'profile_name', get_bloginfo( 'name' ) );
$fde_tagline = (string) fde_option( 'profile_tagline', 'Forward Deployed Engineer' );
$fde_bio     = (string) fde_option( 'profile_bio' );
?>
<section class="about-excerpt section" aria-labelledby="about-excerpt-title">
	<div class="container">
		<header class="section-head">
			<p class="section-head__eyebrow">About</p>
			<h2 id="about-excerpt-title" class="section-head__title">自分と関わるすべての人を、<br>少しでも前に進める。</h2>
		</header>

		<div class="about-excerpt__body">
			<?php if ( is_array( $fde_image ) && ! empty( $fde_image['url'] ) ) : ?>
				<figure class="about-excerpt__photo">
					<img src="<?php echo esc_url( $fde_image['url'] ); ?>"
					     alt="<?php echo esc_attr( $fde_image['alt'] ?? $fde_name ); ?>"
					     loading="lazy"
					     width="<?php echo esc_attr( $fde_image['width'] ?? '' ); ?>"
					     height="<?php echo esc_attr( $fde_image['height'] ?? '' ); ?>">
				</figure>
			<?php endif; ?>

			<div class="about-excerpt__text">
				<p class="about-excerpt__name"><?php echo esc_html( $fde_name ); ?></p>
				<p class="about-excerpt__tagline"><?php echo esc_html( $fde_tagline ); ?></p>
				<?php if ( $fde_bio ) : ?>
					<div class="about-excerpt__bio"><?php echo wp_kses_post( wpautop( $fde_bio ) ); ?></div>
				<?php else : ?>
					<p class="about-excerpt__bio">公務員 → Web制作 → 独立 → AI活用 → FDE。Web の実装力と AI 活用を武器に、中小企業の現場に踏み込んで伴走するエンジニアです。</p>
				<?php endif; ?>
				<p>
					<a class="button button--ghost" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">プロフィールを見る</a>
				</p>
			</div>
		</div>
	</div>
</section>
