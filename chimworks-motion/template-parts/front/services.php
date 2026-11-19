<?php
/**
 * 事業内容。
 * 「番号 + 事業名 + 補足テキスト」を 4 つ並べ、各行はそのサービスの詳細ページへリンクする
 * （ホバーで緑の帯が走って文字が白抜きになる）。
 * 既定の事業名・補足・リンク先は inc/service-pages.php の定義。TOP 固定ページの
 * ACF「事業内容」タブで上書きできる。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_url_fields = [ 1 => 'svc_web_url', 2 => 'svc_dx_url', 3 => 'svc_3_url', 4 => 'svc_4_url' ];
$fde_services   = [];
$fde_n          = 0;
foreach ( fde_services() as $fde_key => $fde_def ) {
	++$fde_n;
	$fde_title = trim( (string) fde_field( "svc_{$fde_n}_title", $fde_def['title'] ) );
	if ( '' === $fde_title ) {
		continue;
	}
	$fde_services[] = [
		'no'    => sprintf( '%02d', count( $fde_services ) + 1 ),
		'title' => $fde_title,
		'note'  => trim( (string) fde_field( "svc_{$fde_n}_note", $fde_def['note'] ) ),
		'url'   => trim( (string) fde_field( $fde_url_fields[ $fde_n ], fde_service_url( $fde_key ) ) ),
	];
}
if ( ! $fde_services ) {
	return;
}
?>
<section class="section section--dark svc" id="services" data-section="services">
	<div class="section__inner">
		<header class="sec-head">
			<div class="sec-head__l">
				<span class="sec-head__num"><?php echo esc_html( fde_section_no() ); ?></span>
				<h2 class="sec-head__title">Service</h2>
			</div>
			<span class="sec-head__meta">事業内容</span>
		</header>

		<div class="svc-rows svc-rows--titles">
			<?php foreach ( $fde_services as $fde_svc ) : ?>
				<?php
				$fde_is_link = '' !== trim( $fde_svc['url'] );
				$fde_tag     = $fde_is_link ? 'a' : 'article';
				?>
				<<?php echo $fde_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 'a' か 'article' の固定値 ?>
					class="svc-row<?php echo $fde_is_link ? ' svc-row--link' : ''; ?>"
					<?php if ( $fde_is_link ) : ?>href="<?php echo esc_url( $fde_svc['url'] ); ?>"<?php endif; ?>>
					<div class="svc-row__head">
						<span class="svc-row__no mono"><?php echo esc_html( $fde_svc['no'] ); ?></span>
						<div class="svc-row__text">
							<h3 class="svc-row__title"><?php echo esc_html( $fde_svc['title'] ); ?></h3>
							<?php if ( '' !== $fde_svc['note'] ) : ?>
								<p class="svc-row__note jp"><?php echo nl2br( esc_html( $fde_svc['note'] ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( $fde_is_link ) : ?>
						<span class="svc-row__go" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
						</span>
					<?php endif; ?>
				</<?php echo $fde_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
			<?php endforeach; ?>
		</div>
	</div>
</section>
