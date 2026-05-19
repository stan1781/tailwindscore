<?php

declare(strict_types=1);

define( 'ABSPATH', __DIR__ );

function sanitize_key( string $value ): string {
	$value = strtolower( $value );

	return preg_replace( '/[^a-z0-9_\-]/', '', $value ) ?? '';
}

function __( string $text, string $domain = 'tailwindscore' ): string {
	unset( $domain );

	return $text;
}

function add_filter( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
	unset( $hook, $callback, $priority, $accepted_args );
}

function add_action( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
	unset( $hook, $callback, $priority, $accepted_args );
}

function get_theme_mod( string $setting, $default = false ) {
	unset( $setting );

	return $default;
}

function tailwindscore_content_surface_text( string $key, string $default = '' ): string {
	return '[surface:' . $key . '|' . $default . ']';
}

function tailwindscore_feedback_empty_state_copy( string $context ): array {
	return array(
		'eyebrow' => $context . '-eyebrow',
		'title'   => $context . '-title',
		'message' => $context . '-message',
	);
}

function wc_get_account_endpoint_url( string $endpoint ): string {
	return '/account/' . $endpoint;
}

require_once __DIR__ . '/../inc/woocommerce/account.php';
require_once __DIR__ . '/../inc/woocommerce/cart.php';
require_once __DIR__ . '/../inc/woocommerce/checkout.php';
require_once __DIR__ . '/../inc/woocommerce/search.php';

$checks = array(
	'account'  => tailwindscore_account_surface_text( 'account-message', 'Account default' ),
	'cart'     => tailwindscore_cart_copy_text( 'cart-drawer-loading-message', 'Cart default' ),
	'checkout' => tailwindscore_checkout_copy_text( 'checkout-loading-message', 'Checkout default' ),
	'search'   => tailwindscore_search_copy_text( 'search-predictive-empty-message', 'Search default' ),
);

$expected = array(
	'account'  => '[surface:account-message|Account default]',
	'cart'     => '[surface:cart-drawer-loading-message|Cart default]',
	'checkout' => '[surface:checkout-loading-message|Checkout default]',
	'search'   => '[surface:search-predictive-empty-message|Search default]',
);

foreach ( $expected as $context => $value ) {
	if ( $checks[ $context ] !== $value ) {
		fwrite( STDERR, "Surface adapter {$context} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

fwrite( STDOUT, "OK\n" );
