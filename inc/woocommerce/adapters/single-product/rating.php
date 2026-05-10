<?php
/**
 * Single product summary — rating adapter.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @param WC_Product $product Product.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_single_product_rating_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$average      = (float) $product->get_average_rating();
	$review_count = (int) $product->get_review_count();

	$rating_html = '';
	if ( function_exists( 'wc_get_rating_html' ) ) {
		$rating_html = wc_get_rating_html( $average, $review_count );
	}

	$props = array(
		'rating_html'   => $rating_html,
		'average'       => $average,
		'review_count'  => $review_count,
		'show_if_empty' => (bool) apply_filters( 'tailwindscore/adapter/single-product/rating/show_if_empty', false, $product ),
	);

	/**
	 * Filter rating props for single product summary.
	 *
	 * @param array<string, mixed> $props   Component args.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/single-product/rating_props', $props, $product );
}
