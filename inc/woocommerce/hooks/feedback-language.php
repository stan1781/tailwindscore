<?php
/**
 * Unified WooCommerce feedback language integration.
 *
 * Replaces default WooCommerce notice rendering with TailwindScore feedback surfaces.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Render WooCommerce notices inside the unified feedback region.
 *
 * @param string $context Commerce context key.
 */
function tailwindscore_render_woocommerce_feedback_notices( string $context = 'shop' ): void {
	if ( ! function_exists( 'woocommerce_output_all_notices' ) ) {
		return;
	}

	ob_start();
	woocommerce_output_all_notices();
	$html = trim( (string) ob_get_clean() );

	$labels = array(
		'shop'      => __( 'Catalog notices', 'tailwindscore' ),
		'single'    => __( 'Product notices', 'tailwindscore' ),
		'cart'      => __( 'Cart notices', 'tailwindscore' ),
		'checkout'  => __( 'Checkout notices', 'tailwindscore' ),
	);

	$dismiss = in_array( $context, array( 'shop', 'single' ), true ) ? '8000' : '0';

	tailwindscore_feedback_notice_region(
		array(
			'context'          => $context,
			'content_html'     => $html,
			'dismiss_after_ms' => $dismiss,
			'scope_label'      => $labels[ $context ] ?? __( 'Store notices', 'tailwindscore' ),
			'render_if_empty'  => true,
		)
	);
}

add_action(
	'woocommerce_init',
	static function (): void {
		remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

		add_action(
			'woocommerce_before_single_product',
			static function (): void {
				tailwindscore_render_woocommerce_feedback_notices( 'single' );
			},
			10
		);

		add_action(
			'woocommerce_before_shop_loop',
			static function (): void {
				tailwindscore_render_woocommerce_feedback_notices( 'shop' );
			},
			10
		);

		add_action(
			'woocommerce_before_cart',
			static function (): void {
				tailwindscore_render_woocommerce_feedback_notices( 'cart' );
			},
			10
		);

		add_action(
			'woocommerce_before_checkout_form',
			static function (): void {
				tailwindscore_render_woocommerce_feedback_notices( 'checkout' );
			},
			10
		);
	},
	40
);
