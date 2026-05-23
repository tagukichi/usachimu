<?php
/**
 * 404 template.
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="container container--narrow">
		<h1><?php esc_html_e( 'ページが見つかりませんでした', 'fde-usachim' ); ?></h1>
		<p><?php esc_html_e( 'URL が変更されたか、削除された可能性があります。', 'fde-usachim' ); ?></p>
		<p>
			<a class="button button--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'トップへ戻る', 'fde-usachim' ); ?></a>
		</p>
	</div>
</section>

<?php
get_footer();
