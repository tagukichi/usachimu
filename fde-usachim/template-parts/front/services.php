<?php
/**
 * §03 — Services (image-led alternating layout).
 * 3 sub-services: Web開発 / SaaS開発 / 社内DX支援
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_service_defaults = [
	[
		'no'    => '01',
		'title' => 'Web開発・アプリ制作',
		'lead'  => 'ブランドサイトから業務アプリまで、目的に合わせて設計・実装します。',
		'desc'  => 'コーポレートサイト、サービスサイト、ランディングページ、Web/モバイルアプリの開発まで一気通貫で対応します。ヒアリングから情報設計、デザイン、実装、運用までを一貫体制で進めるため、要件のブレや手戻りを抑え、ビジネス成果に直結するプロダクトをスピーディーに届けます。',
		'tags'  => 'Next.js, React, TypeScript, WordPress, Figma',
	],
	[
		'no'    => '02',
		'title' => 'SaaS開発',
		'lead'  => 'ゼロからのプロダクト立ち上げ、既存SaaSの機能拡張をサポートします。',
		'desc'  => 'プロダクトの要件定義から MVP 開発、グロース後の機能拡張、保守運用まで、フェーズに合わせた体制で伴走します。マルチテナント設計、課金導線、API連携、認証基盤など、SaaSに不可欠な構成要素をベストプラクティスに沿って実装し、スケールに耐えるプロダクトを育てます。',
		'tags'  => 'Next.js, Hono, Supabase, AWS, Stripe',
	],
	[
		'no'    => '03',
		'title' => '社内DX支援',
		'lead'  => '業務の見える化からツール導入・内製化まで、現場目線で進めます。',
		'desc'  => '紙やExcel中心の業務を、kintone・Notion・スプレッドシート連携・生成AIなどを組み合わせて段階的にデジタル化します。現場ヒアリングから業務フロー設計、ツール選定、定着支援、内製化のための研修まで対応。「導入して終わり」にならない、現場で回り続ける仕組みづくりが強みです。',
		'tags'  => 'kintone, Notion, Google Workspace, 生成AI, RPA',
	],
];

$fde_services = [];
foreach ( [ 1, 2, 3 ] as $n ) {
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
		'no'    => (string) fde_field( "service_{$n}_no",    $fde_service_defaults[ $i ]['no'] ),
		'title' => $title,
		'lead'  => (string) fde_field( "service_{$n}_lead",  $fde_service_defaults[ $i ]['lead'] ),
		'desc'  => (string) fde_field( "service_{$n}_desc",  $fde_service_defaults[ $i ]['desc'] ),
		'tags'  => fde_split_tags( (string) fde_field( "service_{$n}_tags", $fde_service_defaults[ $i ]['tags'] ) ),
		'image' => fde_field( "service_{$n}_image" ),
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
			<span class="sec-head__meta">事業内容</span>
		</header>

		<p class="svc-intro jp">プロダクト開発から社内のDXまで。技術と現場理解の両輪で、課題を仕組みに変えます。</p>

		<div class="svc-list">
			<?php foreach ( $fde_services as $i => $s ) : ?>
				<?php $flipped = ( $i % 2 === 1 ) ? 'true' : 'false'; ?>
				<article class="svc-row" data-flipped="<?php echo esc_attr( $flipped ); ?>">
					<figure class="svc-row__media">
						<?php if ( is_array( $s['image'] ) && ! empty( $s['image']['url'] ) ) : ?>
							<img
								src="<?php echo esc_url( $s['image']['url'] ); ?>"
								alt="<?php echo esc_attr( ! empty( $s['image']['alt'] ) ? $s['image']['alt'] : $s['title'] ); ?>"
								loading="lazy"
								<?php if ( ! empty( $s['image']['width'] ) ) : ?>width="<?php echo esc_attr( $s['image']['width'] ); ?>"<?php endif; ?>
								<?php if ( ! empty( $s['image']['height'] ) ) : ?>height="<?php echo esc_attr( $s['image']['height'] ); ?>"<?php endif; ?>
							>
						<?php else : ?>
							<div class="svc-row__placeholder" aria-hidden="true">
								<span class="svc-row__placeholder-no mono"><?php echo esc_html( $s['no'] ); ?></span>
							</div>
						<?php endif; ?>
					</figure>

					<div class="svc-row__body">
						<span class="svc-row__no mono">SERVICE <?php echo esc_html( $s['no'] ); ?></span>
						<h3 class="svc-row__title"><?php echo esc_html( $s['title'] ); ?></h3>
						<?php if ( $s['lead'] ) : ?>
							<p class="svc-row__lead jp"><?php echo esc_html( $s['lead'] ); ?></p>
						<?php endif; ?>
						<?php if ( $s['desc'] ) : ?>
							<p class="svc-row__desc jp"><?php echo esc_html( $s['desc'] ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $s['tags'] ) ) : ?>
							<div class="svc-row__tags">
								<?php foreach ( $s['tags'] as $t ) : ?>
									<span class="chip mono"><?php echo esc_html( $t ); ?></span>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
