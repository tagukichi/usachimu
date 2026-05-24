<?php
/**
 * Front page (single-page editorial layout).
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/why.php';
require FDE_USACHIM_DIR . '/template-parts/front/about.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/process.php';
require FDE_USACHIM_DIR . '/template-parts/front/work.php';
require FDE_USACHIM_DIR . '/template-parts/front/stack.php';
require FDE_USACHIM_DIR . '/template-parts/front/writing.php';
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
