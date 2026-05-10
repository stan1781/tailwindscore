<?php
/**
 * Variable product experience — SSR hosts + runtime root wrapper (TS `tailwindscore-variation-runtime`).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Wrap variable add-to-cart form for `data-ts-module` host (sibling structure: div > form).
 */
function tailwindscore_variation_runtime_open_wrap(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	global $product;

	if ( ! is_a( $product, 'WC_Product', false ) || ! $product->is_type( 'variable' ) ) {
		return;
	}

	echo '<div class="ts-variation-runtime" data-ts-module="tailwindscore-variation-runtime">';
}

/**
 * Close runtime wrapper after variable form.
 */
function tailwindscore_variation_runtime_close_wrap(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	global $product;

	if ( ! is_a( $product, 'WC_Product', false ) || ! $product->is_type( 'variable' ) ) {
		return;
	}

	echo '</div>';
}

/**
 * Optional SSR slots after attribute table (progressive enhancement).
 */
function tailwindscore_variation_experience_after_variations_table(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	if ( ! apply_filters( 'tailwindscore/variation/experience-ssr-slots', true ) ) {
		return;
	}

	tailwindscore_component( 'variations/variation-price-state', array() );
	tailwindscore_component( 'variations/variation-stock', array() );
}

add_action( 'woocommerce_before_add_to_cart_form', 'tailwindscore_variation_runtime_open_wrap', 0 );
add_action( 'woocommerce_after_add_to_cart_form', 'tailwindscore_variation_runtime_close_wrap', 99999 );
add_action( 'woocommerce_after_variations_table', 'tailwindscore_variation_experience_after_variations_table', 4 );
