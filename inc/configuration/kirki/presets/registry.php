<?php
/**
 * Preset control metadata helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, string>
 */
function tailwindscore_kirki_preset_choices(): array {
	$choices = array();

	foreach ( tailwindscore_preset_registry() as $preset_key => $preset ) {
		$choices[ $preset_key ] = isset( $preset['label'] ) ? (string) $preset['label'] : $preset_key;
	}

	return $choices;
}

/**
 * Build an SSR-safe preview summary for the preset selector.
 */
function tailwindscore_kirki_preset_preview_description(): string {
	$lines = array();

	foreach ( tailwindscore_preset_registry() as $preset_key => $preset ) {
		$label       = isset( $preset['label'] ) ? (string) $preset['label'] : $preset_key;
		$description = isset( $preset['description'] ) ? (string) $preset['description'] : '';
		$lines[]     = sprintf( '%s: %s', $label, $description );
	}

	return implode( "\n", $lines );
}
