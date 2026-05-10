<?php
/**
 * Replace default WooCommerce single-product summary blocks with TailwindScore components.
 *
 * Hook priorities match `woocommerce/includes/wc-template-hooks.php`.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Title block.
 */
function tailwindscore_render_single_product_summary_title(): void {
	if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	global $product;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$props = tailwindscore_adapter_single_product_title_props( $product );

	if ( empty( $props['title'] ) ) {
		return;
	}

	tailwindscore_component( 'product-summary/title', $props );
}

/**
 * Rating block (WC star markup via adapter).
 */
function tailwindscore_render_single_product_summary_rating(): void {
	if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	global $product;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$props = tailwindscore_adapter_single_product_rating_props( $product );

	tailwindscore_component( 'product-summary/rating', $props );
}

/**
 * Price block (variable range + tax/suffix via WC price_html).
 */
function tailwindscore_render_single_product_summary_price(): void {
	if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	global $product;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$props = tailwindscore_adapter_single_product_summary_price_props( $product );

	if ( empty( $props ) ) {
		return;
	}

	tailwindscore_component( 'product-summary/price', $props );
}

/**
 * Short description block.
 */
function tailwindscore_render_single_product_summary_excerpt(): void {
	if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_product' ) ) {
		return;
	}

	global $product;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		return;
	}

	$props = tailwindscore_adapter_single_product_excerpt_props( $product );

	tailwindscore_component( 'product-summary/excerpt', $props );
}

/**
 * Add-to-cart block wrapped in TailwindScore interaction host.
 */
function tailwindscore_render_single_product_summary_add_to_cart(): void {
	if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
		woocommerce_template_single_add_to_cart();
		return;
	}

	ob_start();
	woocommerce_template_single_add_to_cart();
	$html = (string) ob_get_clean();

	if ( '' === trim( $html ) ) {
		return;
	}

	tailwindscore_component(
		'commerce/add-to-cart-button',
		array(
			'inner_html' => $html,
		)
	);
}

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true ) ) {
			return;
		}

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_title', 5 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_price', 10 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_add_to_cart', 30 );
	},
	20
);
