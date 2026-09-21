<?php
/**
 * Front page — FV → 事業内容 → 会社概要 → お問い合わせ。
 * 名刺代わりの 1 ページ構成。ブログは記事を残したまま、ナビの 1 リンクに。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/front/hero.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/company.php';
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
