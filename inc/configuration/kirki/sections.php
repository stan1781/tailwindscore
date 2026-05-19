<?php
/**
 * Governed Kirki section registration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_kirki_sections(): array {
	return array(
		'tailwindscore_preset_foundation' => array(
			'title'       => __( 'Design Language - Preset Personality', 'tailwindscore' ),
			'description' => __( 'Choose the governed commerce personality first. Presets own tone, rhythm, density, motion posture, and baseline token defaults.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 10,
		),
		'tailwindscore_token_foundation'  => array(
			'title'       => __( 'Design Language - Token Profiles', 'tailwindscore' ),
			'description' => __( 'Only whitelisted token profile bundles may refine the active preset without escaping preset governance.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 20,
		),
		'tailwindscore_commerce_behavior_foundation' => array(
			'title'       => __( 'Commerce Experience - Template Behaviors', 'tailwindscore' ),
			'description' => __( 'Bounded PDP and site shell runtime behaviors. Use these controls for feature posture, not copy authoring.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 30,
		),
		'tailwindscore_site_shell_content' => array(
			'title'       => __( 'Content Surfaces - Site Shell Language', 'tailwindscore' ),
			'description' => __( 'Registry-owned content surfaces are documented here for governance visibility. Copy authority remains in PHP surface definitions.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 40,
		),
		'tailwindscore_account_content'   => array(
			'title'       => __( 'Commerce Experience - Account Guidance', 'tailwindscore' ),
			'description' => __( 'Account content surfaces remain registry-first and localization-safe. Kirki no longer authors this copy family.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 60,
		),
	);
}

/**
 * Register sections using Kirki when available, otherwise core Customizer APIs.
 */
function tailwindscore_kirki_register_sections( ?WP_Customize_Manager $wp_customize = null ): void {
	static $kirki_registered = false;
	static $core_registered  = false;

	if ( class_exists( 'Kirki' ) && method_exists( 'Kirki', 'add_section' ) ) {
		if ( $kirki_registered ) {
			return;
		}

		$kirki_registered = true;

		foreach ( tailwindscore_kirki_sections() as $section_id => $section ) {
			Kirki::add_section( $section_id, $section );
		}

		return;
	}

	if ( null === $wp_customize || $core_registered ) {
		return;
	}

	$core_registered = true;

	foreach ( tailwindscore_kirki_sections() as $section_id => $section ) {
		$wp_customize->add_section( $section_id, $section );
	}
}
