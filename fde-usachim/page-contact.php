<?php
/**
 * Contact page.
 *
 * フォームは Contact Form 7 のショートコードを使用。
 * 「テーマ設定 → 問い合わせ」で登録したショートコードを優先し、
 * 未登録時は固定ページ本文（the_content）を出力。
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

$fde_cf7 = (string) fde_option( 'cf7_shortcode' );
?>

<article class="page-contact">
	<header class="page-head section">
		<div class="container container--narrow">
			<p class="page-head__eyebrow">Contact</p>
			<h1 class="page-head__title">お問い合わせ</h1>
			<p class="page-head__lead">構想段階のご相談だけでも大丈夫です。お気軽にどうぞ。<br>1〜2営業日以内にご返信します。</p>
		</div>
	</header>

	<section class="contact-form section">
		<div class="container container--narrow">
			<?php if ( $fde_cf7 ) : ?>
				<div class="contact-form__wrap">
					<?php echo do_shortcode( $fde_cf7 ); ?>
				</div>
			<?php else : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<div class="entry-content"><?php the_content(); ?></div>
				<?php endwhile; ?>

				<?php if ( ! get_the_content() ) : ?>
					<div class="contact-form__notice">
						<p>フォーム未設定です。管理画面の「テーマ設定 → 問い合わせ」で Contact Form 7 のショートコードを設定するか、固定ページ本文にショートコードを貼り付けてください。</p>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</section>
</article>

<?php
get_footer();
