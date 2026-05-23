<?php
/**
 * Hero section.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="hero section" aria-label="Hero">
	<div class="container">
		<p class="hero__eyebrow">Forward Deployed Engineer</p>
		<h1 class="hero__title">現場に踏み込み、<br>AIと共に作る。</h1>
		<p class="hero__lead">中小企業の DX を、構想からプロダクションまで伴走する Forward Deployed Engineer。</p>
		<div class="hero__actions">
			<a class="button button--primary" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">相談する</a>
			<a class="button button--ghost" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">サービスを見る</a>
		</div>
	</div>
</section>
