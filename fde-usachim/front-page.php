<?php
/**
 * Front page (Top).
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/positioning.php';
require FDE_USACHIM_DIR . '/template-parts/front/services-excerpt.php';
require FDE_USACHIM_DIR . '/template-parts/front/approach.php';
require FDE_USACHIM_DIR . '/template-parts/front/works-excerpt.php';
require FDE_USACHIM_DIR . '/template-parts/front/about-excerpt.php';
require FDE_USACHIM_DIR . '/template-parts/front/cta.php';

get_footer();
