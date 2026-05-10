<?php
/**
 * Price adapter — WC_Product → price component `$args` (no WC markup decisions in components).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Map product pricing to `price` component props.
 *
 * @param WC_Product $product Product instance.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_price_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$props = array(
		'price_html' => $product->get_price_html(),
	);

	$suffix = '';
	if ( is_callable( array( $product, 'get_price_suffix' ) ) ) {
		$suffix = (string) $product->get_price_suffix();
	}

	if ( '' !== $suffix ) {
		$props['suffix_html'] = $suffix;
	}

	/**
	 * Unit price (e.g. per kg) — extend via meta / helpers later.
	 *
	 * @param string     $unit_html Empty default.
	 * @param WC_Product $product   Product.
	 */
	$unit_html = apply_filters( 'tailwindscore/adapter/price/unit_html', '', $product );
	if ( is_string( $unit_html ) && '' !== $unit_html ) {
		$props['unit_html'] = $unit_html;
	}

	/**
	 * Optional structured amounts when templates need raw comparison without HTML.
	 *
	 * @param bool $include_structured Whether to add sale_amount / regular_amount strings.
	 */
	if ( apply_filters( 'tailwindscore/adapter/price/include_structured_amounts', false, $product ) ) {
		$regular = $product->get_regular_price();
		$sale    = $product->get_sale_price();

		if ( is_numeric( $sale ) && is_numeric( $regular ) ) {
			$props['sale_amount']    = (string) wc_format_decimal( $sale, wc_get_price_decimals() );
			$props['regular_amount'] = (string) wc_format_decimal( $regular, wc_get_price_decimals() );
		}
	}

	/**
	 * Filter full price component props.
	 *
	 * @param array<string, mixed> $props   Args for `tailwindscore_component( 'price', … )`.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/price_props', $props, $product );
}
