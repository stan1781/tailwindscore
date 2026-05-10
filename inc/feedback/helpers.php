<?php
/**
 * Feedback template runtime helpers.
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
