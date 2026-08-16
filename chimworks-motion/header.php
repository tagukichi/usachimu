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
	<span class="loader__label">CHIMWORKS — LOADING</span>
	<span class="loader__count" data-loader-count>0</span>
	<span class="loader__bar"><span data-loader-bar></span></span>
</div>

<div class="mcursor" data-cursor aria-hidden="true">
	<span class="mcursor__ring" data-cursor-ring></span>
	<span class="mcursor__dot" data-cursor-dot></span>
</div>

<header class="site-header" role="banner">
	<div class="site-header__inner">
		<?php require FDE_USACHIM_DIR . '/template-parts/header/logo.php'; ?>
		<?php require FDE_USACHIM_DIR . '/template-parts/header/nav.php'; ?>
	</div>
</header>

<?php require FDE_USACHIM_DIR . '/template-parts/header/mobile-panel.php'; ?>

<main id="site-main" class="site-main" role="main">
