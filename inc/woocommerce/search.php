<?php
/**
 * Feature-owned search integration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Render a search template part to string.
 *
 * @param string               $template Relative template path inside template-parts/search without .php.
 * @param array<string, mixed> $args     Template args.
 */
function tailwindscore_search_render( string $template, array $args = array() ): string {
	$template = trim( preg_replace( '#[^a-zA-Z0-9\-/]#', '', $template ), '/' );
	if ( '' === $template || str_contains( $template, '..' ) ) {
		return '';
	}

	$file = locate_template( 'template-parts/search/' . $template . '.php', false, false );
	if ( '' === $file ) {
		return '';
	}

	ob_start();
	require $file;
	return (string) ob_get_clean();
}

function tailwindscore_search_endpoint_url(): string {
	return rest_url( 'tailwindscore/v1/search' );
}

/**
 * @return list<array<string, string>>
 */
function tailwindscore_search_popular_terms(): array {
	$items = array(
		array(
			'label' => __( 'New arrivals', 'tailwindscore' ),
			'url'   => home_url( '/?s=new' ),
		),
		array(
			'label' => __( 'Gift edit', 'tailwindscore' ),
			'url'   => home_url( '/?s=gift' ),
		),
		array(
			'label' => __( 'Best sellers', 'tailwindscore' ),
			'url'   => home_url( '/?s=best' ),
		),
	);

	return apply_filters( 'tailwindscore/search/popular_terms', $items );
}

/**
 * @return list<array<string, string>>
 */
function tailwindscore_search_featured_categories(): array {
	$items = array();

	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'number'     => 4,
				'orderby'    => 'count',
				'order'      => 'DESC',
			)
		);

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$link    = get_term_link( $term );
				$items[] = array(
					'label' => $term->name,
					'url'   => $link instanceof WP_Error ? '' : (string) $link,
				);
			}
		}
	}

	return apply_filters( 'tailwindscore/search/featured_categories', array_values( array_filter( $items, static fn( array $item ): bool => '' !== ( $item['url'] ?? '' ) ) ) );
}

/**
 * @return array<string, string>
 */
function tailwindscore_search_copy_text( string $key, string $default = '' ): string {
	$defaults = array(
		'search-recent-searches-guidance-message' => 'Recent searches remain nearby so returning to a product path feels immediate and quiet.',
		'search-predictive-empty-message'         => 'Try a broader product name or continue through a collection path.',
	);

	$fallback = '' !== $default ? $default : ( $defaults[ $key ] ?? '' );

	return tailwindscore_content_surface_text( $key, $fallback );
}

/**
 * @return array<string, string>
 */
function tailwindscore_search_feedback_copy( string $context ): array {
	$states = array(
		'loading'     => array(
			'eyebrow' => __( 'Search', 'tailwindscore' ),
			'title'   => __( 'Looking through the collection', 'tailwindscore' ),
			'message' => __( 'A concise set of matching pieces and collection paths is being prepared.', 'tailwindscore' ),
		),
		'unavailable' => tailwindscore_feedback_empty_state_copy( 'search-unavailable' ),
		'empty'       => tailwindscore_feedback_empty_state_copy( 'search-results' ),
	);

	return $states[ $context ] ?? $states['empty'];
}

/**
 * @return array<string, string>
 */
function tailwindscore_search_surface_copy(): array {
	return array(
		'eyebrow'                  => __( 'Discover', 'tailwindscore' ),
		'title'                    => __( 'Search the collection', 'tailwindscore' ),
		'recent_searches_guidance' => tailwindscore_search_copy_text( 'search-recent-searches-guidance-message' ),
		'predictive_empty_message' => tailwindscore_search_copy_text( 'search-predictive-empty-message' ),
	);
}

/**
 * @return array{products:list<array<string, string>>,categories:list<array<string, string>>,suggestions:list<array<string, string>>,query:string}
 */
function tailwindscore_get_predictive_search_results( string $query ): array {
	$query = trim( wp_strip_all_tags( $query ) );

	$results = array(
		'query'       => $query,
		'products'    => array(),
		'categories'  => array(),
		'suggestions' => array(),
	);

	if ( '' === $query ) {
		return $results;
	}

	$post_type = post_type_exists( 'product' ) ? 'product' : 'post';
	$products  = get_posts(
		array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			's'                   => $query,
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
		)
	);

	foreach ( $products as $product_post ) {
		$image_url  = get_the_post_thumbnail_url( $product_post, 'woocommerce_thumbnail' );
		$price_html = '';
		$category   = '';

		if ( 'product' === $post_type && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $product_post );
			if ( $product instanceof WC_Product ) {
				$price_html = (string) $product->get_price_html();
			}
		}

		if ( taxonomy_exists( 'product_cat' ) ) {
			$terms = get_the_terms( $product_post, 'product_cat' );
			if ( is_array( $terms ) && ! empty( $terms ) ) {
				$primary_term = array_shift( $terms );
				if ( $primary_term instanceof WP_Term ) {
					$category = $primary_term->name;
				}
			}
		}

		$results['products'][] = array(
			'title'      => get_the_title( $product_post ),
			'url'        => (string) get_permalink( $product_post ),
			'image'      => $image_url ? (string) $image_url : '',
			'type'       => __( 'Product', 'tailwindscore' ),
			'category'   => $category,
			'price_html' => $price_html,
		);
	}

	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'number'     => 3,
				'name__like' => $query,
			)
		);

		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$link = get_term_link( $term );
				if ( $link instanceof WP_Error ) {
					continue;
				}

				$results['categories'][] = array(
					'title' => $term->name,
					'url'   => (string) $link,
					'type'  => __( 'Category', 'tailwindscore' ),
					'count' => (string) $term->count,
				);
			}
		}
	}

	$results['suggestions'] = array(
		array(
			'label' => sprintf( __( 'View all results for "%s"', 'tailwindscore' ), $query ),
			'url'   => home_url( '/?s=' . rawurlencode( $query ) . '&post_type=product' ),
		),
	);

	foreach ( $results['categories'] as $category ) {
		$results['suggestions'][] = array(
			'label' => sprintf( __( 'Explore the %s collection', 'tailwindscore' ), $category['title'] ),
			'url'   => $category['url'],
		);
	}

	return $results;
}

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'tailwindscore/v1',
			'/search',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'args'                => array(
					'q' => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'required'          => false,
					),
				),
				'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
					$query   = (string) $request->get_param( 'q' );
					$results = tailwindscore_get_predictive_search_results( $query );
					$html    = tailwindscore_search_render(
						'predictive-results',
						array(
							'query'   => $results['query'],
							'results' => $results,
						)
					);

					return new WP_REST_Response(
						array(
							'query'   => $results['query'],
							'results' => $results,
							'html'    => $html,
						)
					);
				},
			)
		);
	}
);
