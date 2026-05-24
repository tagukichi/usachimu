<?php
/**
 * Single post (Writing).
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	$tag  = function_exists( 'get_field' ) ? (string) get_field( 'tag_label' ) : '';
	$read = function_exists( 'get_field' ) ? (string) get_field( 'read_time' ) : '';
	?>
	<article <?php post_class( 'single-work' ); ?>>
		<header>
			<p class="page-head__eyebrow">§ 07 Blog</p>
			<h1 class="single-work__title"><?php the_title(); ?></h1>
			<div class="writing-card__head" style="margin-bottom:24px;">
				<span class="writing-card__date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
				<?php if ( $read ) : ?><span class="writing-card__read"><?php echo esc_html( $read ); ?> read</span><?php endif; ?>
			</div>
			<?php if ( $tag ) : ?>
				<span class="chip mono" style="margin-bottom:32px;"><?php echo esc_html( $tag ); ?></span>
			<?php endif; ?>
		</header>

		<div class="prose"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
