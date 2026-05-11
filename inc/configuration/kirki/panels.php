<?php
/**
 * Governed Kirki panel registration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_kirki_panels(): array {
	return array(
		'tailwindscore_theme_configuration' => array(
			'title'       => __( 'Commerce Configuration', 'tailwindscore' ),
			'description' => __( 'Preset-governed configuration for design language, commerce experience, content surfaces, and governance visibility.', 'tailwindscore' ),
			'priority'    => 160,
		),
	);
}

/**
 * Register panels using Kirki when available, otherwise core Customizer APIs.
 */
function tailwindscore_kirki_register_panels( ?WP_Customize_Manager $wp_customize = null ): void {
	static $kirki_registered = false;
	static $core_registered  = false;

	if ( class_exists( 'Kirki' ) && method_exists( 'Kirki', 'add_panel' ) ) {
		if ( $kirki_registered ) {
			return;
		}

		$kirki_registered = true;

		foreach ( tailwindscore_kirki_panels() as $panel_id => $panel ) {
			Kirki::add_panel( $panel_id, $panel );
		}

		return;
	}

	if ( null === $wp_customize || $core_registered ) {
		return;
	}

	$core_registered = true;

	foreach ( tailwindscore_kirki_panels() as $panel_id => $panel ) {
		$wp_customize->add_panel( $panel_id, $panel );
	}
}
