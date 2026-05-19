<?php

declare(strict_types=1);

define( 'ABSPATH', __DIR__ );

$GLOBALS['ts_theme_mods'] = array();
$GLOBALS['ts_filters']    = array();

function sanitize_key( string $value ): string {
	$value = strtolower( $value );

	return preg_replace( '/[^a-z0-9_\-]/', '', $value ) ?? '';
}

function __( string $text, string $domain = 'tailwindscore' ): string {
	unset( $domain );

	return $text;
}

function get_theme_mod( string $setting, $default = false ) {
	return $GLOBALS['ts_theme_mods'][ $setting ] ?? $default;
}

function add_filter( string $hook, callable $callback, int $priority = 10, int $accepted_args = 1 ): void {
	unset( $accepted_args );

	$GLOBALS['ts_filters'][ $hook ][ $priority ][] = $callback;
}

function apply_filters( string $hook, $value ) {
	$args = func_get_args();
	array_shift( $args );
	$value = array_shift( $args );

	if ( ! isset( $GLOBALS['ts_filters'][ $hook ] ) ) {
		return $value;
	}

	ksort( $GLOBALS['ts_filters'][ $hook ] );

	foreach ( $GLOBALS['ts_filters'][ $hook ] as $callbacks ) {
		foreach ( $callbacks as $callback ) {
			$value = $callback( $value, ...$args );
		}
	}

	return $value;
}

require_once __DIR__ . '/../inc/configuration/kirki/sanitizers/enums.php';
require_once __DIR__ . '/../inc/configuration/kirki/transport/registry.php';
require_once __DIR__ . '/../inc/configuration/kirki/fields/api.php';
require_once __DIR__ . '/../inc/configuration/behaviors/registry.php';
require_once __DIR__ . '/../inc/configuration/kirki/behaviors/registry.php';
require_once __DIR__ . '/../inc/configuration/kirki/behaviors/controls.php';

$registry = tailwindscore_behavior_registry();
$expected = array(
	'pdp-use-section-layout',
	'pdp-sticky-gallery-column',
	'pdp-sticky-summary-column',
	'pdp-commerce-experience',
	'site-header-sticky',
	'site-header-transparent',
);

foreach ( $expected as $key ) {
	if ( ! isset( $registry[ $key ] ) ) {
		fwrite( STDERR, "Missing behavior registry key {$key}\n" );
		exit( 1 );
	}
}

$GLOBALS['ts_theme_mods']['ts_behavior_pdp_use_section_layout'] = 'disabled';
if ( false !== tailwindscore_pdp_use_section_layout() ) {
	fwrite( STDERR, "PDP section layout behavior did not resolve disabled value\n" );
	exit( 1 );
}

$GLOBALS['ts_theme_mods']['ts_behavior_site_header_transparent'] = 'contextual';
if ( false !== tailwindscore_site_header_is_transparent( false ) ) {
	fwrite( STDERR, "Contextual transparent header should inherit the runtime false default\n" );
	exit( 1 );
}

if ( true !== tailwindscore_site_header_is_transparent( true ) ) {
	fwrite( STDERR, "Contextual transparent header should inherit the runtime true default\n" );
	exit( 1 );
}

$GLOBALS['ts_theme_mods']['ts_behavior_site_header_sticky'] = 'invalid-choice';
if ( true !== tailwindscore_site_header_is_sticky() ) {
	fwrite( STDERR, "Invalid header sticky value should fall back to the governed default\n" );
	exit( 1 );
}

tailwindscore_kirki_register_behavior_controls();
$controls = tailwindscore_kirki_registered_controls_by_type( 'behavior' );

if ( 6 !== count( $controls ) ) {
	fwrite( STDERR, 'Expected six governed behavior controls' . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "OK\n" );
