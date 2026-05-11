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
require_once __DIR__ . '/../inc/presets/registry.php';
require_once __DIR__ . '/../inc/configuration/kirki/presets/registry.php';

$description = tailwindscore_kirki_preset_preview_description();

$expected_snippets = array(
	'Tone:',
	'Motion:',
	'Density:',
	'Commerce pacing:',
	'Governance:',
);

foreach ( $expected_snippets as $expected_snippet ) {
	if ( false === strpos( $description, $expected_snippet ) ) {
		fwrite( STDERR, "Expected snippet missing: {$expected_snippet}\n" );
		exit( 1 );
	}
}

fwrite( STDOUT, "OK\n" );
