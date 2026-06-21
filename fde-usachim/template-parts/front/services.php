<?php
/**
 * §03 — Services (simplified card grid).
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_service_defaults = [
	[ 'no' => '01', 'title' => 'AIプロダクト 0→1',     'meta' => 'LLM / RAG',        'desc' => '業務に組み込まれ、使われるAIをつくる。' ],
	[ 'no' => '02', 'title' => 'データ基盤の整備',       'meta' => 'BigQuery / dbt',   'desc' => '散らばったデータを、使える状態に整える。' ],
	[ 'no' => '03', 'title' => '社内ナレッジ / 検索',     'meta' => 'RAG / Search',     'desc' => '社内に眠る資産を、引き出せる形に。' ],
	[ 'no' => '04', 'title' => '内製化支援 / 顧問',       'meta' => 'Pair / Review',    'desc' => '引き継いだ後も、現場で回り続ける体制へ。' ],
];

$fde_services = [];
foreach ( [ 1, 2, 3, 4 ] as $n ) {
	$i = $n - 1;
	$title_raw = function_exists( 'get_field' ) && (int) get_option( 'page_on_front' )
		? get_field( "service_{$n}_title", (int) get_option( 'page_on_front' ) )
		: null;
	if ( '' === $title_raw || ( is_string( $title_raw ) && '' === trim( $title_raw ) ) ) {
		continue;
	}
	$title = ( null === $title_raw || false === $title_raw )
		? $fde_service_defaults[ $i ]['title']
		: (string) $title_raw;
	$fde_services[] = [
		'no'    => (string) fde_field( "service_{$n}_no",   $fde_service_defaults[ $i ]['no'] ),
		'title' => $title,
		'meta'  => (string) fde_field( "service_{$n}_meta", $fde_service_defaults[ $i ]['meta'] ),
		'desc'  => (string) fde_field( "service_{$n}_desc", $fde_service_defaults[ $i ]['desc'] ),
	];
}
if ( empty( $fde_services ) ) {
	return;
}
?>
<section class="section section--decor" id="services" data-section="services">
	<?php fde_tri_field( 'tr', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 03</span>
				<h2 class="sec-head__title">Services</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( sprintf( '%02d', count( $fde_services ) ) ); ?></span>
		</header>

		<div class="svc">
			<?php foreach ( $fde_services as $s ) : ?>
				<div class="svc__item">
					<span class="svc__no mono"><?php echo esc_html( $s['no'] ); ?></span>
					<h3 class="svc__title"><?php echo esc_html( $s['title'] ); ?></h3>
					<?php if ( $s['desc'] ) : ?>
						<p class="svc__desc jp"><?php echo esc_html( $s['desc'] ); ?></p>
					<?php endif; ?>
					<?php if ( $s['meta'] ) : ?>
						<span class="svc__meta mono"><?php echo esc_html( $s['meta'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
