<?php
/**
 * Preset loader and SSR-safe token override pipeline.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

function tailwindscore_preset_active_key(): string {
	$key    = sanitize_key( (string) get_theme_mod( 'ts_preset_key', tailwindscore_preset_default_key() ) );
	$preset = tailwindscore_preset_definition( $key );

	return is_array( $preset ) ? $key : tailwindscore_preset_default_key();
}

/**
 * Resolve the active preset definition.
 *
 * @return array<string, mixed>
 */
function tailwindscore_preset_active_definition(): array {
	$key    = tailwindscore_preset_active_key();
	$preset = tailwindscore_preset_definition( $key );

	return is_array( $preset ) ? $preset : ( tailwindscore_preset_definition( tailwindscore_preset_default_key() ) ?? array() );
}

/**
 * Preset override groups allowed to emit CSS variables.
 *
 * @return string[]
 */
function tailwindscore_preset_override_groups(): array {
	return array(
		'token_overrides',
		'spacing_rhythm_overrides',
		'motion_intensity_overrides',
		'density_overrides',
		'shell_mood_overrides',
	);
}

/**
 * Flatten active preset CSS variable overrides.
 *
 * @return array<string, string>
 */
function tailwindscore_preset_active_css_variables(): array {
	$preset    = tailwindscore_preset_active_definition();
	$variables = array();

	foreach ( tailwindscore_preset_override_groups() as $group ) {
		$overrides = $preset[ $group ] ?? array();

		if ( ! is_array( $overrides ) ) {
			continue;
		}

		foreach ( $overrides as $property => $value ) {
			$property = trim( (string) $property );
			$value    = trim( (string) $value );

			if ( '' === $property || '' === $value || ! str_starts_with( $property, '--ts-' ) ) {
				continue;
			}

			$variables[ $property ] = $value;
		}
	}

	$variables['--ts-preset-name'] = tailwindscore_preset_active_key();

	return apply_filters( 'tailwindscore/presets/css_variables', $variables, $preset );
}

function tailwindscore_preset_inline_css(): string {
	$variables = tailwindscore_preset_active_css_variables();
	$key       = tailwindscore_preset_active_key();

	if ( 1 === count( $variables ) && isset( $variables['--ts-preset-name'] ) && tailwindscore_preset_default_key() === $key ) {
		return '';
	}

	$lines = array();

	foreach ( $variables as $property => $value ) {
		$lines[] = sprintf( '%s:%s', $property, $value );
	}

	if ( array() === $lines ) {
		return '';
	}

	return ':root{' . implode( ';', $lines ) . ';}';
}

function tailwindscore_presets_attach_inline_style( string $style_handle ): void {
	$css = tailwindscore_preset_inline_css();

	if ( '' === $css ) {
		return;
	}

	wp_add_inline_style( $style_handle, $css );
}

/**
 * @param string[] $classes Existing classes.
 * @return string[]
 */
function tailwindscore_preset_body_classes( array $classes ): array {
	$preset       = tailwindscore_preset_active_definition();
	$key          = tailwindscore_preset_active_key();
	$content_mood = isset( $preset['content_mood_overrides'] ) && is_array( $preset['content_mood_overrides'] ) ? $preset['content_mood_overrides'] : array();

	$classes[] = 'ts-preset';
	$classes[] = 'ts-preset-' . sanitize_html_class( $key );

	if ( isset( $content_mood['tone'] ) ) {
		$classes[] = 'ts-content-tone-' . sanitize_html_class( (string) $content_mood['tone'] );
	}

	if ( isset( $content_mood['emphasis'] ) ) {
		$classes[] = 'ts-content-emphasis-' . sanitize_html_class( (string) $content_mood['emphasis'] );
	}

	if ( isset( $content_mood['tone_intensity'] ) ) {
		$classes[] = 'ts-content-intensity-' . sanitize_html_class( (string) $content_mood['tone_intensity'] );
	}

	if ( isset( $content_mood['commerce_language_pacing'] ) ) {
		$classes[] = 'ts-content-pacing-' . sanitize_html_class( (string) $content_mood['commerce_language_pacing'] );
	}

	return array_values( array_unique( $classes ) );
}
add_filter( 'body_class', 'tailwindscore_preset_body_classes' );
