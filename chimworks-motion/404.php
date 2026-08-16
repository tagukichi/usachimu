<?php
/**
 * 404.
 *
 * @package fde-usachim
 */

get_header();
?>

<section class="section">
	<div class="section__inner" style="text-align:center;">
		<p class="page-head__eyebrow">404</p>
		<h1 class="page-head__title">ページが見つかりませんでした</h1>
		<p class="prose" style="margin-top:24px;">URL が変更されたか、削除された可能性があります。</p>
		<p style="margin-top:32px;">
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップへ戻る</a>
		</p>
	</div>
</section>

<?php
get_footer();
