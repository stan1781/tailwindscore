<?php
/**
 * Single product — layout flow hooks (priority placeholders for summary vs gallery).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Document native WC hook priorities — customize in features/, not templates.
 *
 * Gallery: woocommerce_before_single_product_summary (default ~20 product images).
 * Summary: woocommerce_single_product_summary (title, rating, price, cart…).
 */
add_action(
	'woocommerce_before_single_product',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/single/before_product' );
	},
	5
);

add_action(
	'woocommerce_after_single_product',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/single/after_product' );
	},
	5
);
