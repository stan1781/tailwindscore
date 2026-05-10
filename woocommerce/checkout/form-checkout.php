<?php
/**
 * Checkout form override.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	tailwindscore_checkout_part( 'checkout-empty' );

	return;
}

tailwindscore_checkout_part(
	'checkout-layout',
	array(
		'checkout' => $checkout,
	)
);

do_action( 'woocommerce_after_checkout_form', $checkout );
