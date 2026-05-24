<?php
/**
 * §03 — Services. Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_service_defaults = [
	[ 'no' => 'I',   'title' => 'AIプロダクト 0→1',         'meta' => 'LLM / RAG / Eval',         'desc' => '業務観察 〜 ユースケース定義 〜 プロトタイプ 〜 本番リリース 〜 改善ループまで。PoCを「使われる機能」に変えるところを主戦場にしています。' ],
	[ 'no' => 'II',  'title' => 'データパイプライン整備',     'meta' => 'BigQuery / dbt / Airbyte', 'desc' => '散らばった業務データを集めて、揃えて、配る。AI活用のための土台をつくる、地味で大事な仕事です。' ],
	[ 'no' => 'III', 'title' => '社内ナレッジ / 検索基盤',     'meta' => 'RAG / Vector / Eval',      'desc' => '議事録・契約書・問い合わせログなど、社内に眠る資産を検索可能に。評価指標を握って、運用しながら磨きます。' ],
	[ 'no' => 'IV',  'title' => '内製化支援 / 技術顧問',       'meta' => 'Pair / Review / Hiring',   'desc' => '一人で完結させるのではなく、社内エンジニアと組みます。引き継いだあと、現場が回り続けることを最重要視。' ],
];
$fde_services = [];
foreach ( [ 1, 2, 3, 4 ] as $n ) {
	$i = $n - 1;
	$fde_services[] = [
		'no'    => (string) fde_field( "service_{$n}_no",    $fde_service_defaults[ $i ]['no'] ),
		'title' => (string) fde_field( "service_{$n}_title", $fde_service_defaults[ $i ]['title'] ),
		'meta'  => (string) fde_field( "service_{$n}_meta",  $fde_service_defaults[ $i ]['meta'] ),
		'desc'  => (string) fde_field( "service_{$n}_desc",  $fde_service_defaults[ $i ]['desc'] ),
	];
}
$fde_note  = (string) fde_field( 'services_note', '契約は **成果物 / マイルストーン単位** が原則。月額顧問契約も可。人月単価の常駐・派遣は行っていません。' );
$fde_count = count( $fde_services );
?>
<section class="section" id="services" data-section="services">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 03</span>
				<h2 class="sec-head__title">Services — 何を提供するか。</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( sprintf( '%02d OFFERINGS', $fde_count ) ); ?></span>
		</header>

		<div class="services__list">
			<?php foreach ( $fde_services as $s ) : ?>
				<div class="service-row">
					<div class="service-row__no serif"><?php echo esc_html( $s['no'] ); ?></div>
					<h3 class="service-row__title"><?php echo esc_html( $s['title'] ); ?></h3>
					<p class="service-row__desc"><?php echo esc_html( $s['desc'] ); ?></p>
					<div class="service-row__meta"><?php echo esc_html( $s['meta'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $fde_note ) : ?>
			<div class="services__note">
				<span class="services__note-text"><?php echo wp_kses( fde_inline_text( $fde_note ), [ 'b' => [] ] ); ?></span>
				<span class="services__note-link">→ §04 PROCESS</span>
			</div>
		<?php endif; ?>
	</div>
</section>
