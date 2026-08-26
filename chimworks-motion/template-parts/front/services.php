<?php
/**
 * §02 — Service. 流動デザイン版。
 *   数字や箇条書きで固めず、扱う領域が「流れ」として通り過ぎる見せ方。
 *   1) WEBデザイン      — ストリームは左へ流れる
 *   2) 業務効率化支援    — ストリームは右へ流れる（対の関係）
 *   背後には低速で流れる波を敷き、セクション全体に流動感を与える。
 *   ストリームは装飾（aria-hidden）。実体は各ブロックの
 *   スクリーンリーダー用リストが担う。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ACF の連番フィールドから「領域」の一覧を組み立てる。
 *
 * @param string $prefix   フィールド名の接頭辞。
 * @param array  $defaults [ラベル, 補足] の既定値リスト。
 * @return array{label:string,desc:string}[]
 */
function fde_svc_items( string $prefix, array $defaults ): array {
	$items = [];
	foreach ( $defaults as $i => $row ) {
		$n     = $i + 1;
		$label = (string) fde_field( "{$prefix}_{$n}_label", $row[0] );
		if ( '' === trim( $label ) ) {
			continue;
		}
		$items[] = [
			'label' => $label,
			'desc'  => (string) fde_field( "{$prefix}_{$n}_desc", $row[1] ),
		];
	}
	return $items;
}

// ---- Block 1 : WEBデザイン ----
$fde_web_desc = (string) fde_field(
	'svc_web_desc',
	'ホームページ制作からシステム・アプリ開発まで。企画から公開後の運用まで、一貫体制で対応します。'
);
$fde_domains = fde_svc_items(
	'svc_domain',
	[
		[ 'WEBサイト', 'コーポレート / LP / EC' ],
		[ 'WEBシステム', '業務システム・API連携' ],
		[ 'WEBアプリ', '現場で使う道具づくり' ],
		[ 'UI・UXデザイン', '設計から画面まで' ],
		[ '動画制作', 'YouTube / ショート・リール' ],
		[ '生成AI活用', 'AI を組み込んだ体験' ],
	]
);

// 調速フィーチャーの表示切替（ACF のトグル。未設定時は表示）
$fde_chousoku_show = (bool) fde_field( 'chousoku_show', true );
$fde_chousoku_desc = (string) fde_field(
	'chousoku_desc',
	'不動産の物件調査をスムーズに行うための、AI搭載アプリ。物件情報の収集・整理を自動化し、調査業務にかかる時間を大幅に短縮します。'
);
$fde_chousoku_logo = fde_field( 'chousoku_logo' );
$fde_chousoku_url  = (string) fde_field( 'chousoku_url', 'https://usachim.com/cho-haya/' );

// ---- Block 2 : 業務効率化支援 ----
$fde_dx_desc = (string) fde_field(
	'svc_dx_desc',
	'行政・民間企業の現場に入り込み、伴走型で業務のデジタル化を支援します。「導入して終わり」にしない、現場で回り続ける仕組みづくりが強みです。'
);
$fde_dx_detail = (string) fde_field(
	'svc_dx_detail',
	'行政職員としての経験を活かした自治体のDX推進から、民間企業の業務改善まで。生成AI研修やAIアプリ作成、業務フロー改善、PoC作成などを通じて、現場に定着する仕組みをつくります。'
);
$fde_dx_items = fde_svc_items(
	'svc_dx_item',
	[
		[ '生成AI研修', '職員・社員向けの実践研修' ],
		[ 'AIアプリ作成', '現場で使う業務ツール' ],
		[ '業務フロー改善', '手戻りをなくす設計' ],
		[ 'PoC作成', '小さく試して見極める' ],
		[ 'Google Workspace', '導入と定着の支援' ],
		[ 'インフラ検討', '環境整備・個別サポート' ],
	]
);

