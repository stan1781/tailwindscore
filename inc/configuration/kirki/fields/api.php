<?php
/**
 * Governance-aware Kirki registration API.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Register the Kirki config when the plugin is present.
 */
function tailwindscore_kirki_register_config(): void {
	if ( ! class_exists( 'Kirki' ) || ! method_exists( 'Kirki', 'add_config' ) ) {
		return;
	}

	static $registered = false;

	if ( $registered ) {
		return;
	}

	Kirki::add_config(
		'tailwindscore',
		array(
			'capability'  => 'edit_theme_options',
			'option_type' => 'theme_mod',
		)
	);

	$registered = true;
}

/**
 * @return array<string, array<int, array<string, mixed>>>
 */
function tailwindscore_kirki_control_store(): array {
	static $store = array(
		'token'           => array(),
		'preset'          => array(),
		'behavior'        => array(),
		'content_surface' => array(),
	);

	return $store;
}

/**
 * @param array<string, array<int, array<string, mixed>>> $store Updated store.
 */
function tailwindscore_kirki_set_control_store( array $store ): void {
	$GLOBALS['tailwindscore_kirki_control_store'] = $store;
}

/**
 * @return array<string, array<int, array<string, mixed>>>
 */
function tailwindscore_kirki_get_control_store(): array {
	if ( isset( $GLOBALS['tailwindscore_kirki_control_store'] ) && is_array( $GLOBALS['tailwindscore_kirki_control_store'] ) ) {
		return $GLOBALS['tailwindscore_kirki_control_store'];
	}

	$store = tailwindscore_kirki_control_store();
	tailwindscore_kirki_set_control_store( $store );

	return $store;
}

/**
 * @return array<int, array<string, mixed>>
 */
function tailwindscore_kirki_registered_controls_by_type( string $type ): array {
	$store = tailwindscore_kirki_get_control_store();

	return isset( $store[ $type ] ) && is_array( $store[ $type ] ) ? $store[ $type ] : array();
}

/**
 * Whether a control may operate with the current preset.
 *
 * @param array<string, mixed> $control Control definition.
 */
function tailwindscore_kirki_control_is_preset_compatible( array $control ): bool {
	$compatibility = $control['preset_compatibility'] ?? array();

	if ( ! is_array( $compatibility ) || array() === $compatibility ) {
		return false;
	}

	if ( in_array( 'all', $compatibility, true ) ) {
		return true;
	}

	return in_array( tailwindscore_preset_active_key(), $compatibility, true );
}

/**
 * Internal governance validation for controlled field registration.
 *
 * @param array<string, mixed> $args Field definition.
 * @return array<string, mixed>
 */
function tailwindscore_kirki_normalize_control_args( string $type, array $args ): array {
	$owner                = sanitize_key( (string) ( $args['governance_owner'] ?? '' ) );
	$setting_id           = sanitize_key( (string) ( $args['setting_id'] ?? '' ) );
	$section              = sanitize_key( (string) ( $args['section'] ?? '' ) );
	$label                = isset( $args['label'] ) ? (string) $args['label'] : '';
	$transport_boundary   = sanitize_key( (string) ( $args['transport_boundary'] ?? '' ) );
	$preset_compatibility = $args['preset_compatibility'] ?? array();
	$localization         = $args['localization_strategy'] ?? array();
	$sanitizer            = $args['sanitize_callback'] ?? null;

	if ( '' === $owner || '' === $setting_id || '' === $section || '' === $label ) {
		return array();
	}

	if ( ! is_callable( $sanitizer ) ) {
		return array();
	}

	if ( ! isset( tailwindscore_kirki_transport_boundaries()[ $transport_boundary ] ) ) {
		return array();
	}

	if ( ! is_array( $preset_compatibility ) || array() === $preset_compatibility ) {
		return array();
	}

	if ( ! is_array( $localization ) || ! isset( $localization['mode'] ) ) {
		return array();
	}

	$args['type']                 = $type;
	$args['governance_owner']     = $owner;
	$args['setting_id']           = $setting_id;
	$args['section']              = $section;
	$args['label']                = $label;
	$args['transport_boundary']   = $transport_boundary;
	$args['sanitize_callback']    = $sanitizer;
	$args['preset_compatibility'] = array_values( array_map( 'sanitize_key', $preset_compatibility ) );

	return $args;
}

