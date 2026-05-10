<?php
/**
 * Single Product Image — TailwindScore gallery runtime (Embla + PhotoSwipe bridge).
 *
 * @package TailwindScore
 * @version 10.5.0
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

if ( ! $product || ! $product instanceof WC_Product ) {
	return;
}

$props = tailwindscore_adapter_gallery_runtime_props( $product );

tailwindscore_component( 'gallery/gallery', $props );
