<?php
/**
 * Work card.
 *
 * Expects $post to be set (use setup_postdata before requiring, or rely on the loop).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_terms     = get_the_terms( get_the_ID(), 'work_category' );
$fde_client    = function_exists( 'get_field' ) ? (string) get_field( 'client_name' ) : '';
?>
<a class="work-card" href="<?php the_permalink(); ?>">
	<div class="work-card__thumb">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'work-card', [ 'loading' => 'lazy', 'class' => 'work-card__img' ] ); ?>
		<?php else : ?>
			<div class="work-card__noimg" aria-hidden="true">No Image</div>
		<?php endif; ?>
	</div>
	<div class="work-card__body">
		<?php if ( ! empty( $fde_terms ) && ! is_wp_error( $fde_terms ) ) : ?>
			<ul class="work-card__terms">
				<?php foreach ( $fde_terms as $fde_term ) : ?>
					<li><?php echo esc_html( $fde_term->name ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<h3 class="work-card__title"><?php the_title(); ?></h3>
		<?php if ( $fde_client ) : ?>
			<p class="work-card__client"><?php echo esc_html( $fde_client ); ?></p>
		<?php endif; ?>
	</div>
</a>
