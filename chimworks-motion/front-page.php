<?php
/**
 * Front page — FV（ビジョン）→ 事業内容 → 調速。
 * 会社概要と連絡先メールはフッターのロゴ下に控えめに置く（問い合わせフォームは置かない）。ブログは記事を残したまま、ナビの 1 リンクに。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/product.php';

get_footer();
