<?php
/**
 * Feature-owned feedback integration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Render a feedback partial from `template-parts/feedback`.
 *
 * @param string               $name Partial name without `.php`.
 * @param array<string, mixed> $args Explicit template arguments.
 */
function tailwindscore_feedback_part( string $name, array $args = array() ): void {
	$name = preg_replace( '#[^a-zA-Z0-9\-/]#', '', $name );
	$name = trim( $name, '/' );

	if ( '' === $name || str_contains( $name, '..' ) ) {
		return;
	}

	get_template_part( 'template-parts/feedback/' . $name, null, $args );
}

/**
 * Render a WooCommerce-compatible feedback region for server-side notices.
 *
 * @param array<string, mixed> $args Region arguments passed to `template-parts/feedback/notice.php`.
 */
function tailwindscore_feedback_notice_region( array $args = array() ): void {
	tailwindscore_feedback_part( 'notice', $args );
}

/**
 * Shared premium-commerce empty-state copy.
 *
 * @return array<string, string>
 */
function tailwindscore_feedback_empty_state_copy( string $context ): array {
	$defaults = array(
		'eyebrow' => '',
		'title'   => '',
		'message' => '',
	);
	$copy     = $defaults;

	foreach ( array_keys( $defaults ) as $slot ) {
		$key = sprintf( 'empty-state-%s-%s', sanitize_key( $context ), $slot );

		if ( function_exists( 'tailwindscore_content_surface_value' ) ) {
			$copy[ $slot ] = (string) tailwindscore_content_surface_value( $key );
		}
	}

	if ( '' === $copy['title'] && 'search-results' !== $context ) {
		return tailwindscore_feedback_empty_state_copy( 'search-results' );
	}

	return $copy;
}

/**
 * Render WooCommerce notices inside the unified feedback region.
 */
function tailwindscore_render_woocommerce_feedback_notices( string $context = 'shop' ): void {
	if ( ! function_exists( 'woocommerce_output_all_notices' ) ) {
		return;
	}

	ob_start();
	woocommerce_output_all_notices();
	$html = trim( (string) ob_get_clean() );

	$labels = array(
		'shop'     => __( 'Catalog notices', 'tailwindscore' ),
		'single'   => __( 'Product notices', 'tailwindscore' ),
		'cart'     => __( 'Cart notices', 'tailwindscore' ),
		'checkout' => __( 'Checkout notices', 'tailwindscore' ),
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
	'wp_footer',
	static function (): void {
		tailwindscore_feedback_part(
			'toast',
			array(
				'id' => 'ts-feedback-toast-root',
			)
		);
		tailwindscore_feedback_part(
			'notice',
			array(
				'id'               => 'ts-feedback-live-region-root',
				'context'          => 'live-region',
				'live_only'        => true,
				'aria_live'        => 'polite',
				'role'             => 'status',
				'render_if_empty'  => true,
				'dismiss_after_ms' => '0',
				'scope_label'      => __( 'Store updates', 'tailwindscore' ),
			)
		);
	},
	40
);

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
