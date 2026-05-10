<?php
/**
 * Single product summary — price adapter (delegates to global price props + PDP context).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Price block props for PDP summary — sale, variable range, suffix, tax via WC HTML.
 *
 * @param WC_Product $product Product.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_single_product_summary_price_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$props = tailwindscore_adapter_price_props( $product );

	/**
	 * Filter PDP summary price props (after base price adapter).
	 *
	 * @param array<string, mixed> $props   Price component args.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/single-product/summary_price_props', $props, $product );
}
