<?php
/**
 * WooCommerce account experience hooks.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

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
