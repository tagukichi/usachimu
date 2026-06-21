<?php
/**
 * Front page (single-page editorial layout).
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/news.php';
require FDE_USACHIM_DIR . '/template-parts/front/why.php';
require FDE_USACHIM_DIR . '/template-parts/front/about.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/writing.php';

get_footer();
