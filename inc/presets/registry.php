<?php
/**
 * Governed commerce mood preset registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

function tailwindscore_preset_default_key(): string {
	return 'premium-dtc';
}

/**
 * Preset governance boundaries.
 *
 * @return array<string, array<int, string>>
 */
function tailwindscore_preset_boundaries(): array {
	$boundaries = array(
		'supported'   => array(
			'design_tokens',
			'spacing_rhythm',
			'motion_rhythm',
			'density',
			'shell_pacing',
			'content_mood',
		),
		'unsupported' => array(
			'component_structure',
			'woocommerce_logic',
			'archive_architecture',
			'cart_behavior',
			'checkout_runtime',
			'interaction_patterns',
			'layout_rewrites',
			'runtime_divergence',
		),
	);

	return apply_filters( 'tailwindscore/presets/boundaries', $boundaries );
}

/**
 * Registry of governed presets.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_preset_registry(): array {
	$presets = array(
		'minimal-editorial' => array(
			'key'                        => 'minimal-editorial',
			'label'                      => 'Minimal Editorial',
			'description'                => 'Quiet editorial pacing with airy spacing, restrained accents, and reduced commerce pressure.',
			'token_overrides'            => array(
				'--ts-color-canvas'         => 'oklch(0.992 0.002 95)',
				'--ts-color-surface'        => 'oklch(0.998 0.001 95)',
				'--ts-color-border-subtle'  => 'oklch(0.9 0.008 95)',
				'--ts-color-text-primary'   => 'oklch(0.24 0.014 95)',
				'--ts-color-text-secondary' => 'oklch(0.45 0.018 95)',
				'--ts-color-accent'         => 'oklch(0.42 0.04 220)',
				'--ts-shadow-surface'       => '0 1px 2px oklch(0 0 0 / 0.03)',
			),
			'spacing_rhythm_overrides'   => array(
				'--ts-space-section-y'      => 'var(--ts-space-16)',
				'--ts-stack-gap-md'         => 'var(--ts-space-6)',
				'--ts-grid-gap'             => 'var(--ts-space-6)',
			),
			'motion_intensity_overrides' => array(
				'--ts-duration-fast'        => '120ms',
				'--ts-duration-normal'      => '180ms',
				'--ts-duration-slow'        => '260ms',
			),
			'density_overrides'          => array(
				'--ts-grid-min'             => '15rem',
			),
			'shell_mood_overrides'       => array(
				'--ts-pdp-sticky-top'       => '1.25rem',
				'--ts-container-max'        => '70rem',
			),
			'content_mood_overrides'     => array(
				'mood_key'                  => 'editorial',
				'tone'                      => 'editorial',
				'emphasis'                  => 'quiet',
				'tone_intensity'            => 'low',
				'commerce_language_pacing'  => 'slow',
				'supported_surfaces'        => array(
					'announcement_language',
					'empty_states',
					'newsletter_prompts',
					'footer_messaging',
					'search_guidance',
				),
			),
		),
		'premium-dtc'        => array(
			'key'                        => 'premium-dtc',
			'label'                      => 'Premium DTC',
			'description'                => 'The canonical TailwindScore balance of premium commerce clarity, measured confidence, and conversion-ready polish.',
			'token_overrides'            => array(),
			'spacing_rhythm_overrides'   => array(),
			'motion_intensity_overrides' => array(),
			'density_overrides'          => array(),
			'shell_mood_overrides'       => array(),
			'content_mood_overrides'     => array(
				'mood_key'                  => 'premium-commerce',
				'tone'                      => 'premium-commerce',
				'emphasis'                  => 'balanced',
				'tone_intensity'            => 'balanced',
				'commerce_language_pacing'  => 'measured',
				'supported_surfaces'        => array(
					'announcement_language',
					'trust_messaging',
					'empty_states',
					'support_messaging',
					'newsletter_prompts',
					'footer_messaging',
					'checkout_reassurance',
					'account_messaging',
					'search_guidance',
				),
			),
		),
		'soft-luxury'        => array(
			'key'                        => 'soft-luxury',
			'label'                      => 'Soft Luxury',
			'description'                => 'Warmer neutrals, softer contrast, and gentler motion for a polished but relaxed luxury mood.',
			'token_overrides'            => array(
				'--ts-color-canvas'         => 'oklch(0.975 0.008 80)',
				'--ts-color-surface'        => 'oklch(0.992 0.004 80)',
				'--ts-color-border-subtle'  => 'oklch(0.885 0.012 80)',
				'--ts-color-text-primary'   => 'oklch(0.28 0.02 55)',
				'--ts-color-text-secondary' => 'oklch(0.48 0.02 55)',
				'--ts-color-accent'         => 'oklch(0.62 0.09 35)',
				'--ts-radius-lg'            => '1rem',
				'--ts-radius-xl'            => '1.25rem',
			),
			'spacing_rhythm_overrides'   => array(
				'--ts-space-section-y'      => '3.5rem',
				'--ts-stack-gap-lg'         => '1.75rem',
			),
			'motion_intensity_overrides' => array(
				'--ts-duration-normal'      => '260ms',
				'--ts-duration-slow'        => '360ms',
				'--ts-ease-emphasis'        => 'cubic-bezier(0.2, 0.9, 0.22, 1)',
			),
			'density_overrides'          => array(
				'--ts-grid-min'             => '14.5rem',
			),
			'shell_mood_overrides'       => array(
				'--ts-space-gutter-x'       => '1.25rem',
			),
			'content_mood_overrides'     => array(
				'mood_key'                  => 'assured',
				'tone'                      => 'assured',
				'emphasis'                  => 'warm',
				'tone_intensity'            => 'warm',
				'commerce_language_pacing'  => 'measured',
				'supported_surfaces'        => array(
					'announcement_language',
					'trust_messaging',
					'support_messaging',
					'checkout_reassurance',
					'account_messaging',
				),
			),
		),
		'modern-lifestyle'   => array(
			'key'                        => 'modern-lifestyle',
			'label'                      => 'Modern Lifestyle',
			'description'                => 'A brighter, slightly more energetic mood with compact spacing and contemporary accent contrast.',
			'token_overrides'            => array(
				'--ts-color-canvas'         => 'oklch(0.986 0.004 210)',
				'--ts-color-surface-raised' => 'oklch(0.99 0.007 210)',
				'--ts-color-border-strong'  => 'oklch(0.72 0.05 220)',
				'--ts-color-accent'         => 'oklch(0.62 0.16 240)',
				'--ts-shadow-hover'         => '0 8px 20px oklch(0 0 0 / 0.08)',
			),
			'spacing_rhythm_overrides'   => array(
				'--ts-space-section-y'      => '2.75rem',
				'--ts-grid-gap'             => '1.25rem',
			),
			'motion_intensity_overrides' => array(
				'--ts-duration-fast'        => '130ms',
				'--ts-duration-normal'      => '200ms',
			),
			'density_overrides'          => array(
				'--ts-grid-min'             => '13.5rem',
			),
			'shell_mood_overrides'       => array(
				'--ts-container-max'        => '74rem',
			),
			'content_mood_overrides'     => array(
				'mood_key'                  => 'confident',
				'tone'                      => 'confident',
				'emphasis'                  => 'lively',
				'tone_intensity'            => 'medium',
				'commerce_language_pacing'  => 'brisk',
				'supported_surfaces'        => array(
					'announcement_language',
					'trust_messaging',
					'search_guidance',
					'checkout_reassurance',
				),
			),
		),
		'dark-commerce'      => array(
			'key'                        => 'dark-commerce',
			'label'                      => 'Dark Commerce',
			'description'                => 'A dark-surface commerce mood that preserves the same structure, runtime, and interaction system.',
			'token_overrides'            => array(
				'--ts-color-canvas'          => 'oklch(0.17 0.01 260)',
				'--ts-color-surface'         => 'oklch(0.22 0.012 260)',
				'--ts-color-surface-raised'  => 'oklch(0.26 0.016 260)',
				'--ts-color-border-subtle'   => 'oklch(0.34 0.012 260)',
				'--ts-color-border-strong'   => 'oklch(0.55 0.03 260)',
				'--ts-color-text-primary'    => 'oklch(0.94 0.01 260)',
				'--ts-color-text-secondary'  => 'oklch(0.78 0.014 260)',
				'--ts-color-text-muted'      => 'oklch(0.66 0.012 260)',
				'--ts-color-accent'          => 'oklch(0.72 0.14 250)',
				'--ts-color-accent-contrast' => 'oklch(0.2 0.02 260)',
				'--ts-shadow-overlay'        => '0 18px 40px oklch(0 0 0 / 0.28)',
			),
			'spacing_rhythm_overrides'   => array(
				'--ts-space-section-y'      => '3rem',
			),
			'motion_intensity_overrides' => array(
				'--ts-duration-normal'      => '230ms',
				'--ts-duration-slow'        => '340ms',
			),
			'density_overrides'          => array(
				'--ts-grid-min'             => '14rem',
			),
			'shell_mood_overrides'       => array(
				'--ts-pdp-sticky-top'       => '1rem',
			),
			'content_mood_overrides'     => array(
				'mood_key'                  => 'dramatic',
				'tone'                      => 'dramatic',
				'emphasis'                  => 'focused',
				'tone_intensity'            => 'focused',
				'commerce_language_pacing'  => 'controlled',
				'supported_surfaces'        => array(
					'announcement_language',
					'trust_messaging',
					'footer_messaging',
					'search_guidance',
				),
			),
		),
	);

	return apply_filters( 'tailwindscore/presets/registry', $presets );
}

/**
 * Fetch a single preset definition.
 *
 * @return array<string, mixed>|null
 */
function tailwindscore_preset_definition( string $key ): ?array {
	$presets = tailwindscore_preset_registry();
	$key     = sanitize_key( $key );

	return isset( $presets[ $key ] ) && is_array( $presets[ $key ] ) ? $presets[ $key ] : null;
}
