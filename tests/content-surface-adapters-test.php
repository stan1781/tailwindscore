<?php

declare(strict_types=1);

define( 'ABSPATH', __DIR__ );

$GLOBALS['ts_test_actions']        = array();
$GLOBALS['ts_test_inline_scripts'] = array();

function sanitize_key( string $value ): string {
	$value = strtolower( $value );

	return preg_replace( '/[^a-z0-9_\-]/', '', $value ) ?? '';
}

function __( string $text, string $domain = 'tailwindscore' ): string {
	unset( $domain );

	return $text;
}

function esc_html( string $text ): string {
	return $text;
}

function esc_attr( string $text ): string {
	return $text;
}

function add_filter( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
	unset( $hook, $callback, $priority, $accepted_args );
}

function add_action( string $hook, $callback, int $priority = 10, int $accepted_args = 1 ): void {
	$GLOBALS['ts_test_actions'][ $hook ][] = array(
		'callback'      => $callback,
		'priority'      => $priority,
		'accepted_args' => $accepted_args,
	);
}

function get_theme_mod( string $setting, $default = false ) {
	unset( $setting );

	return $default;
}

function get_option( string $name, $default = null ) {
	unset( $name );

	return $default;
}

function wp_add_inline_script( string $handle, string $data, string $position = 'after' ): bool {
	$GLOBALS['ts_test_inline_scripts'][] = array(
		'handle'   => $handle,
		'data'     => $data,
		'position' => $position,
	);

	return true;
}

function wp_json_encode( $value ): string {
	return (string) json_encode( $value );
}

function wp_get_current_commenter(): array {
	return array();
}

function wc_review_ratings_enabled(): bool {
	return false;
}

