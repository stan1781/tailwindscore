<?php
/**
 * Sanitizers for governed content surfaces.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Normalize plain-text surfaces.
 */
function tailwindscore_content_surface_sanitize_text( $value ): string {
	return is_scalar( $value ) ? sanitize_text_field( (string) $value ) : '';
}

/**
 * Normalize multi-line text surfaces.
 */
function tailwindscore_content_surface_sanitize_textarea( $value ): string {
	return is_scalar( $value ) ? sanitize_textarea_field( (string) $value ) : '';
}

/**
 * Normalize rich-text surfaces.
 */
function tailwindscore_content_surface_sanitize_rich_text( $value ): string {
	return is_scalar( $value ) ? trim( wp_kses_post( (string) $value ) ) : '';
}

/**
 * Normalize social link surface values.
 *
 * @param mixed $value Raw stored value.
 * @return array<int, array<string, string>>
 */
function tailwindscore_content_surface_sanitize_social_links( $value ): array {
	if ( ! is_array( $value ) ) {
		return array();
	}

	$allowed_platforms = array_keys( tailwindscore_icon_registry() );
	$items             = array();

	foreach ( $value as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$platform = sanitize_key( (string) ( $item['platform'] ?? '' ) );
		$url      = esc_url_raw( (string) ( $item['url'] ?? '' ) );
		$label    = sanitize_text_field( (string) ( $item['label'] ?? '' ) );

		if ( '' === $platform || '' === $url || ! in_array( $platform, $allowed_platforms, true ) ) {
			continue;
		}

		if ( '' === $label ) {
			$label = ucwords( str_replace( '-', ' ', $platform ) );
		}

		$items[] = array(
			'platform' => $platform,
			'url'      => $url,
			'label'    => $label,
		);
	}

	return $items;
}

/**
 * Check whether a sanitized surface value should fall back.
 *
 * @param mixed $value Sanitized value.
 */
function tailwindscore_content_surface_is_empty( $value ): bool {
	if ( is_array( $value ) ) {
		return array() === $value;
	}

	if ( is_string( $value ) ) {
		return '' === trim( $value );
	}

	return empty( $value );
}
