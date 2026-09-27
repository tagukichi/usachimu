<?php
/**
 * 02 — 会社概要。
 * 入力済みの行だけを定義リストで並べる（ACF は TOP 固定ページのフィールド）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_company_rows = [];
foreach (
	[
		'company_name'     => [ '商号', '合同会社CHIM WORKS' ],
		'company_founded'  => [ '設立', '' ],
		'company_ceo'      => [ '代表者', '' ],
		'company_capital'  => [ '資本金', '' ],
		'company_address'  => [ '所在地', '' ],
		'company_business' => [ '事業内容', "WEB制作・システム開発\n業務効率化・DX支援\n動画制作" ],
	] as $fde_ck => $fde_cl
) {
	$fde_cv = (string) fde_field( $fde_ck, $fde_cl[1] );
	if ( '' !== trim( $fde_cv ) ) {
		$fde_company_rows[] = [ 'k' => $fde_cl[0], 'v' => $fde_cv ];
	}
}
foreach ( [ 1, 2 ] as $fde_cn ) {
	$fde_ck = (string) fde_field( "company_extra_{$fde_cn}_k", '' );
	$fde_cv = (string) fde_field( "company_extra_{$fde_cn}_v", '' );
	if ( '' !== trim( $fde_ck ) && '' !== trim( $fde_cv ) ) {
		$fde_company_rows[] = [ 'k' => $fde_ck, 'v' => $fde_cv ];
	}
}

if ( ! $fde_company_rows ) {
	return;
}
?>
<section class="section section--dark company" id="company" data-section="company">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Company</h2>
			</div>
			<span class="sec-head__meta">会社概要</span>
		</header>

		<dl class="company-list company-list--section">
			<?php foreach ( $fde_company_rows as $fde_row ) : ?>
				<div class="company-list__row">
					<dt class="company-list__k"><?php echo esc_html( $fde_row['k'] ); ?></dt>
					<dd class="company-list__v"><?php echo nl2br( esc_html( $fde_row['v'] ) ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
