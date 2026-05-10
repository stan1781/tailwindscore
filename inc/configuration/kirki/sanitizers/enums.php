<?php
/**
 * Sanitizers for governed Kirki controls.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize a value against an enumerated list of choices.
 *
 * @param mixed    $value   Raw setting value.
 * @param string[] $choices Allowed choices.
 * @param string   $default Default value when invalid.
 */
function tailwindscore_kirki_sanitize_enum( $value, array $choices, string $default = '' ): string {
	$value = sanitize_key( (string) $value );

	return in_array( $value, $choices, true ) ? $value : $default;
}

/**
 * Build an enum sanitizer callback.
 *
 * @param string[] $choices Allowed choice values.
 */
function tailwindscore_kirki_enum_sanitizer( array $choices, string $default = '' ): callable {
	return static function ( $value ) use ( $choices, $default ): string {
		return tailwindscore_kirki_sanitize_enum( $value, $choices, $default );
	};
}
