<?php
/**
 * §01 — Why Forward Deployed. Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_title   = (string) fde_field( 'why_title', 'AIを味方につけ、次の10年に備える。' );
$fde_premise = (string) fde_field(
	'why_premise',
	'これからの10年、勝ち負けを分けるのは「どんなAIを買ったか」ではなく、**自社のデータを、自社の業務に、自社の手で活かせる状態を作れたか**です。'
);

$fde_pillar_defaults = [
	[ 'no' => '一', 'head' => 'データは「眠っている」。',                  'body' => '請求書はPDF、議事録はWord、顧客の声はメール、設備ログはCSV。社内のあらゆる場所にデータはあるのに、横断して使える状態にはなっていない。AIに食わせる以前に、まずそこを揃える必要があります。' ],
	[ 'no' => '二', 'head' => 'AIは「土台」の上にしか乗らない。',         'body' => '生成AIで業務が変わると言われるけれど、変わるのは下準備ができた現場だけ。データの所在が把握され、整っていて、必要な人が引き出せる ── その地味な土台があって初めて、AIは仕事の役に立ち始めます。' ],
	[ 'no' => '三', 'head' => '作って終わり、ではなく回り続ける仕組みを。', 'body' => 'PoCを納品して関係が切れると、半年後にはほぼ動いていません。データの形が変わり、業務が変わり、誰も触れなくなるからです。現場の変化に追従できる人が、現場の中にいる状態をつくる必要があります。' ],
];
$fde_pillars = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$i = $n - 1;
	$fde_pillars[] = [
		'no'   => (string) fde_field( "why_pillar_{$n}_no",   $fde_pillar_defaults[ $i ]['no'] ),
		'head' => (string) fde_field( "why_pillar_{$n}_head", $fde_pillar_defaults[ $i ]['head'] ),
		'body' => (string) fde_field( "why_pillar_{$n}_body", $fde_pillar_defaults[ $i ]['body'] ),
	];
}

$fde_df_title = (string) fde_field( 'why_dataflow_title', "散らばったデータを、\n現場の判断に届くまで。" );
$fde_df_note  = (string) fde_field(
	'why_dataflow_note',
	"FDEが引き受けるのは、「データがある」から「現場で使われる」までの全長。\n片方の端だけでは、現場は変わりません。"
);
$fde_df_image = fde_field( 'why_dataflow_image' );
$fde_df_diag  = (string) fde_field(
	'why_dataflow_diagram',
	"  ┌────────────┐    ┌────────────┐    ┌────────────┐    ┌────────────┐\n  │  社内資産  │ →  │   整える   │ →  │ AI で活かす │ →  │ 現場で使う │\n  │  scattered │    │  organized │    │   AI/LLM   │    │  in-field  │\n  └────────────┘    └────────────┘    └────────────┘    └────────────┘\n       PDF              schema              RAG               UI/Slack\n       議事録           pipeline            eval              業務に\n       設備ログ         BigQuery            Dify              直接組込\n       問合せ           dbt                 LangGraph         運用 + 改善\n   \n   ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─\n   ※ ふつう、左寄りはエンジニア／右寄りは事業側の仕事として\n     切り分けられる。CHIM はその切れ目を跨ぐところを引き受ける。"
);
?>
<section class="section" id="why" data-section="why">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 01</span>
				<h2 class="sec-head__title jp"><?php echo esc_html( $fde_title ); ?></h2>
			</div>
			<span class="sec-head__meta">WHY FORWARD DEPLOYED</span>
		</header>

		<div class="why__premise">
			<div>
				<div class="why__premise-label">// Premise</div>
				<div class="why__premise-quote serif">&ldquo;</div>
			</div>
			<p class="why__premise-text"><?php echo wp_kses( str_replace( "\n", '<br>', fde_inline_text( $fde_premise ) ), [ 'b' => [], 'br' => [] ] ); ?></p>
		</div>

		<div class="why__pillars">
			<?php foreach ( $fde_pillars as $i => $p ) : ?>
				<div class="why__pillar">
					<div class="why__pillar-head">
						<span class="why__pillar-no"><?php echo esc_html( $p['no'] ); ?></span>
						<span class="why__pillar-idx"><?php echo esc_html( sprintf( '%02d / %02d', $i + 1, count( $fde_pillars ) ) ); ?></span>
					</div>
					<h3 class="why__pillar-title"><?php echo esc_html( $p['head'] ); ?></h3>
					<p class="why__pillar-body"><?php echo esc_html( $p['body'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="why__dataflow">
			<div class="why__dataflow-inner">
				<div>
					<div class="why__dataflow-label">FIG. 02 — Data, in service of the field</div>
					<h3 class="why__dataflow-title"><?php echo nl2br( esc_html( $fde_df_title ) ); ?></h3>
					<p class="why__dataflow-note"><?php echo nl2br( esc_html( $fde_df_note ) ); ?></p>
				</div>
				<?php if ( is_array( $fde_df_image ) && ! empty( $fde_df_image['url'] ) ) : ?>
					<figure class="why__dataflow-figure">
						<img
							src="<?php echo esc_url( $fde_df_image['url'] ); ?>"
							alt="<?php echo esc_attr( $fde_df_image['alt'] ?: 'Data flow — scattered → organized → AI/LLM → in-field' ); ?>"
							loading="lazy"
							<?php if ( ! empty( $fde_df_image['width'] ) ) : ?>width="<?php echo esc_attr( $fde_df_image['width'] ); ?>"<?php endif; ?>
							<?php if ( ! empty( $fde_df_image['height'] ) ) : ?>height="<?php echo esc_attr( $fde_df_image['height'] ); ?>"<?php endif; ?>
						>
					</figure>
				<?php else : ?>
					<pre class="why__dataflow-diagram"><?php echo esc_html( $fde_df_diag ); ?></pre>
				<?php endif; ?>
			</div>
			<span class="why__dataflow-tick why__dataflow-tick--tl" aria-hidden="true"></span>
			<span class="why__dataflow-tick why__dataflow-tick--tr" aria-hidden="true"></span>
			<span class="why__dataflow-tick why__dataflow-tick--bl" aria-hidden="true"></span>
			<span class="why__dataflow-tick why__dataflow-tick--br" aria-hidden="true"></span>
		</div>
	</div>
</section>
