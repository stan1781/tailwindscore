<?php
/**
 * Checkout template runtime helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Render a checkout partial from `template-parts/checkout`.
 *
 * @param string               $name Partial name without `.php`.
 * @param array<string, mixed> $args Explicit template arguments.
 */
function tailwindscore_checkout_part( string $name, array $args = array() ): void {
	$name = preg_replace( '#[^a-zA-Z0-9\-/]#', '', $name );
	$name = trim( $name, '/' );

	if ( '' === $name || str_contains( $name, '..' ) ) {
		return;
	}

	get_template_part( 'template-parts/checkout/' . $name, null, $args );
}

/**
 * Checkout governed copy map.
 *
 * @return array<string, mixed>
 */
function tailwindscore_checkout_surface_copy(): array {
	return array(
		'eyebrow'             => __( 'Secure purchase', 'tailwindscore' ),
		'title'               => __( 'Checkout', 'tailwindscore' ),
		'intro'               => tailwindscore_content_surface_text( 'checkout-reassurance-message' ),
		'support_items'       => array(
			__( 'Secure payment methods', 'tailwindscore' ),
			tailwindscore_content_surface_text( 'support-message' ),
		),
		'review_intro'        => tailwindscore_content_surface_text( 'checkout-payment-guidance-message' ),
		'summary_note'        => tailwindscore_content_surface_text( 'checkout-mobile-summary-message' ),
		'payment_intro'       => tailwindscore_content_surface_text( 'checkout-payment-guidance-message' ),
		'validation_title'    => tailwindscore_content_surface_text( 'checkout-validation-title' ),
		'validation_summary'  => tailwindscore_content_surface_text( 'checkout-validation-summary-message' ),
		'loading_message'     => tailwindscore_content_surface_text( 'checkout-loading-message' ),
	);
}
