<?php
/**
 * Admin notices to guide initial setup.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Notice when no static front page is set — TOP text fields will live on
 * that page, so editing requires it.
 */
add_action(
	'admin_notices',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$page_on_front = (int) get_option( 'page_on_front' );
		$show_on_front = get_option( 'show_on_front' );

		if ( 'page' === $show_on_front && $page_on_front > 0 ) {
			return;
		}

		$reading_url = admin_url( 'options-reading.php' );
		?>
		<div class="notice notice-warning">
			<p>
				<strong><?php esc_html_e( '[CHIM WORKS] TOPページのテキストを編集するには：', 'fde-usachim' ); ?></strong>
				<br>
				<?php
				printf(
					/* translators: %s: URL to Reading Settings */
					wp_kses_post( __( '<a href="%s">設定 → 表示設定</a> で「ホームページの表示」を「固定ページ」にし、TOPページ用の固定ページ（中身は空でOK）を「ホームページ」に指定してください。指定した固定ページの編集画面に各セクションのテキストフィールドが表示されます。', 'fde-usachim' ) ),
					esc_url( $reading_url )
				);
				?>
			</p>
		</div>
		<?php
	}
);

/**
 * Editor note on the front page itself — body content is not used.
 */
add_action(
	'edit_form_after_title',
	static function ( $post ) {
		if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
			return;
		}
		if ( (int) $post->ID !== (int) get_option( 'page_on_front' ) ) {
			return;
		}
		?>
		<div class="notice notice-info inline" style="margin: 12px 0; padding: 10px 12px;">
			<p style="margin: 0;">
				<strong><?php esc_html_e( '[CHIM WORKS] このページの本文は使用されません。', 'fde-usachim' ); ?></strong>
				<?php esc_html_e( '各セクションのテキストは、編集画面下部の「TOPページ — セクションテキスト」タブから編集してください。', 'fde-usachim' ); ?>
			</p>
		</div>
		<?php
	}
);
