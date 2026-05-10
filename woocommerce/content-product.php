<?php
/**
 * Loop product — WC_Product → adapter → `product-card` component (no business logic here).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! isset( $product ) || ! is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
	return;
}

$props = tailwindscore_adapter_product_card_props( $product );

if ( empty( $props ) ) {
	return;
}

tailwindscore_component( 'product-card', $props );
