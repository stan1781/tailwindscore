<?php
/**
 * Cart surface hooks.
 *
 * This keeps the non-JS fallback on a product permalink after native WooCommerce
 * POST add-to-cart. The JS-enabled cart lifecycle should intercept before this path.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'template_redirect',
	static function (): void {
		if ( wp_doing_ajax() || is_admin() ) {
			return;
		}

		if ( 'POST' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		$product_id = isset( $_POST['add-to-cart'] ) ? absint( wp_unslash( (string) $_POST['add-to-cart'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $product_id < 1 ) {
			return;
		}

		if ( function_exists( 'wc_notice_count' ) && wc_notice_count( 'error' ) > 0 ) {
			return;
		}

		$permalink = get_permalink( $product_id );
		if ( ! is_string( $permalink ) || '' === $permalink ) {
			return;
		}

		wp_safe_redirect( $permalink, 303 );
		exit;
	},
	99
);
