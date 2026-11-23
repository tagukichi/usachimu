<?php
/**
 * TOP — 伴走支援（考え方と 3 つの特長）と、伴走の流れ（黒いセクション）。
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$fde_lead  = fde_lp_text( 'support_lead' );
$fde_items = fde_pipe_rows( fde_lp_text( 'support_items' ) );
$fde_flow  = fde_pipe_rows( fde_lp_text( 'flow_items' ) );
$fde_term  = fde_lp_text( 'flow_period' );
?>
<section class="section section--dark lp-support" id="support" data-section="support">
	<div class="section__inner">
		<?php fde_svp_head( 'Support', '伴走支援' ); ?>
		<?php if ( '' !== $fde_lead ) : ?>
			<p class="lp-lead"><?php echo nl2br( esc_html( $fde_lead ) ); ?></p>
		<?php endif; ?>
		<?php if ( $fde_items ) : ?>
			<div class="svp-pillars">
				<?php foreach ( $fde_items as $fde_i => $fde_item ) : ?>
					<article class="svp-pillar">
						<span class="svp-pillar__no mono"><?php echo esc_html( sprintf( '%02d', $fde_i + 1 ) ); ?></span>
						<h3 class="svp-pillar__title"><?php echo esc_html( $fde_item[0] ); ?></h3>
						<?php if ( ! empty( $fde_item[1] ) ) : ?>
							<p class="svp-pillar__text"><?php echo esc_html( $fde_item[1] ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php if ( $fde_flow ) : ?>
	<section class="section svp-sec--ink lp-flow" id="flow" data-section="flow">
		<div class="section__inner">
			<?php fde_svp_head( 'Process', '伴走の流れ' ); ?>
			<?php fde_svp_steps( $fde_flow ); ?>
			<?php if ( '' !== $fde_term ) : ?>
				<p class="lp-flow__term"><span class="lp-flow__term-dot" aria-hidden="true"></span><?php echo esc_html( $fde_term ); ?></p>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
