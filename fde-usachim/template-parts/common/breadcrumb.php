<?php
/**
 * Breadcrumb component (simple, accessible).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() ) {
	return;
}

$fde_items = [
	[ 'label' => 'Home', 'url' => home_url( '/' ) ],
];

if ( is_singular( 'works' ) ) {
	$fde_items[] = [ 'label' => 'Works', 'url' => get_post_type_archive_link( 'works' ) ];
	$fde_items[] = [ 'label' => get_the_title(), 'url' => '' ];
} elseif ( is_post_type_archive( 'works' ) ) {
	$fde_items[] = [ 'label' => 'Works', 'url' => '' ];
} elseif ( is_tax( 'work_category' ) ) {
	$fde_items[] = [ 'label' => 'Works', 'url' => get_post_type_archive_link( 'works' ) ];
	$fde_items[] = [ 'label' => single_term_title( '', false ), 'url' => '' ];
} elseif ( is_page() ) {
	$fde_items[] = [ 'label' => get_the_title(), 'url' => '' ];
} elseif ( is_search() ) {
	$fde_items[] = [ 'label' => sprintf( '「%s」の検索結果', get_search_query() ), 'url' => '' ];
} elseif ( is_404() ) {
	$fde_items[] = [ 'label' => 'Not Found', 'url' => '' ];
} else {
	return;
}
?>
<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'パンくず', 'fde-usachim' ); ?>">
	<div class="container">
		<ol class="breadcrumb__list">
			<?php foreach ( $fde_items as $i => $item ) : ?>
				<li class="breadcrumb__item">
					<?php if ( ! empty( $item['url'] ) ) : ?>
						<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					<?php else : ?>
						<span aria-current="page"><?php echo esc_html( $item['label'] ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</nav>
