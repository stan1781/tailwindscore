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
 * @param array<string, mixed> $preset
 */
function tailwindscore_kirki_preset_preview_lines( string $preset_key, array $preset ): array {
	$personality = tailwindscore_preset_personality_definition( $preset_key );
	$description = isset( $preset['description'] ) ? (string) $preset['description'] : '';
	$tone        = isset( $personality['mood_family']['tone'] ) ? (string) $personality['mood_family']['tone'] : $description;
	$motion      = isset( $personality['motion_personality']['description'] ) ? (string) $personality['motion_personality']['description'] : $description;
	$density     = isset( $personality['density_profile']['description'] ) ? (string) $personality['density_profile']['description'] : $description;
	$pacing      = isset( $personality['content_pacing']['commerce'] ) ? (string) $personality['content_pacing']['commerce'] : $description;
	$governance  = isset( $personality['governance_boundary']['summary'] ) ? (string) $personality['governance_boundary']['summary'] : $description;

	return array(
		sprintf( '%s', isset( $preset['label'] ) ? (string) $preset['label'] : $preset_key ),
		sprintf( 'Tone: %s', $tone ),
		sprintf( 'Motion: %s', $motion ),
		sprintf( 'Density: %s', $density ),
		sprintf( 'Commerce pacing: %s', $pacing ),
		sprintf( 'Governance: %s', $governance ),
	);
}

/**
 * Build an SSR-safe preview summary for the preset selector.
 */
function tailwindscore_kirki_preset_preview_description(): string {
	$lines = array();

	foreach ( tailwindscore_preset_registry() as $preset_key => $preset ) {
		$lines[] = implode( "\n", tailwindscore_kirki_preset_preview_lines( $preset_key, $preset ) );
	}

	return implode( "\n\n", $lines );
}
