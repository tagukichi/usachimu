<?php
/**
 * TOP — 解決の手段（課題に合わせて組み合わせるソリューション）と、対応ツールの帯。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_items = fde_pipe_rows( fde_lp_text( 'solution_items' ) );
$fde_lead  = fde_lp_text( 'solution_lead' );
$fde_tools = array_values( array_filter( array_map( 'trim', preg_split( '/[、,]/u', fde_lp_text( 'solution_tools' ) ) ?: [] ), 'strlen' ) );
if ( ! $fde_items ) {
	return;
}
?>
<section class="section section--dark lp-solutions" id="solutions" data-section="solutions">
	<div class="section__inner">
		<?php fde_svp_head( 'Solutions', '解決の手段' ); ?>
		<?php if ( '' !== $fde_lead ) : ?>
			<p class="lp-lead"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
		<?php endif; ?>
		<?php fde_svp_cards( $fde_items, 'solutions' ); ?>
	</div>
</section>

<?php if ( $fde_tools ) : ?>
	<div class="svp-band lp-tools" aria-label="対応しているツール・技術">
		<div class="svp-band__track">
			<?php for ( $fde_loop = 0; $fde_loop < 2; $fde_loop++ ) : ?>
				<?php foreach ( $fde_tools as $fde_tool ) : ?>
					<?php $fde_logo = fde_brand_icon( $fde_tool ); ?>
					<span class="svp-band__item"<?php echo $fde_loop ? ' aria-hidden="true"' : ''; ?>>
						<?php if ( $fde_logo ) : ?>
							<img src="<?php echo esc_url( $fde_logo ); ?>" alt="" width="20" height="20">
						<?php else : ?>
							<i></i>
						<?php endif; ?>
						<?php echo esc_html( $fde_tool ); ?>
					</span>
				<?php endforeach; ?>
			<?php endfor; ?>
		</div>
	</div>
<?php endif; ?>
