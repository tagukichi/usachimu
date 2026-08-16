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
?>

<div class="marquee" data-marquee data-marquee-dir="1" aria-hidden="true">
	<div class="marquee__track" data-marquee-track>
		<span class="marquee__word">Web Development</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word marquee__word--ghost">DX Support</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word">Design</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word marquee__word--ghost">Engineering</span>
		<span class="marquee__dot">●</span>
	</div>
</div>

<?php
require FDE_USACHIM_DIR . '/template-parts/front/services.php';
require FDE_USACHIM_DIR . '/template-parts/front/about.php';
require FDE_USACHIM_DIR . '/template-parts/front/blog.php';
?>

<div class="marquee" data-marquee data-marquee-dir="-1" aria-hidden="true">
	<div class="marquee__track" data-marquee-track>
		<span class="marquee__word marquee__word--ghost">Let's Talk</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word">Chimworks</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word marquee__word--ghost">Contact</span>
		<span class="marquee__dot">●</span>
		<span class="marquee__word">Chimworks</span>
		<span class="marquee__dot">●</span>
	</div>
</div>

<?php
require FDE_USACHIM_DIR . '/template-parts/front/contact.php';

get_footer();