function wc_review_ratings_required(): bool {
	return false;
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
require_once __DIR__ . '/../inc/woocommerce/hooks/review-experience.php';
require_once __DIR__ . '/../inc/woocommerce/search.php';

$cart_summary_copy        = function_exists( 'tailwindscore_cart_summary_copy' ) ? tailwindscore_cart_summary_copy() : array();
$cart_surface_copy        = function_exists( 'tailwindscore_cart_surface_copy' ) ? tailwindscore_cart_surface_copy() : array();
$checkout_copy            = tailwindscore_checkout_surface_copy();
$account_order_detail_copy = function_exists( 'tailwindscore_account_order_detail_copy' ) ? tailwindscore_account_order_detail_copy() : array();
$account_template_copy    = function_exists( 'tailwindscore_account_template_copy' ) ? tailwindscore_account_template_copy() : array();
$reviews_copy             = function_exists( 'tailwindscore_review_surface_copy' ) ? tailwindscore_review_surface_copy() : array();
$search_copy              = tailwindscore_search_surface_copy();
$search_loading_copy      = tailwindscore_search_feedback_copy( 'loading' );

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

$expected_cart_summary = array(
	'subtotal_label' => '[surface:cart-summary-subtotal-label|Subtotal]',
	'summary_note'   => '[surface:cart-summary-note|Shipping and taxes calculated at checkout.]',
	'checkout_label' => '[surface:cart-summary-checkout-label|Checkout]',
	'view_cart_label' => '[surface:cart-summary-view-cart-label|View cart]',
);

foreach ( $expected_cart_summary as $key => $value ) {
	if ( ! isset( $cart_summary_copy[ $key ] ) || $cart_summary_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Cart summary adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$expected_cart_surface = array(
	'line_item_subtotal_label' => '[surface:cart-line-item-subtotal-label|Subtotal]',
	'validation_title'         => '[surface:add-to-cart-validation-title|Please review this selection]',
);

foreach ( $expected_cart_surface as $key => $value ) {
	if ( ! isset( $cart_surface_copy[ $key ] ) || $cart_surface_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Cart surface adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$wp_enqueue_actions = $GLOBALS['ts_test_actions']['wp_enqueue_scripts'] ?? array();
foreach ( $wp_enqueue_actions as $action ) {
	if ( isset( $action['callback'] ) && is_callable( $action['callback'] ) ) {
		$action['callback']();
	}
}

$expected_bridge = 'document.documentElement.dataset.feedbackAddToCartValidationTitle="[surface:add-to-cart-validation-title|Please review this selection]";';
$matched_bridge  = false;

foreach ( $GLOBALS['ts_test_inline_scripts'] as $inline_script ) {
	if (
		( $inline_script['handle'] ?? '' ) === 'tailwindscore-config'
		&& ( $inline_script['position'] ?? '' ) === 'after'
		&& ( $inline_script['data'] ?? '' ) === $expected_bridge
	) {
		$matched_bridge = true;
		break;
	}
}

if ( ! $matched_bridge ) {
	fwrite( STDERR, "Add-to-cart validation title bridge was not published through the global config handle\n" );
	exit( 1 );
}

$expected_checkout_fields = array(
	'layout_title'                      => '[surface:checkout-layout-title|Checkout]',
	'summary_subtotal'                  => '[surface:checkout-summary-subtotal-label|Subtotal]',
	'payment_unavailable_message'      => '[surface:checkout-payment-unavailable-message|No payment methods are available for this order. Please review your details or contact us for help.]',
	'payment_billing_required_message' => '[surface:checkout-payment-billing-required-message|Enter your billing details to view available payment methods.]',
	'payment_not_needed_message'       => '[surface:checkout-payment-not-needed-message|No payment is needed for this order.]',
	'noscript_update_message'          => '[surface:checkout-noscript-update-message|Because your browser does not support JavaScript, totals may update only after you confirm with the %s button.]',
	'update_totals_label'              => '[surface:checkout-update-totals-label|Update totals]',
);

foreach ( $expected_checkout_fields as $key => $value ) {
	if ( ! isset( $checkout_copy[ $key ] ) || $checkout_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Checkout adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$expected_account_order_detail = array(
	'shipping_method_label' => '[surface:account-order-detail-shipping-method-label|Shipping method]',
	'no_shipping_method'    => '[surface:account-order-detail-no-shipping-method|No shipping required]',
	'shipping_address_label' => '[surface:account-order-detail-shipping-address-label|Shipping address]',
	'no_shipping_address'   => '[surface:account-order-detail-no-shipping-address|No shipping address provided.]',
	'payment_method_pending' => '[surface:account-order-detail-payment-method-pending|To be confirmed]',
);

foreach ( $expected_account_order_detail as $key => $value ) {
	if ( ! isset( $account_order_detail_copy[ $key ] ) || $account_order_detail_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Account order detail adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$expected_account_templates = array(
	'edit_account_intro'       => '[surface:account-edit-account-intro|Keep your customer profile current without leaving the post-purchase flow.]',
	'login_remember_label'     => '[surface:account-login-remember-label|Keep me signed in]',
	'reset_new_password_label' => '[surface:account-reset-new-password-label|New password]',
	'reset_confirm_label'      => '[surface:account-reset-confirm-password-label|Confirm password]',
	'reset_submit_label'       => '[surface:account-reset-submit-label|Save new password]',
	'view_order_back_label'    => '[surface:account-view-order-back-label|Back to orders]',
	'address_empty_message'    => '[surface:account-address-empty-message|No address saved yet.]',
	'order_card_meta_format'   => '[surface:account-order-card-meta-format|%1$s · %2$s · %3$s]',
);

foreach ( $expected_account_templates as $key => $value ) {
	if ( ! isset( $account_template_copy[ $key ] ) || $account_template_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Account template adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$default_order_card_meta_format = '%1$s · %2$s · %3$s';
$invalid_account_order_card_meta_formats = array(
	'',
	'%1$s · %2$s',
	'%1$s · %2$s · %2$s',
	'%1$s · %2$s · %4$s',
	'%1$s · %2$s · %3$s %s',
	'100% complete',
);

foreach ( $invalid_account_order_card_meta_formats as $format ) {
	if ( tailwindscore_account_normalize_order_card_meta_format( $format ) !== $default_order_card_meta_format ) {
		fwrite( STDERR, "Account order card meta format did not fall back for invalid input: {$format}\n" );
		exit( 1 );
	}
}

$valid_account_order_card_meta_format = '%3$s / %1$s / %2$s';

if ( tailwindscore_account_normalize_order_card_meta_format( $valid_account_order_card_meta_format ) !== $valid_account_order_card_meta_format ) {
	fwrite( STDERR, "Account order card meta format rejected a valid placeholder permutation\n" );
	exit( 1 );
}

$expected_reviews = array(
	'title'                => '[surface:reviews-title|Customer reviews]',
	'intro'                => '[surface:reviews-intro|Measured notes from customers, arranged with the same quiet hierarchy as the rest of the product story.]',
	'pagination_label'     => '[surface:reviews-pagination-label|Reviews pagination]',
	'access_title'         => '[surface:reviews-access-title|Purchase required to review]',
	'access_message'       => '[surface:reviews-access-message|Only customers who have purchased this item can leave a review, which keeps the conversation grounded in ownership.]',
	'access_sign_in_label' => '[surface:reviews-access-sign-in-label|Sign in]',
	'form_title'           => '[surface:reviews-form-title|Write a review]',
	'form_submit_label'    => '[surface:reviews-form-submit-label|Submit review]',
	'form_intro'           => '[surface:reviews-form-intro|Share a concise note on fit, feel, quality, or everyday use.]',
	'cookies_consent'      => '[surface:reviews-form-cookies-consent|Save my name, email, and website in this browser for the next time I comment.]',
);

foreach ( $expected_reviews as $key => $value ) {
	if ( ! isset( $reviews_copy[ $key ] ) || $reviews_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Reviews adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$expected_search = array(
	'eyebrow'                  => '[surface:search-eyebrow|Discover]',
	'title'                    => '[surface:search-title|Search the collection]',
	'recent_searches_guidance' => '[surface:search-recent-searches-guidance-message|Recent searches remain nearby so returning to a product path feels immediate and quiet.]',
	'predictive_empty_message' => '[surface:search-predictive-empty-message|Try a broader product name or continue through a collection path.]',
	'default_state_title'      => '[surface:search-default-state-title|Begin with a piece, a material, or a collection]',
	'suggested_searches_heading' => '[surface:search-suggested-searches-heading|Suggested searches]',
	'browse_collections_heading' => '[surface:search-browse-collections-heading|Browse collections]',
	'recent_searches_heading'  => '[surface:search-recent-searches-heading|Recent searches]',
	'overlay_placeholder'      => '[surface:search-overlay-placeholder|Search products, categories, stories]',
);

foreach ( $expected_search as $key => $value ) {
	if ( ! isset( $search_copy[ $key ] ) || $search_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Search adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$expected_search_loading = array(
	'eyebrow' => '[surface:search-loading-eyebrow|Search]',
	'title'   => '[surface:search-loading-title|Looking through the collection]',
	'message' => '[surface:search-loading-message|A concise set of matching pieces and collection paths is being prepared.]',
);

foreach ( $expected_search_loading as $key => $value ) {
	if ( ! isset( $search_loading_copy[ $key ] ) || $search_loading_copy[ $key ] !== $value ) {
		fwrite( STDERR, "Search loading adapter {$key} did not delegate to the shared resolver\n" );
		exit( 1 );
	}
}

$review_form_args = tailwindscore_review_form_args();

if ( ( $review_form_args['title_reply'] ?? '' ) !== '[surface:reviews-form-title|Write a review]' ) {
	fwrite( STDERR, "Review form title did not delegate to the shared resolver\n" );
	exit( 1 );
}

if ( ( $review_form_args['label_submit'] ?? '' ) !== '[surface:reviews-form-submit-label|Submit review]' ) {
	fwrite( STDERR, "Review form submit label did not delegate to the shared resolver\n" );
	exit( 1 );
}

if ( ! str_contains( (string) ( $review_form_args['comment_notes_before'] ?? '' ), '[surface:reviews-form-intro|Share a concise note on fit, feel, quality, or everyday use.]' ) ) {
	fwrite( STDERR, "Review form intro did not delegate to the shared resolver\n" );
	exit( 1 );
}

fwrite( STDOUT, "OK\n" );
