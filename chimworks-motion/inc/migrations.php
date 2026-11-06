<?php
/**
 * URL migrations from the legacy theme.
 *
 * NOTE: works CPT 廃止に伴い、旧 /web/ → /works/ の 301 リダイレクトは
 * 無効化しました。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 固定ページ「会社概要」（/company/）を一度だけ自動作成する。
 * 管理画面を開いたときに、同じスラッグのページが無ければ作る。
 * 中身（商号・設立など）は page-company.php が表で出し、
 * ページ編集画面の ACF「会社概要」で変更できる。
 */
add_action(
	'admin_init',
	static function (): void {
		if ( get_option( 'fde_company_page_created' ) || ! current_user_can( 'publish_pages' ) ) {
			return;
		}
		if ( ! get_page_by_path( 'company' ) ) {
			$id = wp_insert_post(
				[
					'post_type'     => 'page',
					'post_status'   => 'publish',
					'post_title'    => '会社概要',
					'post_name'     => 'company',
					'post_content'  => '',
					'page_template' => 'page-company.php',
				],
				true
			);
			if ( is_wp_error( $id ) ) {
				return;
			}
		}
		update_option( 'fde_company_page_created', 1, false );
	}
);
