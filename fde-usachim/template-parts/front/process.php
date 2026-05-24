<?php
/**
 * §04 — Process.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_meta  = (string) fde_option( 'process_meta', 'MIN 0w · TYP 10w · MAX 26w' );
$fde_aside = (string) fde_option(
	'process_aside',
	'各フェーズの終わりに、**続けるか・止めるか** を必ず判断します。無理に走り切るより、早く止めるほうが互いの体力を残せます。'
);
$fde_steps = fde_option(
	'process_steps',
	[
		[ 'no' => '00', 'dur' => 'Day 0',      'title' => '初回相談 / Scoping',  'desc' => '60分の無料相談。困りごとと、すでに試したことを伺います。NDA可。' ],
		[ 'no' => '01', 'dur' => 'Week 1–2',   'title' => '現場観察 / Discovery','desc' => '実際の業務を横で見せていただきます。仮説と「やらないこと」を一緒に決めます。' ],
		[ 'no' => '02', 'dur' => 'Week 2–4',   'title' => 'プロトタイプ / Eval', 'desc' => '触れる試作を作り、評価指標で握る。ここで進むか・やめるかを冷静に判断。' ],
		[ 'no' => '03', 'dur' => 'Week 4–10',  'title' => '本番化 / Deploy',     'desc' => 'インフラ・監視・ドキュメントを揃えて本番投入。現場で使えるところまで。' ],
		[ 'no' => '04', 'dur' => 'Month 3+',   'title' => '運用 / 内製化',        'desc' => '伴走しながら社内チームへ引き継ぎ。撤退条件も最初に定義しておきます。' ],
	]
);
?>
<section class="section" id="process" data-section="process">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 04</span>
				<h2 class="sec-head__title">Process — どう進めるか。</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( $fde_meta ); ?></span>
		</header>

		<div class="process__grid">
			<aside class="process__aside jp">
				<div class="process__aside-label">NOTE</div>
				<p><?php echo wp_kses( fde_inline_text( $fde_aside ), [ 'b' => [] ] ); ?></p>
			</aside>

			<div class="process__list">
				<?php foreach ( (array) $fde_steps as $s ) : ?>
					<div class="process-row">
						<div class="process-row__num">
							<span class="process-row__num-label mono"><?php echo esc_html( $s['no'] ?? '' ); ?></span>
						</div>
						<span class="process-row__dur"><?php echo esc_html( $s['dur'] ?? '' ); ?></span>
						<h3 class="process-row__title"><?php echo esc_html( $s['title'] ?? '' ); ?></h3>
						<p class="process-row__desc"><?php echo esc_html( $s['desc'] ?? '' ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
