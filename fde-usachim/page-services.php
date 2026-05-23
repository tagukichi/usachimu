<?php
/**
 * Services page.
 *
 * @package fde-usachim
 */

get_header();

require FDE_USACHIM_DIR . '/template-parts/common/breadcrumb.php';

$fde_services = [
	[
		'no'      => '01',
		'title'   => 'AI 業務自動化支援',
		'lead'    => 'Dify / Claude を活用し、社内の繰り返し業務をワークフローに落とし込みます。',
		'for'     => [
			'問い合わせ・社内連携・資料作成など、手作業の多い業務を自動化したい',
			'AI ツールを試したが、自社にどう使えばよいか分からない',
		],
		'flow'    => [
			'現状業務のヒアリングと自動化候補の洗い出し',
			'PoC（1〜2週間）で「動くもの」を作って検証',
			'本実装・運用設計・社内へのハンドオフ',
		],
		'price'   => '要見積もり',
	],
	[
		'no'      => '02',
		'title'   => 'Web サイト・LP 制作',
		'lead'    => 'コーポレートサイト、採用サイト、LP、WordPress オリジナルテーマ開発まで対応します。',
		'for'     => [
			'既存サイトを技術的に整理してリニューアルしたい',
			'更新しやすい WordPress を、テーマレベルから自社最適に作りたい',
		],
		'flow'    => [
			'目的・ターゲットの整理と設計方針の合意',
			'デザイン / 実装 / コンテンツ流し込み',
			'公開後の改善サポート（オプション）',
		],
		'price'   => '30万円〜',
	],
	[
		'no'      => '03',
		'title'   => 'DX 顧問・継続伴走',
		'lead'    => '月額契約で、技術相談・改善実装・社内勉強会などを継続的に支援します。',
		'for'     => [
			'社内に技術が分かる人がおらず、外注先以上の関係が欲しい',
			'継続的に改善し続ける体制を、内製化に向けて整えていきたい',
		],
		'flow'    => [
			'初回ヒアリング後にスコープと月次稼働を合意',
			'隔週の定例 + Slack 等での日常的なやり取り',
			'四半期ごとに振り返りと優先順位の見直し',
		],
		'price'   => '月額契約（要相談）',
	],
	[
		'no'      => '04',
		'title'   => 'PoC・プロトタイプ開発',
		'lead'    => '「動くもの」を素早く作り、議論を前に進めるためのスポット開発です。',
		'for'     => [
			'新規事業や社内改善の仮説を、まず動くもので検証したい',
			'資料での検討だけで止まっているテーマを前に進めたい',
		],
		'flow'    => [
			'検証したい仮説と評価軸のすり合わせ',
			'数日〜2週間でプロトタイプを構築',
			'評価会と次アクションの提案',
		],
		'price'   => '短期スポット（要相談）',
	],
];
?>

<article class="page-services">
	<header class="page-head section">
		<div class="container container--narrow">
			<p class="page-head__eyebrow">Services</p>
			<h1 class="page-head__title">提供サービス</h1>
			<p class="page-head__lead">単発の制作から、継続的な伴走支援まで。お客様のフェーズに合わせて関わり方を選べます。</p>
		</div>
	</header>

	<section class="services section" aria-label="Services list">
		<div class="container container--narrow">
			<?php foreach ( $fde_services as $service ) : ?>
				<article class="service">
					<header class="service__head">
						<span class="service__no"><?php echo esc_html( $service['no'] ); ?></span>
						<h2 class="service__title"><?php echo esc_html( $service['title'] ); ?></h2>
						<p class="service__lead"><?php echo esc_html( $service['lead'] ); ?></p>
					</header>

					<div class="service__body">
						<div class="service__block">
							<h3>こんな方におすすめ</h3>
							<ul>
								<?php foreach ( $service['for'] as $item ) : ?>
									<li><?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="service__block">
							<h3>進め方</h3>
							<ol>
								<?php foreach ( $service['flow'] as $item ) : ?>
									<li><?php echo esc_html( $item ); ?></li>
								<?php endforeach; ?>
							</ol>
						</div>
						<div class="service__block">
							<h3>料金</h3>
							<p><?php echo esc_html( $service['price'] ); ?></p>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<?php require FDE_USACHIM_DIR . '/template-parts/common/cta-block.php'; ?>
</article>

<?php
get_footer();
