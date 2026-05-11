<?php

declare(strict_types=1);

define( 'ABSPATH', __DIR__ );

function sanitize_key( string $value ): string {
	$value = strtolower( $value );

	return preg_replace( '/[^a-z0-9_\-]/', '', $value ) ?? '';
}

function apply_filters( string $hook, $value ) {
	return $value;
}

require_once __DIR__ . '/../inc/presets/metadata/registry.php';

$metadata = tailwindscore_preset_personality_registry();
$premium  = tailwindscore_preset_personality_definition( 'premium-dtc' );

if ( ! isset( $metadata['premium-dtc'] ) ) {
	fwrite( STDERR, "Expected premium-dtc personality metadata.\n" );
	exit( 1 );
}

$required_keys = array(
	'visual_identity',
	'commerce_rhythm',
	'density_profile',
	'motion_personality',
	'mood_family',
	'shell_language',
	'content_pacing',
	'capability_matrix',
	'governance_boundary',
	'localization_posture',
);

foreach ( $required_keys as $required_key ) {
	if ( ! isset( $premium[ $required_key ] ) ) {
		fwrite( STDERR, "Missing required personality key: {$required_key}\n" );
		exit( 1 );
	}
}

if ( 'prohibited' !== ( $premium['governance_boundary']['template_branching'] ?? null ) ) {
	fwrite( STDERR, "Expected template branching to be prohibited.\n" );
	exit( 1 );
}

fwrite( STDOUT, "OK\n" );
