<?php
/**
 * Works category filter (archive page).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_terms = get_terms(
	[
		'taxonomy'   => 'work_category',
		'hide_empty' => true,
	]
);

if ( empty( $fde_terms ) || is_wp_error( $fde_terms ) ) {
	return;
}

$fde_current = is_tax( 'work_category' ) ? get_queried_object() : null;
?>
<nav class="works-filter" aria-label="<?php esc_attr_e( 'カテゴリーで絞り込み', 'fde-usachim' ); ?>">
	<ul class="works-filter__list">
		<li>
			<a class="works-filter__item <?php echo $fde_current ? '' : 'is-active'; ?>"
			   href="<?php echo esc_url( get_post_type_archive_link( 'works' ) ); ?>">
				<?php esc_html_e( 'すべて', 'fde-usachim' ); ?>
			</a>
		</li>
		<?php foreach ( $fde_terms as $fde_term ) : ?>
			<?php $fde_active = $fde_current && (int) $fde_current->term_id === (int) $fde_term->term_id; ?>
			<li>
				<a class="works-filter__item <?php echo $fde_active ? 'is-active' : ''; ?>"
				   href="<?php echo esc_url( get_term_link( $fde_term ) ); ?>">
					<?php echo esc_html( $fde_term->name ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
