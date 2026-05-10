<?php
/**
 * Checkout payment override.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}

tailwindscore_checkout_part(
	'checkout-payment',
	array(
		'available_gateways' => $available_gateways,
		'order_button_text'  => $order_button_text,
	)
);

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
