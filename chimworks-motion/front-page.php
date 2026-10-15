<?php
/**
 * Front page — FV（ビジョン）→ 事業内容 → 調速 → Blog → Contact。
 * Contact はフォームを置かずメールアドレスのみ。会社概要はフッターのロゴ下。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/product.php';
require FDE_USACHIM_DIR . '/template-parts/front/blog.php';
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
