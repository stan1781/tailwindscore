<?php
/**
 * Badge adapter — WC_Product → badge component `$args` arrays (no markup).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Build ordered badge props for a product (sale, featured, stock, new).
 *
 * @param WC_Product $product Product instance.
 * @return list<array<string, mixed>> List of badge component props.
 */
function tailwindscore_adapter_product_badges_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$badges = array();

	if ( $product->is_on_sale() ) {
		$badges[] = array(
			'label'   => __( 'Sale', 'tailwindscore' ),
			'variant' => 'sale',
			'size'    => 'sm',
		);
	}

	if ( $product->is_featured() ) {
		$badges[] = array(
			'label'   => __( 'Featured', 'tailwindscore' ),
			'variant' => 'success',
			'size'    => 'sm',
		);
	}

	if ( ! $product->is_in_stock() ) {
		$badges[] = array(
			'label'   => __( 'Out of stock', 'tailwindscore' ),
			'variant' => 'neutral',
			'size'    => 'sm',
		);
	} elseif ( $product->managing_stock() && $product->get_stock_quantity() !== null ) {
		$qty = $product->get_stock_quantity();
		if ( is_numeric( $qty ) && (int) $qty > 0 && (int) $qty <= (int) apply_filters( 'tailwindscore/adapter/badge/low_stock_threshold', 3, $product ) ) {
			/* translators: %d: stock quantity */
			$badges[] = array(
				'label'   => sprintf( __( 'Only %d left', 'tailwindscore' ), (int) $qty ),
				'variant' => 'neutral',
				'size'    => 'sm',
			);
		}
	}

	$new_days = (int) apply_filters( 'tailwindscore/adapter/badge/new_product_days', 30, $product );
	if ( $new_days > 0 ) {
		$created = $product->get_date_created();
		if ( $created && $created->getTimestamp() >= time() - ( $new_days * DAY_IN_SECONDS ) ) {
			$badges[] = array(
				'label'   => __( 'New', 'tailwindscore' ),
				'variant' => 'new',
				'size'    => 'sm',
			);
		}
	}

	/**
	 * Filter badge props list before rendering.
	 *
	 * @param list<array<string, mixed>> $badges  Badge component args.
	 * @param WC_Product                 $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/badges_props', $badges, $product );
}
