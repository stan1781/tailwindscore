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

function esc_html__( string $text, string $domain = 'tailwindscore' ): string {
	unset( $domain );
	return $text;
}

function esc_html_x( string $text, string $context, string $domain = 'tailwindscore' ): string {
	unset( $context, $domain );
	return $text;
}

function apply_filters( string $hook, $value ) {
	return $value;
}

function add_filter( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
	unset( $hook, $callback, $priority, $accepted_args );
}

function get_option( string $name, $default = null ) {
	unset( $name );
	return $default;
}

function tailwindscore_content_surface_text( string $key, string $default = '' ): string {
	return '' !== $default ? $default : $key;
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

function wc_review_ratings_enabled(): bool {
	return false;
}

function wp_get_current_commenter(): array {
	return array();
}

class WC_Product {}

function wc_get_product( int $product_id ) {
	unset( $product_id );
	return new WC_Product();
}

function tailwindscore_adapter_product_rating_aggregate_props( WC_Product $product ): array {
	unset( $product );
	return array(
		'average_rating' => 0,
		'review_count'   => 0,
	);
}

require_once __DIR__ . '/../inc/checkout/helpers.php';
require_once __DIR__ . '/../inc/account/helpers.php';
require_once __DIR__ . '/../inc/search/helpers.php';
require_once __DIR__ . '/../inc/woocommerce/hooks/review-experience.php';

$checkout_copy = tailwindscore_checkout_surface_copy();
$search_copy   = tailwindscore_search_surface_copy();
$review_copy   = tailwindscore_review_surface_copy();

$GLOBALS['ts_test_endpoint'] = 'dashboard';

function is_account_page(): bool {
	return true;
}

function is_wc_endpoint_url( string $endpoint ): bool {
	return $endpoint === ( $GLOBALS['ts_test_endpoint'] ?? '' );
}

$GLOBALS['ts_test_endpoint'] = 'edit-account';
$account_copy                = tailwindscore_account_surface_copy();

$required = array(
	'checkout' => array( $checkout_copy, array( 'empty_cta_label', 'summary_heading' ) ),
	'account'  => array( $account_copy, array( 'secondary_action_label', 'empty_address_message' ) ),
	'search'   => array( $search_copy, array( 'default_title', 'search_placeholder' ) ),
	'reviews'  => array( $review_copy, array( 'review_title', 'form_intro', 'cookies_consent_label' ) ),
);

foreach ( $required as $family => $definition ) {
	$copy = $definition[0];
	$keys = $definition[1];

	foreach ( $keys as $key ) {
		if ( ! isset( $copy[ $key ] ) || '' === trim( (string) $copy[ $key ] ) ) {
			fwrite( STDERR, "Missing governed helper key {$key} for {$family}\n" );
			exit( 1 );
		}
	}
}

fwrite( STDOUT, "OK\n" );
