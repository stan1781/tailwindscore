<?php
/**
 * Governed token transport registry and SSR output bridge.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Supported transport boundaries for governed Kirki fields.
 *
 * @return array<string, array<string, string>>
 */
function tailwindscore_kirki_transport_boundaries(): array {
	return array(
		'refresh' => array(
			'label'       => 'SSR refresh',
			'description' => 'Server-rendered preview with no client-only CSS or template divergence.',
		),
	);
}

/**
 * Whitelisted token override bundles.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_governed_token_registry(): array {
	return array(
		'accent_profile'   => array(
			'label'          => __( 'Accent Profile', 'tailwindscore' ),
			'default'        => 'inherit',
			'css_var_owner'  => 'design_tokens',
			'preset_support' => 'all',
			'choices'        => array(
				'inherit'    => array(
					'label'         => __( 'Inherit active preset', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'soft-focus' => array(
					'label'         => __( 'Soft Focus', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-color-accent'          => 'oklch(0.56 0.08 220)',
						'--ts-color-accent-contrast' => 'oklch(0.985 0.002 220)',
					),
				),
				'signature'  => array(
					'label'         => __( 'Signature', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-color-accent'          => 'oklch(0.62 0.16 240)',
						'--ts-color-accent-contrast' => 'oklch(0.98 0.003 240)',
					),
				),
				'inked'      => array(
					'label'         => __( 'Inked', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-color-accent'          => 'oklch(0.4 0.08 255)',
						'--ts-color-accent-contrast' => 'oklch(0.985 0.002 255)',
					),
				),
			),
		),
		'radius_profile'   => array(
			'label'          => __( 'Radius Profile', 'tailwindscore' ),
			'default'        => 'inherit',
			'css_var_owner'  => 'design_tokens',
			'preset_support' => 'all',
			'choices'        => array(
				'inherit'  => array(
					'label'         => __( 'Inherit active preset', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'compact'  => array(
					'label'         => __( 'Compact', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-radius-lg'      => '0.625rem',
						'--ts-radius-xl'      => '0.875rem',
						'--ts-radius-card'    => '0.5rem',
						'--ts-radius-control' => '0.5rem',
					),
				),
				'balanced' => array(
					'label'         => __( 'Balanced', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'soft'     => array(
					'label'         => __( 'Soft', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-radius-lg'      => '0.875rem',
						'--ts-radius-xl'      => '1.125rem',
						'--ts-radius-card'    => '0.75rem',
						'--ts-radius-control' => '0.75rem',
					),
				),
			),
		),
		'density_profile'  => array(
			'label'          => __( 'Density Profile', 'tailwindscore' ),
			'default'        => 'inherit',
			'css_var_owner'  => 'design_tokens',
			'preset_support' => 'all',
			'choices'        => array(
				'inherit' => array(
					'label'         => __( 'Inherit active preset', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'calm'    => array(
					'label'         => __( 'Calm', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-space-section-y' => 'var(--ts-space-16)',
						'--ts-grid-gap'        => 'var(--ts-space-6)',
						'--ts-grid-min'        => '15rem',
					),
				),
				'balanced'=> array(
					'label'         => __( 'Balanced', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'compact' => array(
					'label'         => __( 'Compact', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-space-section-y' => 'var(--ts-space-12)',
						'--ts-grid-gap'        => 'var(--ts-space-4)',
						'--ts-grid-min'        => '13.5rem',
					),
				),
			),
		),
		'motion_profile'   => array(
			'label'          => __( 'Motion Profile', 'tailwindscore' ),
			'default'        => 'inherit',
			'css_var_owner'  => 'design_tokens',
			'preset_support' => 'all',
			'choices'        => array(
				'inherit'  => array(
					'label'         => __( 'Inherit active preset', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'measured' => array(
					'label'         => __( 'Measured', 'tailwindscore' ),
					'css_variables' => array(),
				),
				'gentle'   => array(
					'label'         => __( 'Gentle', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-duration-fast'   => '150ms',
						'--ts-duration-normal' => '240ms',
						'--ts-duration-slow'   => '340ms',
					),
				),
				'brisk'    => array(
					'label'         => __( 'Brisk', 'tailwindscore' ),
					'css_variables' => array(
						'--ts-duration-fast'   => '110ms',
						'--ts-duration-normal' => '170ms',
						'--ts-duration-slow'   => '240ms',
					),
				),
			),
		),
	);
}

/**
 * Resolve CSS variables for registered token controls.
 *
 * @return array<string, string>
 */
function tailwindscore_governed_token_css_variables(): array {
	$controls  = tailwindscore_kirki_registered_controls_by_type( 'token' );
	$whitelist = tailwindscore_governed_token_registry();
	$variables = array();

	foreach ( $controls as $control ) {
		if ( ! tailwindscore_kirki_control_is_preset_compatible( $control ) ) {
			continue;
		}

		$token_key = $control['token_key'] ?? '';
		$setting   = $control['setting_id'] ?? '';

		if ( ! is_string( $token_key ) || ! is_string( $setting ) || ! isset( $whitelist[ $token_key ] ) ) {
			continue;
		}

		$token_definition = $whitelist[ $token_key ];
		$choices          = isset( $token_definition['choices'] ) && is_array( $token_definition['choices'] ) ? $token_definition['choices'] : array();
		$default          = isset( $control['default'] ) ? (string) $control['default'] : (string) ( $token_definition['default'] ?? 'inherit' );
		$selected         = tailwindscore_kirki_sanitize_enum( get_theme_mod( $setting, $default ), array_keys( $choices ), $default );

		if ( ! isset( $choices[ $selected ]['css_variables'] ) || ! is_array( $choices[ $selected ]['css_variables'] ) ) {
			continue;
		}

		foreach ( $choices[ $selected ]['css_variables'] as $property => $value ) {
			$property = trim( (string) $property );
			$value    = trim( (string) $value );

			if ( '' === $property || '' === $value || ! str_starts_with( $property, '--ts-' ) ) {
				continue;
			}

			$variables[ $property ] = $value;
		}
	}

	return $variables;
}

/**
 * Merge governed token overrides into the preset CSS pipeline.
 *
 * @param array<string, string> $variables Existing preset variables.
 * @return array<string, string>
 */
function tailwindscore_kirki_merge_governed_token_css_variables( array $variables ): array {
	return array_merge( $variables, tailwindscore_governed_token_css_variables() );
}
add_filter( 'tailwindscore/presets/css_variables', 'tailwindscore_kirki_merge_governed_token_css_variables', 20, 1 );
