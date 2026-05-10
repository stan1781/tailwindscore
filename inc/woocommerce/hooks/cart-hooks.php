<?php
/**
 * Cart / checkout flow hooks — fragments and notices bridge points.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'woocommerce_before_cart',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/cart/before_cart' );
	},
	5
);

add_action(
	'woocommerce_before_checkout_form',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/checkout/before_form' );
	},
	5
);