// 業務効率化支援：流れる画像ストリップ（画像 1〜3。任意）
$fde_svc_dx_images = [];
foreach ( [ 1, 2, 3 ] as $n ) {
	$img = fde_field( "service_2_image_{$n}" );
	if ( is_array( $img ) && ! empty( $img['url'] ) ) {
		$fde_svc_dx_images[] = [ 'url' => $img['url'], 'alt' => $img['alt'] ?? '' ];
	}
}
?>
<section class="section section--dark section--decor svc svc--flow" id="services" data-section="services">
	<?php fde_tri_field( 'tr', 9 ); ?>

	<!-- 背景の波：低速で流れ続け、セクション全体に流動感を与える -->
	<div class="svc-waves" aria-hidden="true">
		<svg class="svc-waves__svg svc-waves__svg--a" viewBox="0 0 2880 220" preserveAspectRatio="none" data-wave data-wave-speed="34">
			<path d="M0,120 C180,60 360,180 720,120 C1080,60 1260,180 1440,120 L1440,220 L0,220 Z" />
			<path d="M1440,120 C1620,60 1800,180 2160,120 C2520,60 2700,180 2880,120 L2880,220 L1440,220 Z" />
		</svg>
		<svg class="svc-waves__svg svc-waves__svg--b" viewBox="0 0 2880 220" preserveAspectRatio="none" data-wave data-wave-speed="52">
			<path d="M0,150 C240,100 420,200 720,150 C1020,100 1200,200 1440,150 L1440,220 L0,220 Z" />
			<path d="M1440,150 C1680,100 1860,200 2160,150 C2460,100 2640,200 2880,150 L2880,220 L1440,220 Z" />
		</svg>
	</div>

	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num">02</span>
				<h2 class="sec-head__title">Service</h2>
			</div>
			<span class="sec-head__meta">事業内容</span>
		</header>

		<!-- ============ Block 1 : WEBデザイン ============ -->
		<article class="svc-block svc-block--flow">
			<header class="svc-block__head">
				<span class="svc-block__no mono">SERVICE 01</span>
				<h3 class="svc-block__title">WEBデザイン</h3>
				<p class="svc-block__desc jp"><?php echo esc_html( $fde_web_desc ); ?></p>
			</header>

			<?php if ( $fde_domains ) : ?>
				<?php
				fde_render_stream(
					$fde_domains,
					[
						'dir'     => -1,
						'variant' => 'a',
						'label'   => 'WEBデザインで扱う領域',
					]
				);
				?>
			<?php endif; ?>

			<!-- ============ 自社サービス：調速 フィーチャー ============ -->
			<?php if ( $fde_chousoku_show ) : ?>
			<div class="svc-feature glass">
				<div class="svc-feature__grid">
					<div class="svc-feature__brand">
						<?php if ( is_array( $fde_chousoku_logo ) && ! empty( $fde_chousoku_logo['url'] ) ) : ?>
							<img class="svc-feature__logo"
							     src="<?php echo esc_url( $fde_chousoku_logo['url'] ); ?>"
							     alt="<?php echo esc_attr( ! empty( $fde_chousoku_logo['alt'] ) ? $fde_chousoku_logo['alt'] : '調速' ); ?>"
							     loading="lazy">
						<?php else : ?>
							<span class="svc-feature__name serif">調速</span>
							<span class="svc-feature__read mono">ちょうはや</span>
						<?php endif; ?>
					</div>
					<div class="svc-feature__body">
						<p class="svc-feature__desc jp"><?php echo esc_html( $fde_chousoku_desc ); ?></p>
						<?php if ( $fde_chousoku_url ) : ?>
							<a class="svc-feature__link" href="<?php echo esc_url( $fde_chousoku_url ); ?>" target="_blank" rel="noopener">
								<span class="mono">調速のサービスサイトを見る</span>
								<span aria-hidden="true">→</span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endif; ?>
		</article>

		<!-- ============ Block 2 : 業務効率化支援 ============ -->
		<article class="svc-block svc-block--flow">
			<header class="svc-block__head">
				<span class="svc-block__no mono">SERVICE 02</span>
				<h3 class="svc-block__title">業務効率化支援</h3>
				<p class="svc-block__desc jp"><?php echo esc_html( $fde_dx_desc ); ?></p>
			</header>

			<?php if ( $fde_dx_items ) : ?>
				<?php
				fde_render_stream(
					$fde_dx_items,
					[
						'dir'     => 1,
						'variant' => 'b',
						'label'   => '業務効率化支援で扱う領域',
					]
				);
				?>
			<?php endif; ?>

			<p class="svc-block__detail jp"><?php echo esc_html( $fde_dx_detail ); ?></p>

			<?php if ( $fde_svc_dx_images ) : ?>
				<div class="svc-strip" data-strip aria-hidden="true">
					<div class="svc-strip__track" data-strip-track>
						<?php foreach ( array_merge( $fde_svc_dx_images, $fde_svc_dx_images ) as $img ) : ?>
							<figure class="svc-strip__item">
								<img src="<?php echo esc_url( $img['url'] ); ?>"
								     alt="<?php echo esc_attr( $img['alt'] ?: '業務効率化支援' ); ?>"
								     loading="lazy">
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</article>
	</div>
</section>
