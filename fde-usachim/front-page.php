<?php
/**
 * Front page — FV → Concept → Service → About → Contact.
 *
 * @package fde-usachim
 */

get_header();
?>
<div class="hero-scroll" data-hero-scroll>
	<?php require FDE_USACHIM_DIR . '/template-parts/front/hero.php'; ?>
</div>
<?php
require FDE_USACHIM_DIR . '/template-parts/front/concept.php';
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/about.php';
require FDE_USACHIM_DIR . '/template-parts/front/blog.php';
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
