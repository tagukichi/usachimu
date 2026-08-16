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
			<span class="loader__rabbit" data-loader-sprite>
				<svg viewBox="0 0 140 70" fill="none" aria-hidden="true">
					<defs>
						<linearGradient id="fde-rabbit-g" x1="0" y1="0" x2="1" y2="0">
							<stop offset="0" stop-color="#0ae448"/>
							<stop offset="1" stop-color="#00bae2"/>
						</linearGradient>
					</defs>
					<g fill="url(#fde-rabbit-g)">
						<path d="M127,22 C130,25 129,29 125,32 C119,35 112,36 105,37 C109,43 116,50 122,57 C123,59 121,61 119,59 C112,54 105,48 100,43 C88,46 74,46 62,43 C52,50 40,59 28,67 C25,69 22,68 23,65 C27,59 32,52 37,46 C30,43 26,37 25,30 C21,28 20,24 23,21 C27,18 32,17 37,16 C54,9 76,8 93,12 C102,10 110,12 116,15 C122,17 125,19 127,22 Z"/>
						<path d="M108,15 C100,8 88,3 78,1 C74,0 72,2 75,5 C83,9 93,13 102,17 C104,18 107,17 108,15 Z"/>
						<path d="M113,18 C104,13 94,9 85,8 C81,8 80,11 84,13 C92,16 100,19 107,21 C110,22 112,20 113,18 Z"/>
					</g>
					<circle cx="115" cy="21" r="2.4" fill="#0b0d0c"/>
				</svg>
			</span>
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
