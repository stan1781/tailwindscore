<?php
/**
 * Gallery adapter — attachment ID lists. See `gallery/runtime-props.php` for SSR slide props.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Gallery data for TS modules / future gallery component (structure only).
 *
 * @param WC_Product $product Product instance.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_product_gallery_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array(
			'featured_attachment_id' => 0,
			'gallery_attachment_ids' => array(),
			'all_attachment_ids'      => array(),
		);
	}

	$featured = (int) $product->get_image_id();
	$gallery  = array_map( 'intval', $product->get_gallery_image_ids() );

	$all = array_values(
		array_unique(
			array_filter(
				array_merge(
					$featured ? array( $featured ) : array(),
					$gallery
				)
			)
		)
	);

	$props = array(
		'featured_attachment_id' => $featured,
		'gallery_attachment_ids' => $gallery,
		'all_attachment_ids'      => $all,
	);

	/**
	 * Filter gallery adapter payload (SSR-friendly IDs only).
	 *
	 * @param array<string, mixed> $props   Gallery structure.
	 * @param WC_Product             $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/gallery_props', $props, $product );
}
