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
			'title'       => __( 'Preset Foundation', 'tailwindscore' ),
			'description' => __( 'Choose the commerce preset first. Presets own mood, rhythm, and baseline token defaults.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 10,
		),
		'tailwindscore_token_foundation'  => array(
			'title'       => __( 'Token Profiles', 'tailwindscore' ),
			'description' => __( 'Only whitelisted token profile bundles may override the active preset.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 20,
		),
		'tailwindscore_site_shell_content' => array(
			'title'       => __( 'Site Shell Content', 'tailwindscore' ),
			'description' => __( 'Governed content surfaces for announcement, footer, and support guidance.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 30,
		),
		'tailwindscore_checkout_content'  => array(
			'title'       => __( 'Checkout Guidance', 'tailwindscore' ),
			'description' => __( 'Registry-backed reassurance copy for checkout-critical surfaces.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 40,
		),
		'tailwindscore_account_content'   => array(
			'title'       => __( 'Account Guidance', 'tailwindscore' ),
			'description' => __( 'Post-purchase messaging stays mood-aware and localization-safe.', 'tailwindscore' ),
			'panel'       => 'tailwindscore_theme_configuration',
			'priority'    => 50,
		),
		'tailwindscore_search_content'    => array(
			'title'       => __( 'Search Guidance', 'tailwindscore' ),
			'description' => __( 'Discovery guidance remains SSR-safe and consistent with the active content mood.', 'tailwindscore' ),
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
