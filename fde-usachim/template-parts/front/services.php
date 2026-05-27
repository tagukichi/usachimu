<?php
/**
 * §03 — Services (alternating spreads with optional media + details).
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

$fde_page_id = function_exists( 'get_field' ) ? (int) get_option( 'page_on_front' ) : 0;

$fde_services = [];
foreach ( [ 1, 2, 3, 4 ] as $n ) {
	$i = $n - 1;

	// title を raw で取得：
	//   - null / false（未保存）       → デフォルトでフォールバック表示
	//   - '' （editor が明示的に空保存）→ サービスごと非表示
	//   - その他                         → 入力値を使う
	$title_raw = $fde_page_id ? get_field( "service_{$n}_title", $fde_page_id ) : null;

	if ( '' === $title_raw || ( is_string( $title_raw ) && '' === trim( $title_raw ) ) ) {
		continue;
	}

	$title = ( null === $title_raw || false === $title_raw )
		? $fde_service_defaults[ $i ]['title']
		: (string) $title_raw;

	$fde_services[] = [
		'slot'      => $n,
		'no'        => (string) fde_field( "service_{$n}_no",        $fde_service_defaults[ $i ]['no'] ),
		'title'     => $title,
		'meta'      => (string) fde_field( "service_{$n}_meta",      $fde_service_defaults[ $i ]['meta'] ),
		'desc'      => (string) fde_field( "service_{$n}_desc",      $fde_service_defaults[ $i ]['desc'] ),
		'image'     => fde_field( "service_{$n}_image" ),
		'long_desc' => (string) fde_field( "service_{$n}_long_desc", '' ),
		'for'       => fde_split_lines( (string) fde_field( "service_{$n}_for",   '' ) ),
		'steps'     => fde_split_lines( (string) fde_field( "service_{$n}_steps", '' ) ),
		'period'    => (string) fde_field( "service_{$n}_period",    '' ),
		'link_url'  => (string) fde_field( "service_{$n}_link_url",  '' ),
	];
}

$fde_note  = (string) fde_field( 'services_note', '契約は **成果物 / マイルストーン単位** が原則。月額顧問契約も可。人月単価の常駐・派遣は行っていません。' );
$fde_count = count( $fde_services );

if ( 0 === $fde_count ) {
	return;
}
?>
<section class="section" id="services" data-section="services">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 03</span>
				<h2 class="sec-head__title">Services</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( sprintf( '%02d OFFERINGS', $fde_count ) ); ?></span>
		</header>

		<div class="services__spreads">
			<?php foreach ( $fde_services as $s ) :
				// alternation はスロット番号で固定（途中で1つ非表示にしても順序が保持される）
				$flipped = ( $s['slot'] % 2 === 0 ) ? 'true' : 'false';
				$has_img = is_array( $s['image'] ) && ! empty( $s['image']['url'] );
				?>
				<article class="service-spread" data-flipped="<?php echo esc_attr( $flipped ); ?>" data-no-media="<?php echo $has_img ? 'false' : 'true'; ?>">

					<?php if ( $has_img ) : ?>
						<figure class="service-spread__media">
							<img
								src="<?php echo esc_url( $s['image']['url'] ); ?>"
								alt="<?php echo esc_attr( ! empty( $s['image']['alt'] ) ? $s['image']['alt'] : $s['title'] ); ?>"
								loading="lazy"
								<?php if ( ! empty( $s['image']['width'] ) ) : ?>width="<?php echo esc_attr( $s['image']['width'] ); ?>"<?php endif; ?>
								<?php if ( ! empty( $s['image']['height'] ) ) : ?>height="<?php echo esc_attr( $s['image']['height'] ); ?>"<?php endif; ?>
							>
						</figure>
					<?php endif; ?>

					<div class="service-spread__content">
						<header class="service-spread__head">
							<?php if ( $s['no'] ) : ?>
								<span class="service-spread__no serif"><?php echo esc_html( $s['no'] ); ?></span>
							<?php endif; ?>
							<div class="service-spread__head-text">
								<h3 class="service-spread__title"><?php echo esc_html( $s['title'] ); ?></h3>
								<?php if ( $s['meta'] ) : ?>
									<span class="service-spread__meta mono"><?php echo esc_html( $s['meta'] ); ?></span>
								<?php endif; ?>
							</div>
						</header>

						<?php if ( $s['desc'] ) : ?>
							<p class="service-spread__desc jp"><?php echo esc_html( $s['desc'] ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== trim( $s['long_desc'] ) ) : ?>
							<div class="service-spread__long jp">
								<?php echo wp_kses( fde_paragraphs( $s['long_desc'] ), [ 'p' => [], 'b' => [], 'br' => [] ] ); ?>
							</div>
						<?php endif; ?>

						<?php
						$detail_blocks = array_filter( [
							'こんなとき' => $s['for'],
							'進め方'     => $s['steps'],
						] );
						?>
						<?php if ( $detail_blocks ) : ?>
							<div class="service-spread__details">
								<?php foreach ( $detail_blocks as $label => $items ) : ?>
									<section>
										<div class="service-spread__label mono"><?php echo esc_html( $label ); ?></div>
										<ul class="service-spread__list jp">
											<?php foreach ( $items as $item ) : ?>
												<li><?php echo esc_html( $item ); ?></li>
											<?php endforeach; ?>
										</ul>
									</section>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<?php if ( $s['period'] || $s['link_url'] ) : ?>
							<footer class="service-spread__foot">
								<?php if ( $s['period'] ) : ?>
									<span class="service-spread__period mono">期間目安 / <?php echo esc_html( $s['period'] ); ?></span>
								<?php endif; ?>
								<?php if ( $s['link_url'] ) : ?>
									<a class="service-spread__more mono" href="<?php echo esc_url( $s['link_url'] ); ?>">詳しく見る →</a>
								<?php endif; ?>
							</footer>
						<?php endif; ?>
					</div>
				</article>
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
