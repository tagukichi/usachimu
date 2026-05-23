<?php
/**
 * Works archive (and work_category taxonomy fallback).
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

$fde_is_tax    = is_tax( 'work_category' );
$fde_term      = $fde_is_tax ? get_queried_object() : null;
$fde_title     = $fde_is_tax ? $fde_term->name : '実績';
$fde_lead      = $fde_is_tax
	? sprintf( 'カテゴリー「%s」の実績一覧です。', $fde_term->name )
	: '中小企業の DX 伴走、AI 業務自動化、Web 制作などの実績です。';
?>

<article class="page-works">
	<header class="page-head section">
		<div class="container">
			<p class="page-head__eyebrow">Works</p>
			<h1 class="page-head__title"><?php echo esc_html( $fde_title ); ?></h1>
			<p class="page-head__lead"><?php echo esc_html( $fde_lead ); ?></p>
		</div>
	</header>

	<section class="works section">
		<div class="container">
			<?php require FDE_USACHIM_DIR . '/template-parts/works/filter.php'; ?>

			<?php if ( have_posts() ) : ?>
				<ul class="works-grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<li>
							<?php require FDE_USACHIM_DIR . '/template-parts/works/card.php'; ?>
						</li>
					<?php endwhile; ?>
				</ul>

				<?php
				the_posts_pagination(
					[
						'mid_size'  => 1,
						'prev_text' => __( '前へ', 'fde-usachim' ),
						'next_text' => __( '次へ', 'fde-usachim' ),
					]
				);
				?>
			<?php else : ?>
				<p class="works__empty">該当する実績はまだありません。</p>
			<?php endif; ?>
		</div>
	</section>

	<?php require FDE_USACHIM_DIR . '/template-parts/common/cta-block.php'; ?>
</article>

<?php
get_footer();
