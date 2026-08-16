<?php
/**
 * Generic page template.
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'single-work' ); ?>>
		<header class="page-head">
			<p class="page-head__eyebrow">Page</p>
			<h1 class="page-head__title"><?php the_title(); ?></h1>
		</header>
		<div class="prose">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
