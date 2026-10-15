<?php
/**
 * Template Name: 特設ページ（3D）
 * Template Post Type: page
 *
 * 事業内容の行から飛ぶ先。全画面の Three.js シーンの上にタイトルと
 * リード文を重ね、その下に固定ページ本文を表示する。
 * シーンの種類（web / dx）は ACF の「3D シーン」で選ぶ。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_scene   = (string) get_field( 'sp_scene' ) ?: 'web';
$fde_eyebrow = (string) get_field( 'sp_eyebrow' ) ?: '';
$fde_lead    = (string) get_field( 'sp_lead' ) ?: '';

get_header();
?>
<article class="sp" data-sp-scene="<?php echo esc_attr( in_array( $fde_scene, [ 'web', 'dx' ], true ) ? $fde_scene : 'web' ); ?>">
	<header class="sp-hero" data-sp-hero>
		<canvas class="sp-hero__canvas" data-sp-canvas aria-hidden="true"></canvas>
		<div class="sp-hero__inner">
			<?php if ( $fde_eyebrow ) : ?>
				<span class="sp-hero__eyebrow mono"><?php echo esc_html( $fde_eyebrow ); ?></span>
			<?php endif; ?>
			<h1 class="sp-hero__title jp"><?php the_title(); ?></h1>
			<?php if ( $fde_lead ) : ?>
				<p class="sp-hero__lead jp"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
			<?php endif; ?>
			<a class="sp-hero__scroll mono" href="#sp-body">SCROLL<span class="sp-hero__scroll-line" aria-hidden="true"></span></a>
		</div>
		<p class="sp-hero__hint mono" aria-hidden="true">DRAG / MOVE</p>
	</header>

	<div class="sp-body" id="sp-body">
		<div class="sp-body__inner prose">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>

		<div class="sp-body__back">
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/#services' ) ); ?>">← 事業内容へ戻る</a>
			<?php if ( fde_contact_email() ) : ?>
				<a class="btn btn--solid" href="mailto:<?php echo esc_attr( fde_contact_email() ); ?>">メールで相談する</a>
			<?php endif; ?>
		</div>
	</div>
</article>
<?php
get_footer();
