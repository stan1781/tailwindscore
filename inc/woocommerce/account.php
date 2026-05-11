<?php
/**
 * Feature-owned account integration.
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
 * @return array{eyebrow:string,title:string,intro:string}
 */
function tailwindscore_account_copy_text( string $key, string $default = '' ): string {
	$setting_id = 'ts_surface_' . str_replace( '-', '_', $key );
	$value      = get_theme_mod( $setting_id, null );

	if ( is_string( $value ) && '' !== trim( $value ) ) {
		return $value;
	}

	return $default;
}

/**
 * @return array{eyebrow:string,title:string,intro:string}
 */
function tailwindscore_account_surface_copy(): array {
	$endpoint = tailwindscore_account_current_endpoint();

	$copy = array(
		'dashboard'     => array(
			'eyebrow' => __( 'Customer account', 'tailwindscore' ),
			'title'   => __( 'Your account', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-dashboard-message', __( 'Orders, addresses, downloads, and account details arranged in one calm post-purchase space.', 'tailwindscore' ) ),
		),
		'orders'        => array(
			'eyebrow' => __( 'Order history', 'tailwindscore' ),
			'title'   => __( 'Orders', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-orders-message', __( 'Track each purchase, reopen details when needed, and keep your post-purchase history easy to scan.', 'tailwindscore' ) ),
		),
		'view-order'    => array(
			'eyebrow' => __( 'Order detail', 'tailwindscore' ),
			'title'   => __( 'Order overview', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-view-order-message', __( 'A clear summary of status, items, totals, and delivery information without dashboard clutter.', 'tailwindscore' ) ),
		),
		'downloads'     => array(
			'eyebrow' => __( 'Downloads', 'tailwindscore' ),
			'title'   => __( 'Digital purchases', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-downloads-message', __( 'Keep downloadable products close at hand, with remaining access and expiry details presented quietly.', 'tailwindscore' ) ),
		),
		'edit-address'  => array(
			'eyebrow' => __( 'Address book', 'tailwindscore' ),
			'title'   => __( 'Addresses', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-address-guidance-message', __( 'Manage billing and shipping destinations with spacious, touch-safe forms and restrained utility language.', 'tailwindscore' ) ),
		),
		'edit-account'  => array(
			'eyebrow' => __( 'Account details', 'tailwindscore' ),
			'title'   => __( 'Personal details', 'tailwindscore' ),
			'intro'   => __( 'Update your name, email, or password in a single uninterrupted form surface.', 'tailwindscore' ),
		),
		'lost-password' => array(
			'eyebrow' => __( 'Account access', 'tailwindscore' ),
			'title'   => __( 'Reset password', 'tailwindscore' ),
			'intro'   => tailwindscore_account_copy_text( 'account-login-reassurance-message', __( 'Restore access with the same clear rhythm as the rest of the customer account experience.', 'tailwindscore' ) ),
		),
	);

	return $copy[ $endpoint ] ?? $copy['dashboard'];
}

/**
 * @return array<string, string>
 */
function tailwindscore_account_empty_state( string $context ): array {
	return tailwindscore_feedback_empty_state_copy( $context );
}

function tailwindscore_account_surface_text( string $key, string $default = '' ): string {
	return tailwindscore_account_copy_text( $key, $default );
}

/**
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
 * @return array<int, array<string, string>>
 */
function tailwindscore_account_dashboard_cards(): array {
	return array(
		array(
			'title' => __( 'Orders', 'tailwindscore' ),
			'copy'  => tailwindscore_account_copy_text( 'account-orders-message', __( 'Review recent purchases, delivery progress, and order details.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'orders' ),
		),
		array(
			'title' => __( 'Addresses', 'tailwindscore' ),
			'copy'  => tailwindscore_account_copy_text( 'account-address-guidance-message', __( 'Keep billing and shipping destinations ready for a faster checkout.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'edit-address' ),
		),
		array(
			'title' => __( 'Account details', 'tailwindscore' ),
			'copy'  => tailwindscore_account_copy_text( 'account-message', __( 'Update name, email, and password in one simple account form.', 'tailwindscore' ) ),
			'url'   => wc_get_account_endpoint_url( 'edit-account' ),
		),
	);
}

add_filter(
	'woocommerce_account_menu_items',
	static function ( array $items ): array {
		$labels = array(
			'dashboard'       => __( 'Overview', 'tailwindscore' ),
			'orders'          => __( 'Orders', 'tailwindscore' ),
			'downloads'       => __( 'Downloads', 'tailwindscore' ),
			'edit-address'    => __( 'Addresses', 'tailwindscore' ),
			'edit-account'    => __( 'Account details', 'tailwindscore' ),
			'customer-logout' => __( 'Sign out', 'tailwindscore' ),
		);

		foreach ( $labels as $key => $label ) {
			if ( isset( $items[ $key ] ) ) {
				$items[ $key ] = $label;
			}
		}

		return $items;
	}
);

add_filter(
	'woocommerce_form_field_args',
	static function ( array $args, string $key ): array {
		if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ( function_exists( 'is_checkout' ) && is_checkout() ) ) {
			return $args;
		}

		$args['class']       = is_array( $args['class'] ?? null ) ? $args['class'] : array();
		$args['input_class'] = is_array( $args['input_class'] ?? null ) ? $args['input_class'] : array();
		$args['label_class'] = is_array( $args['label_class'] ?? null ) ? $args['label_class'] : array();

		$args['class'][] = 'ts-account-field';

		if ( ! in_array( (string) ( $args['type'] ?? '' ), array( 'checkbox', 'radio', 'hidden' ), true ) ) {
			$args['class'][]       = 'ts-field';
			$args['label_class'][] = 'ts-label';
		}

		if ( 'select' === ( $args['type'] ?? '' ) ) {
			$args['input_class'][] = 'ts-select';
		} elseif ( 'textarea' === ( $args['type'] ?? '' ) ) {
			$args['input_class'][] = 'ts-textarea';
		} elseif ( ! in_array( (string) ( $args['type'] ?? '' ), array( 'checkbox', 'radio', 'hidden' ), true ) ) {
			$args['input_class'][] = 'ts-input';
		}

		$args['custom_attributes']                        = is_array( $args['custom_attributes'] ?? null ) ? $args['custom_attributes'] : array();
		$args['custom_attributes']['data-account-field'] = $key;

		return $args;
	},
	10,
	2
);
