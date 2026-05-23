<?php
/**
 * About page.
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

$fde_image   = fde_option( 'profile_image' );
$fde_name    = (string) fde_option( 'profile_name', get_bloginfo( 'name' ) );
$fde_tagline = (string) fde_option( 'profile_tagline', 'Forward Deployed Engineer' );
$fde_bio     = (string) fde_option( 'profile_bio' );
?>

<article class="page-about">
	<header class="page-head section">
		<div class="container container--narrow">
			<p class="page-head__eyebrow">About</p>
			<h1 class="page-head__title">自分と関わるすべての人を、<br>少しでも前に進める。</h1>
			<p class="page-head__lead">私が大切にしているのは、目の前の課題に対して「最短で、確かな手応えを返す」こと。Web の実装力と AI 活用を武器に、中小企業の現場に踏み込んで伴走しています。</p>
		</div>
	</header>

	<section class="profile section" aria-labelledby="profile-title">
		<div class="container container--narrow">
			<h2 id="profile-title" class="section-head__title">プロフィール</h2>
			<div class="profile__body">
				<?php if ( is_array( $fde_image ) && ! empty( $fde_image['url'] ) ) : ?>
					<figure class="profile__photo">
						<img src="<?php echo esc_url( $fde_image['url'] ); ?>"
						     alt="<?php echo esc_attr( $fde_image['alt'] ?? $fde_name ); ?>"
						     loading="lazy"
						     width="<?php echo esc_attr( $fde_image['width'] ?? '' ); ?>"
						     height="<?php echo esc_attr( $fde_image['height'] ?? '' ); ?>">
					</figure>
				<?php endif; ?>
				<div class="profile__text">
					<p class="profile__name"><?php echo esc_html( $fde_name ); ?></p>
					<p class="profile__tagline"><?php echo esc_html( $fde_tagline ); ?></p>
					<?php if ( $fde_bio ) : ?>
						<div class="profile__bio"><?php echo wp_kses_post( wpautop( $fde_bio ) ); ?></div>
					<?php else : ?>
						<p>公務員として地域の現場に立ち、その後 Web 制作の世界へ。独立後は WordPress を中心とした受託制作と、Claude / Dify を活用した業務自動化を組み合わせて、中小企業の DX に伴走しています。</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<section class="why-fde section" aria-labelledby="why-fde-title">
		<div class="container container--narrow">
			<h2 id="why-fde-title" class="section-head__title">なぜ FDE という働き方を選んだのか</h2>
			<div class="prose">
				<p>「言われたものを作るだけ」では、本当に必要なものは作れない——制作業を続ける中で、何度もそう感じてきました。</p>
				<p>Forward Deployed Engineer は、顧客の現場に深く入り込み、何を作るべきかから一緒に考えて、手を動かして検証していく働き方です。AI で開発スピードが劇的に変わったいま、この「現場と作り手の距離をゼロにする」スタイルが、中小企業の DX には一番効くと信じています。</p>
			</div>
		</div>
	</section>

	<section class="principles section" aria-labelledby="principles-title">
		<div class="container container--narrow">
			<h2 id="principles-title" class="section-head__title">大切にしていること</h2>
			<ol class="principles__list">
				<li>
					<h3>「動くもの」で議論する</h3>
					<p>言葉だけで詰めない。プロトタイプを早く出して、現場の感触で意思決定する。</p>
				</li>
				<li>
					<h3>できないことは、できないと言う</h3>
					<p>背伸びした提案より、確実に届けられるラインを誠実に共有する。</p>
				</li>
				<li>
					<h3>納品で終わらない</h3>
					<p>運用フェーズの改善まで含めて伴走することで、価値が初めて根づく。</p>
				</li>
			</ol>
		</div>
	</section>

	<section class="tech-stack section" aria-labelledby="tech-stack-title">
		<div class="container container--narrow">
			<h2 id="tech-stack-title" class="section-head__title">技術スタック</h2>
			<dl class="tech-stack__list">
				<div><dt>Languages</dt><dd>PHP / TypeScript / Python</dd></div>
				<div><dt>Frontend</dt><dd>HTML / CSS / JavaScript</dd></div>
				<div><dt>CMS</dt><dd>WordPress（オリジナルテーマ・プラグイン開発）</dd></div>
				<div><dt>AI</dt><dd>Claude / Claude Code / Dify</dd></div>
				<div><dt>Infra</dt><dd>Xserver / さくらインターネット / Cloudflare</dd></div>
			</dl>
		</div>
	</section>

	<?php require FDE_USACHIM_DIR . '/template-parts/common/cta-block.php'; ?>
</article>

<?php
get_footer();
