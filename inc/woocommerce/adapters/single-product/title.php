<?php
/**
 * Single product summary — title adapter (WC_Product → component props).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @param WC_Product $product Product.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_single_product_title_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$props = array(
		'title'       => $product->get_name(),
		'heading_tag' => 'h1',
		'product_id'  => $product->get_id(),
	);

	/**
	 * Filter title props for single product summary.
	 *
	 * @param array<string, mixed> $props   Component args.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/single-product/title_props', $props, $product );
}
