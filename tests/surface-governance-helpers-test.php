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

function add_action( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
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

function wc_review_ratings_required(): bool {
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

require_once __DIR__ . '/../inc/woocommerce/checkout.php';
require_once __DIR__ . '/../inc/woocommerce/account.php';
require_once __DIR__ . '/../inc/woocommerce/cart.php';
require_once __DIR__ . '/../inc/woocommerce/hooks/review-experience.php';
require_once __DIR__ . '/../inc/woocommerce/search.php';

$checkout_copy = tailwindscore_checkout_surface_copy();
$cart_copy     = function_exists( 'tailwindscore_cart_summary_copy' ) ? tailwindscore_cart_summary_copy() : array();
$cart_surface_copy = function_exists( 'tailwindscore_cart_surface_copy' ) ? tailwindscore_cart_surface_copy() : array();
$account_order_detail_copy = function_exists( 'tailwindscore_account_order_detail_copy' ) ? tailwindscore_account_order_detail_copy() : array();
$account_template_copy = function_exists( 'tailwindscore_account_template_copy' ) ? tailwindscore_account_template_copy() : array();
$reviews_copy  = function_exists( 'tailwindscore_review_surface_copy' ) ? tailwindscore_review_surface_copy() : array();
$search_copy   = tailwindscore_search_surface_copy();

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
	'checkout' => array( $checkout_copy, array( 'layout_title', 'empty_cta_label', 'summary_heading', 'summary_subtotal', 'payment_unavailable_message', 'payment_billing_required_message', 'payment_not_needed_message', 'noscript_update_message', 'update_totals_label' ) ),
	'cart'     => array( $cart_copy, array( 'subtotal_label', 'summary_note', 'checkout_label', 'view_cart_label' ) ),
	'cart-surface' => array( $cart_surface_copy, array( 'line_item_subtotal_label', 'validation_title', 'loading_message', 'update_error', 'item_updated', 'item_removed' ) ),
	'account-order-detail' => array( $account_order_detail_copy, array( 'items_heading', 'quantity_format', 'delivery_heading', 'shipping_method_label', 'no_shipping_method', 'shipping_address_label', 'no_shipping_address', 'payment_heading', 'payment_method_label', 'payment_method_pending', 'billing_address_label', 'no_billing_address' ) ),
	'account-templates' => array( $account_template_copy, array( 'edit_account_intro', 'login_remember_label', 'reset_new_password_label', 'reset_confirm_label', 'reset_submit_label', 'view_order_back_label', 'address_empty_message', 'order_card_meta_format' ) ),
	'reviews'  => array( $reviews_copy, array( 'title', 'intro', 'pagination_label', 'access_eyebrow', 'access_title', 'access_message', 'access_sign_in_label', 'form_title', 'form_title_reply_to', 'form_intro', 'form_submit_label', 'form_rating_label', 'form_rating_label_optional', 'form_rating_placeholder', 'form_rating_option_5', 'form_rating_option_4', 'form_rating_option_3', 'form_rating_option_2', 'form_rating_option_1', 'form_review_label', 'form_name_label', 'form_email_label', 'cookies_consent', 'verified_owner_label' ) ),
	'account'  => array( $account_copy, array( 'eyebrow', 'title', 'intro' ) ),
	'search'   => array( $search_copy, array( 'eyebrow', 'title', 'recent_searches_guidance', 'predictive_empty_message', 'default_state_title', 'suggested_searches_heading', 'browse_collections_heading', 'recent_searches_heading', 'overlay_placeholder' ) ),
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
