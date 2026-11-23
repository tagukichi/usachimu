<?php
/**
 * Front page — 伴走支援を 1 ページで伝える構成。
 * FV（ビジョン）→ こんなお悩み → 伴走支援 → 伴走の流れ → 解決の手段 → 実績 →
 * 調速 → よくある質問 → Blog → お問い合わせ
 * 会社の情報は固定ページ「会社概要」（page-company.php）に置く。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/pain.php';
require FDE_USACHIM_DIR . '/template-parts/front/support.php';
require FDE_USACHIM_DIR . '/template-parts/front/solutions.php';
require FDE_USACHIM_DIR . '/template-parts/front/cases.php';
require FDE_USACHIM_DIR . '/template-parts/front/product.php';
require FDE_USACHIM_DIR . '/template-parts/front/faq.php';
require FDE_USACHIM_DIR . '/template-parts/front/blog.php';
fde_svp_contact();

get_footer();
