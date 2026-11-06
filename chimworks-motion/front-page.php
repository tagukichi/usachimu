<?php
/**
 * Front page — FV（ビジョン）→ 事業内容 → 調速 → Blog。
 * 会社の情報は固定ページ「会社概要」（page-company.php）に置く。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/product.php';
require FDE_USACHIM_DIR . '/template-parts/front/blog.php';

get_footer();
