<?php
/**
 * Template Name: 会社概要
 *
 * 固定ページ「会社概要」（スラッグ company なら自動で適用）。
 * 商号・設立・代表者などを表で出す。内容はページ編集画面の
 * ACF「会社概要」で変更でき、未入力の項目は既定値を表示する。
 * 本文エディタに書いた内容は、表の下に続けて表示する。
 *
 * @package fde-usachim
 */

get_header();
require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

while ( have_posts() ) :
	the_post();
	$fde_rows = fde_company_rows();
	?>
	<article <?php post_class( 'single-work company-page' ); ?>>
		<header class="page-head">
			<p class="page-head__eyebrow">Company</p>
			<h1 class="page-head__title"><?php the_title(); ?></h1>
		</header>

		<?php if ( $fde_rows ) : ?>
			<dl class="company-table">
				<?php foreach ( $fde_rows as $fde_row ) : ?>
					<div class="company-table__row">
						<dt class="company-table__k"><?php echo esc_html( $fde_row['k'] ); ?></dt>
						<dd class="company-table__v">
							<?php if ( ! empty( $fde_row['mail'] ) ) : ?>
								<a href="mailto:<?php echo esc_attr( $fde_row['v'] ); ?>"><?php echo esc_html( $fde_row['v'] ); ?></a>
							<?php else : ?>
								<?php echo nl2br( esc_html( $fde_row['v'] ) ); ?>
							<?php endif; ?>
						</dd>
					</div>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>

		<?php if ( '' !== trim( (string) get_the_content() ) ) : ?>
			<div class="prose company-page__note">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>
	</article>
	<?php
endwhile;

get_footer();
