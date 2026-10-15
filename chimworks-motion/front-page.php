<?php
/**
 * Front page — FV（ビジョン）→ 事業内容 → 調速 → お問い合わせ。
 * 会社概要はフッターのロゴ下に控えめに置く。ブログは記事を残したまま、ナビの 1 リンクに。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/product.php';
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
