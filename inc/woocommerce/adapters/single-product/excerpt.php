<?php
/**
 * Single product summary — short description adapter.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Short description fragment — uses WC filters/formatting parity with core template.
 *
 * @param WC_Product $product Product.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_single_product_excerpt_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$raw = $product->get_short_description();

	/**
	 * Same filter as WC `single-product/short-description.php` input path.
	 *
	 * @param string     $short   Short description.
	 * @param WC_Product $product Product.
	 */
	$filtered = (string) apply_filters( 'woocommerce_short_description', $raw );

	$html = '';
	if ( function_exists( 'wc_format_content' ) ) {
		$html = wc_format_content( $filtered );
	} else {
		$html = wpautop( wp_kses_post( $filtered ) );
	}

	$props = array(
		'content_html' => $html,
		'has_content'    => '' !== trim( wp_strip_all_tags( $filtered ) ),
	);

	/**
	 * Filter excerpt props for single product summary.
	 *
	 * @param array<string, mixed> $props   Component args.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/single-product/excerpt_props', $props, $product );
}