/**
 * Register a governed token control.
 *
 * @param array<string, mixed> $args Token control definition.
 */
function tailwindscore_register_token_control( array $args ): void {
	$normalized = tailwindscore_kirki_normalize_control_args( 'token', $args );

	if ( array() === $normalized ) {
		return;
	}

	$store            = tailwindscore_kirki_get_control_store();
	$store['token'][] = $normalized;
	tailwindscore_kirki_set_control_store( $store );
}

/**
 * Register a governed preset control.
 *
 * @param array<string, mixed> $args Preset control definition.
 */
function tailwindscore_register_preset_control( array $args ): void {
	$normalized = tailwindscore_kirki_normalize_control_args( 'preset', $args );

	if ( array() === $normalized ) {
		return;
	}

	$store             = tailwindscore_kirki_get_control_store();
	$store['preset'][] = $normalized;
	tailwindscore_kirki_set_control_store( $store );
}

/**
 * Register a governed behavior control.
 *
 * @param array<string, mixed> $args Behavior control definition.
 */
function tailwindscore_register_behavior_control( array $args ): void {
	$normalized = tailwindscore_kirki_normalize_control_args( 'behavior', $args );

	if ( array() === $normalized ) {
		return;
	}

	$store               = tailwindscore_kirki_get_control_store();
	$store['behavior'][] = $normalized;
	tailwindscore_kirki_set_control_store( $store );
}

/**
 * Register a governed content surface control.
 *
 * @param array<string, mixed> $args Content surface control definition.
 */
function tailwindscore_register_content_surface_control( array $args ): void {
	$normalized = tailwindscore_kirki_normalize_control_args( 'content_surface', $args );

	if ( array() === $normalized ) {
		return;
	}

	$store                      = tailwindscore_kirki_get_control_store();
	$store['content_surface'][] = $normalized;
	tailwindscore_kirki_set_control_store( $store );
}

/**
 * Register all stored controls with Kirki or the core Customizer.
 */
function tailwindscore_kirki_register_controls( ?WP_Customize_Manager $wp_customize = null ): void {
	static $kirki_registered = false;
	static $core_registered  = false;

	if ( class_exists( 'Kirki' ) && method_exists( 'Kirki', 'add_field' ) ) {
		if ( $kirki_registered ) {
			return;
		}

		$kirki_registered = true;

		foreach ( tailwindscore_kirki_get_control_store() as $controls ) {
			foreach ( $controls as $control ) {
				tailwindscore_kirki_register_control( null, $control );
			}
		}

		return;
	}

	if ( null === $wp_customize || $core_registered ) {
		return;
	}

	$core_registered = true;

	foreach ( tailwindscore_kirki_get_control_store() as $controls ) {
		foreach ( $controls as $control ) {
			tailwindscore_kirki_register_control( $wp_customize, $control );
		}
	}
}

/**
 * Register a single governed control.
 *
 * @param array<string, mixed> $control Control definition.
 */
function tailwindscore_kirki_register_control( ?WP_Customize_Manager $wp_customize, array $control ): void {
	if ( class_exists( 'Kirki' ) && method_exists( 'Kirki', 'add_field' ) ) {
		$kirki_args = array(
			'type'              => $control['control_type'] ?? 'text',
			'settings'          => $control['setting_id'],
			'label'             => $control['label'],
			'description'       => $control['description'] ?? '',
			'section'           => $control['section'],
			'default'           => $control['default'] ?? '',
			'choices'           => $control['choices'] ?? array(),
			'transport'         => 'refresh',
			'sanitize_callback' => $control['sanitize_callback'],
		);

		Kirki::add_field( 'tailwindscore', $kirki_args );
		return;
	}

	if ( null === $wp_customize ) {
		return;
	}

	$setting_args = array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'default'           => $control['default'] ?? '',
		'sanitize_callback' => $control['sanitize_callback'],
		'transport'         => 'refresh',
	);

	$wp_customize->add_setting( $control['setting_id'], $setting_args );

	$control_type = $control['control_type'] ?? 'text';
	$wp_type      = in_array( $control_type, array( 'select', 'textarea' ), true ) ? $control_type : 'text';

	$wp_customize->add_control(
		$control['setting_id'],
		array(
			'type'        => $wp_type,
			'label'       => $control['label'],
			'description' => $control['description'] ?? '',
			'section'     => $control['section'],
			'choices'     => $control['choices'] ?? array(),
		)
	);
}
