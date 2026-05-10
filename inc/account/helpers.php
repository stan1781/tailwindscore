<?php
/**
 * Account experience helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Render an account partial from `template-parts/account`.
 *
 * @param string               $name Partial name without `.php`.
 * @param array<string, mixed> $args Explicit template arguments.
 */
function tailwindscore_account_part( string $name, array $args = array() ): void {
	$name = preg_replace( '#[^a-zA-Z0-9\-/]#', '', $name );
	$name = trim( $name, '/' );

	if ( '' === $name || str_contains( $name, '..' ) ) {
		return;
	}

	get_template_part( 'template-parts/account/' . $name, null, $args );
}

/**
 * Current WooCommerce account endpoint.
 */
function tailwindscore_account_current_endpoint(): string {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return '';
	}

	if ( function_exists( 'is_wc_endpoint_url' ) ) {
		$candidates = array(
			'orders',
			'view-order',
			'downloads',
			'edit-address',
			'payment-methods',
			'add-payment-method',
			'edit-account',
			'lost-password',
			'customer-logout',
		);

		foreach ( $candidates as $endpoint ) {
			if ( is_wc_endpoint_url( $endpoint ) ) {
				return $endpoint;
			}
		}
	}

	return 'dashboard';
}

/**
 * Header copy for the current account surface.
 *
 * @return array{eyebrow:string,title:string,intro:string}
 */
function tailwindscore_account_surface_copy(): array {
	$endpoint = tailwindscore_account_current_endpoint();

	$copy = array(
		'dashboard'      => array(
			'eyebrow' => __( 'Customer account', 'tailwindscore' ),
			'title'   => __( 'Your account', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-dashboard-message' ),
		),
		'orders'         => array(
			'eyebrow' => __( 'Order history', 'tailwindscore' ),
			'title'   => __( 'Orders', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-orders-message' ),
		),
		'view-order'     => array(
			'eyebrow' => __( 'Order detail', 'tailwindscore' ),
			'title'   => __( 'Order overview', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-view-order-message' ),
		),
		'downloads'      => array(
			'eyebrow' => __( 'Downloads', 'tailwindscore' ),
			'title'   => __( 'Digital purchases', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-downloads-message' ),
		),
		'edit-address'   => array(
			'eyebrow' => __( 'Address book', 'tailwindscore' ),
			'title'   => __( 'Addresses', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-address-guidance-message', __( 'Manage billing and shipping destinations with spacious, touch-safe forms and restrained utility language.', 'tailwindscore' ) ),
		),
		'edit-account'   => array(
			'eyebrow' => __( 'Account details', 'tailwindscore' ),
			'title'   => __( 'Personal details', 'tailwindscore' ),
			'intro'   => __( 'Update your name, email, or password in a single uninterrupted form surface.', 'tailwindscore' ),
		),
		'lost-password'  => array(
			'eyebrow' => __( 'Account access', 'tailwindscore' ),
			'title'   => __( 'Reset password', 'tailwindscore' ),
			'intro'   => tailwindscore_content_surface_text( 'account-login-reassurance-message', __( 'Restore access with the same clear rhythm as the rest of the customer account experience.', 'tailwindscore' ) ),
		),
	);

	return $copy[ $endpoint ] ?? $copy['dashboard'];
}

/**
 * Shared empty-state language for account surfaces.
 *
 * @return array<string, string>
 */
function tailwindscore_account_empty_state( string $context ): array {
	return tailwindscore_feedback_empty_state_copy( $context );
}

/**
 * Governed account surface text.
 */
function tailwindscore_account_surface_text( string $key, string $default = '' ): string {
	return tailwindscore_content_surface_text( $key, $default );
}

/**
 * Governed account recovery copy.
 *
 * @return array<string, string>
 */
function tailwindscore_account_recovery_copy(): array {
	return array(
		'intro'   => tailwindscore_account_surface_text( 'account-recovery-message' ),
		'caption' => tailwindscore_account_surface_text( 'account-recovery-caption' ),
		'reset'   => tailwindscore_account_surface_text( 'account-reset-message' ),
		'support' => tailwindscore_account_surface_text( 'account-reset-support-message' ),
	);
}

/**
 * Governed dashboard card copy.
 *
 * @return array<int, array<string, string>>
 */
function tailwindscore_account_dashboard_cards(): array {
	return array(
		array(
			'title' => __( 'Orders', 'tailwindscore' ),
			'copy'  => tailwindscore_content_surface_text( 'account-orders-message', __( 'Review recent purchases, delivery progress, and order details.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'orders' ),
		),
		array(
			'title' => __( 'Addresses', 'tailwindscore' ),
			'copy'  => tailwindscore_content_surface_text( 'account-address-guidance-message', __( 'Keep billing and shipping destinations ready for a faster checkout.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'edit-address' ),
		),
		array(
			'title' => __( 'Account details', 'tailwindscore' ),
			'copy'  => tailwindscore_content_surface_text( 'account-message', __( 'Update name, email, and password in one simple account form.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'edit-account' ),
		),
	);
}
