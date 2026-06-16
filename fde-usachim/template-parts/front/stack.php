<?php
/**
 * §06 — Stack (dark). Editable via the front page editor.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_meta = (string) fde_field( 'stack_meta', 'REVIEWED ' . date_i18n( 'Y.m' ) );

$fde_group_defaults = [
	[ 'g' => 'AI / LLM',            'items' => 'OpenAI, Anthropic, Local LLM, RAG, Eval, LangGraph' ],
	[ 'g' => 'Workflow / Low-code', 'items' => 'Dify, n8n, kintone, Salesforce, GAS, Zapier' ],
	[ 'g' => 'Language',            'items' => 'Python, TypeScript, SQL, Go (sub)' ],
	[ 'g' => 'Data',                'items' => 'BigQuery, Snowflake, dbt, Airbyte, Fivetran' ],
	[ 'g' => 'Cloud / Ops',         'items' => 'GCP, AWS, Vercel, Cloudflare, GWS, Terraform' ],
	[ 'g' => 'Product',             'items' => 'Next.js, Hono, FastAPI, Supabase' ],
];
$fde_groups = [];
foreach ( [ 1, 2, 3, 4, 5, 6 ] as $n ) {
	$i = $n - 1;
	$fde_groups[] = [
		'g'     => (string) fde_field( "stack_g_{$n}_g",     $fde_group_defaults[ $i ]['g'] ),
		'items' => (string) fde_field( "stack_g_{$n}_items", $fde_group_defaults[ $i ]['items'] ),
	];
}
?>
<section class="section section--dark section--decor" id="stack" data-section="stack">
	<?php fde_tri_field( 'tl', 10 ); ?>
	<?php fde_tri_field( 'br', 9 ); ?>
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">§ 06</span>
				<h2 class="sec-head__title">Stack</h2>
			</div>
			<span class="sec-head__meta"><?php echo esc_html( $fde_meta ); ?></span>
		</header>

		<div class="stack__grid">
			<?php foreach ( $fde_groups as $i => $grp ) : ?>
				<?php $items = fde_split_tags( $grp['items'] ); ?>
				<div class="stack-row" data-col="<?php echo esc_attr( $i % 2 ); ?>">
					<div>
						<div class="stack-row__idx"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></div>
						<div class="stack-row__title serif"><?php echo esc_html( $grp['g'] ); ?></div>
					</div>
					<div class="stack-row__items mono">
						<?php foreach ( $items as $j => $it ) : ?>
							<span><?php echo esc_html( $it ); ?></span><?php if ( $j < count( $items ) - 1 ) : ?><span class="stack-row__items-sep">·</span><?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
