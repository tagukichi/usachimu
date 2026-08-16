<?php
/**
 * Search form.
 *
 * @package fde-usachim
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'サイト内検索', 'fde-usachim' ); ?></span>
		<input type="search" class="search-form__input" placeholder="<?php esc_attr_e( '検索…', 'fde-usachim' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="button button--ghost"><?php esc_html_e( '検索', 'fde-usachim' ); ?></button>
</form>
