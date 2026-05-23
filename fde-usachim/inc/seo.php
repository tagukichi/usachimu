<?php
/**
 * SEO / OGP / JSON-LD / GA4 output.
 *
 * @package fde-usachim
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve the description for the current view.
 */
function fde_seo_description(): string {
	$default = (string) fde_option( 'site_description', get_bloginfo( 'description' ) );

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post && ! empty( $post->post_excerpt ) ) {
			return wp_strip_all_tags( $post->post_excerpt );
		}
		if ( $post ) {
			$content = wp_strip_all_tags( strip_shortcodes( (string) $post->post_content ) );
			$content = preg_replace( '/\s+/u', ' ', $content );
			if ( $content ) {
				return mb_substr( trim( $content ), 0, 120 );
			}
		}
	}

	if ( is_tax( 'work_category' ) ) {
		$term = get_queried_object();
		if ( $term && ! empty( $term->description ) ) {
			return wp_strip_all_tags( $term->description );
		}
		if ( $term ) {
			return sprintf( '「%s」カテゴリーの実績一覧。', $term->name );
		}
	}

	if ( is_post_type_archive( 'works' ) ) {
		return '中小企業の DX 伴走、AI 業務自動化、Web 制作などの実績一覧。';
	}

	return $default;
}

/**
 * Resolve OGP image URL.
 */
function fde_seo_og_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = (string) get_the_post_thumbnail_url( get_queried_object_id(), 'work-hero' );
		if ( $url ) {
			return $url;
		}
	}

	$default = fde_option( 'default_og_image' );
	if ( is_string( $default ) && $default ) {
		return $default;
	}

	return '';
}

/**
 * Resolve OGP type for the current view.
 */
function fde_seo_og_type(): string {
	if ( is_singular() ) {
		return 'article';
	}
	return 'website';
}

/**
 * Output meta description, canonical, OGP and Twitter Card tags.
 */
add_action(
	'wp_head',
	static function () {
		$description = fde_seo_description();
		$title       = wp_get_document_title();
		$canonical   = is_singular() ? get_permalink() : ( is_post_type_archive( 'works' ) ? get_post_type_archive_link( 'works' ) : home_url( add_query_arg( null, null ) ) );
		$og_image    = fde_seo_og_image();
		$og_type     = fde_seo_og_type();
		$site_name   = get_bloginfo( 'name' );
		$locale      = get_locale();

		if ( $description ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		}
		if ( $canonical ) {
			printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
		}

		// noindex for search and 404.
		if ( is_search() || is_404() ) {
			echo '<meta name="robots" content="noindex,follow">' . "\n";
		}

		// Open Graph
		printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		if ( $description ) {
			printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
		}
		if ( $canonical ) {
			printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $canonical ) );
		}
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site_name ) );
		printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( $locale ) );
		if ( $og_image ) {
			printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $og_image ) );
		}

		// Twitter Card
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
		if ( $description ) {
			printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
		}
		if ( $og_image ) {
			printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $og_image ) );
		}
	},
	2
);

/**
 * Emit JSON-LD structured data.
 */
add_action(
	'wp_head',
	static function () {
		$site_name   = get_bloginfo( 'name' );
		$home        = home_url( '/' );
		$description = (string) fde_option( 'site_description', get_bloginfo( 'description' ) );

		$graph = [];

		$graph[] = [
			'@type'       => 'WebSite',
			'@id'         => $home . '#website',
			'url'         => $home,
			'name'        => $site_name,
			'description' => $description,
			'inLanguage'  => str_replace( '_', '-', get_locale() ),
		];

		$org = [
			'@type' => 'Organization',
			'@id'   => $home . '#organization',
			'name'  => $site_name,
			'url'   => $home,
		];

		$sns_x  = (string) fde_option( 'sns_x_url' );
		$sns_fb = (string) fde_option( 'sns_facebook_url' );
		$sameas = array_values( array_filter( [ $sns_x, $sns_fb ] ) );
		if ( $sameas ) {
			$org['sameAs'] = $sameas;
		}
		$logo = fde_option( 'default_og_image' );
		if ( is_string( $logo ) && $logo ) {
			$org['logo'] = $logo;
		}
		$graph[] = $org;

		if ( is_singular( 'works' ) ) {
			$post_id   = get_queried_object_id();
			$image_url = (string) get_the_post_thumbnail_url( $post_id, 'work-hero' );
			$work = [
				'@type'         => 'CreativeWork',
				'@id'           => get_permalink( $post_id ) . '#creativework',
				'name'          => get_the_title( $post_id ),
				'url'           => get_permalink( $post_id ),
				'datePublished' => get_the_date( DATE_W3C, $post_id ),
				'dateModified'  => get_the_modified_date( DATE_W3C, $post_id ),
				'author'        => [ '@id' => $home . '#organization' ],
				'publisher'     => [ '@id' => $home . '#organization' ],
			];
			if ( $image_url ) {
				$work['image'] = $image_url;
			}
			if ( function_exists( 'get_field' ) ) {
				$industry = (string) get_field( 'client_industry', $post_id );
				if ( $industry ) {
					$work['about'] = $industry;
				}
			}
			$graph[] = $work;
		}

		$payload = [
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		];

		echo "<script type=\"application/ld+json\">\n";
		echo wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
		echo "\n</script>\n";
	},
	3
);

/**
 * Output GA4 (gtag.js) when a measurement ID is configured.
 */
add_action(
	'wp_head',
	static function () {
		if ( is_admin() || is_user_logged_in() ) {
			return;
		}
		$id = (string) fde_option( 'ga4_id' );
		if ( ! $id || ! preg_match( '/^G-[A-Z0-9]+$/i', $id ) ) {
			return;
		}
		$id = esc_js( $id );
		?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $id; ?>"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo $id; ?>');
</script>
		<?php
	},
	1
);
