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
	<div class="loader__inner">
		<div class="loader__title mono" data-loader-text>NOW LOADING<span class="loader__dots">...</span></div>
		<div class="loader__gauge-wrap">
			<span class="loader__sprite" data-loader-sprite>
				<svg viewBox="0 0 10 8" shape-rendering="crispEdges" aria-hidden="true">
					<g fill="currentColor">
						<rect x="2" y="0" width="1" height="2"/>
						<rect x="5" y="0" width="1" height="2"/>
						<rect x="1" y="2" width="6" height="1"/>
						<rect x="0" y="3" width="5" height="1"/>
						<rect x="6" y="3" width="1" height="1"/>
						<rect x="0" y="4" width="9" height="1"/>
						<rect x="0" y="5" width="10" height="1"/>
						<rect x="1" y="6" width="8" height="1"/>
						<rect x="2" y="7" width="2" height="1"/>
						<rect x="6" y="7" width="2" height="1"/>
					</g>
				</svg>
			</span>
			<span class="loader__gauge"><span class="loader__gauge-fill" data-loader-bar></span></span>
		</div>
		<div class="loader__pct mono"><span data-loader-count>000</span>%</div>
	</div>
	<div class="loader__tip mono">TIP: SCROLL TO EXPLORE</div>
	<div class="loader__scan" aria-hidden="true"></div>
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
