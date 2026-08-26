<?php
/**
 * Site header.
 *
 * @package fde-usachim
 */

$fde_body_classes = [ 'site' ];
if ( is_front_page() ) {
	$fde_body_classes[] = 'has-dark-hero';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( $fde_body_classes ); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#site-main"><?php esc_html_e( 'メインコンテンツへスキップ', 'fde-usachim' ); ?></a>

<div class="loader" data-loader aria-hidden="true">
	<div class="loader__glow loader__glow--a" aria-hidden="true"></div>
	<div class="loader__glow loader__glow--b" aria-hidden="true"></div>
	<div class="loader__inner">
		<div class="loader__track-wrap">
			<span class="loader__bar"><span class="loader__bar-fill" data-loader-bar></span></span>
		</div>
		<div class="loader__meta">
			<span class="loader__label mono" data-loader-text>LOADING</span>
			<span class="loader__pct"><span data-loader-count>0</span>%</span>
		</div>
	</div>
	<div class="loader__tip mono">TIP: SCROLL TO EXPLORE</div>
</div>

<div class="mcursor" data-cursor aria-hidden="true">
	<span class="mcursor__ring" data-cursor-ring>
		<span class="mcursor__label mono" data-cursor-label></span>
	</span>
	<span class="mcursor__dot" data-cursor-dot></span>
</div>

<div class="page-curtain" data-curtain aria-hidden="true"></div>

<header class="site-header" role="banner">
	<div class="site-header__inner">
		<?php require FDE_USACHIM_DIR . '/template-parts/header/logo.php'; ?>
		<?php require FDE_USACHIM_DIR . '/template-parts/header/nav.php'; ?>
	</div>
</header>

<?php require FDE_USACHIM_DIR . '/template-parts/header/mobile-panel.php'; ?>

<main id="site-main" class="site-main" role="main">
