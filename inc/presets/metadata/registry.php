<?php
/**
 * Preset personality metadata registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_preset_personality_registry(): array {
	$presets = array(
		'minimal-editorial' => array(
			'visual_identity'      => array(
				'label'       => 'Quiet editorial',
				'description' => 'Airy composition, restrained accents, and low-pressure product framing.',
			),
			'commerce_rhythm'      => array(
				'label'       => 'Deliberate browsing',
				'description' => 'Slower browse rhythm that gives editorial hierarchy more room than conversion urgency.',
			),
			'density_profile'      => array(
				'label'       => 'Open density',
				'description' => 'Loose spacing with expanded breathing room across collection and PDP surfaces.',
			),
			'motion_personality'   => array(
				'label'       => 'Soft restraint',
				'description' => 'Low-amplitude motion with quick settle times and minimal emphasis.',
			),
			'mood_family'          => array(
				'tone'        => 'Editorial calm',
				'description' => 'Measured, quiet, and guidance-led commerce language.',
			),
			'shell_language'       => array(
				'label'       => 'Quiet framing',
				'description' => 'Navigation and shell cues stay understated and low-noise.',
			),
			'content_pacing'       => array(
				'commerce'    => 'Slow pacing',
				'description' => 'Copy reveals value through progression instead of urgency spikes.',
			),
			'capability_matrix'    => array(
				'supported'   => array( 'design_tokens', 'spacing_rhythm', 'motion_rhythm', 'density', 'shell_pacing', 'content_mood' ),
				'unsupported' => array( 'template_branching', 'component_structure', 'runtime_divergence', 'arbitrary_controls' ),
			),
			'governance_boundary'  => array(
				'summary'             => 'Preset may shift tone and rhythm inside shared commerce templates only.',
				'template_branching'  => 'prohibited',
				'markup_divergence'   => 'prohibited',
				'runtime_feature_fork'=> 'prohibited',
				'expansion_policy'    => 'bounded',
			),
			'localization_posture' => array(
				'tone_safety'      => 'high',
				'mood_compatibility' => 'editorial-safe locales',
				'fallback_strategy' => 'fallback_to_surface_default_when_editorial_tone_is_not_localizable',
			),
		),
		'premium-dtc'        => array(
			'visual_identity'      => array(
				'label'       => 'Premium commerce default',
				'description' => 'Balanced contrast, polished trust cues, and canonical TailwindScore hierarchy.',
			),
			'commerce_rhythm'      => array(
				'label'       => 'Measured confidence',
				'description' => 'Clear purchase flow with stable pacing from discovery through checkout.',
			),
			'density_profile'      => array(
				'label'       => 'Balanced density',
				'description' => 'Moderate information density that stays scan-friendly across core commerce surfaces.',
			),
			'motion_personality'   => array(
				'label'       => 'Assured motion',
				'description' => 'Measured transitions that support state change without theatrical emphasis.',
			),
			'mood_family'          => array(
				'tone'        => 'Premium clarity',
				'description' => 'Confident, reassuring, and conversion-ready language without pressure tactics.',
			),
			'shell_language'       => array(
				'label'       => 'Commerce-forward shell',
				'description' => 'Shell surfaces reinforce trust, orientation, and shopping progress.',
			),
			'content_pacing'       => array(
				'commerce'    => 'Measured pacing',
				'description' => 'Trust and action cues remain steady across the full purchase journey.',
			),
			'capability_matrix'    => array(
				'supported'   => array( 'design_tokens', 'spacing_rhythm', 'motion_rhythm', 'density', 'shell_pacing', 'content_mood' ),
				'unsupported' => array( 'template_branching', 'component_structure', 'runtime_divergence', 'arbitrary_controls' ),
			),
			'governance_boundary'  => array(
				'summary'             => 'Canonical preset contract for shared SSR commerce templates.',
				'template_branching'  => 'prohibited',
				'markup_divergence'   => 'prohibited',
				'runtime_feature_fork'=> 'prohibited',
				'expansion_policy'    => 'bounded',
			),
			'localization_posture' => array(
				'tone_safety'        => 'high',
				'mood_compatibility' => 'broad-commerce-safe locales',
				'fallback_strategy'  => 'fallback_to_premium_surface_defaults_before_neutralizing_tone',
			),
		),
		'soft-luxury'        => array(
			'visual_identity'      => array(
				'label'       => 'Warm premium softness',
				'description' => 'Warmer neutrals, rounded surfaces, and polished but relaxed contrast.',
			),
			'commerce_rhythm'      => array(
				'label'       => 'Gentle reassurance',
				'description' => 'Trust and product cues advance through calm, polished pacing.',
			),
			'density_profile'      => array(
				'label'       => 'Relaxed density',
				'description' => 'Softer spacing and wider rest points around conversion moments.',
			),
			'motion_personality'   => array(
				'label'       => 'Glide emphasis',
				'description' => 'Longer easing curves with low-friction state changes.',
			),
			'mood_family'          => array(
				'tone'        => 'Warm assurance',
				'description' => 'Luxury-adjacent language that remains calm, supportive, and grounded.',
			),
			'shell_language'       => array(
				'label'       => 'Soft framing',
				'description' => 'Shell elements feel welcoming and polished rather than urgent.',
			),
			'content_pacing'       => array(
				'commerce'    => 'Measured pacing',
				'description' => 'Purchase guidance stays warm and composed across trust surfaces.',
			),
			'capability_matrix'    => array(
				'supported'   => array( 'design_tokens', 'spacing_rhythm', 'motion_rhythm', 'density', 'shell_pacing', 'content_mood' ),
				'unsupported' => array( 'template_branching', 'component_structure', 'runtime_divergence', 'arbitrary_controls' ),
			),
			'governance_boundary'  => array(
				'summary'             => 'Luxury mood variation stays inside shared commerce structure and governed token rails.',
				'template_branching'  => 'prohibited',
				'markup_divergence'   => 'prohibited',
				'runtime_feature_fork'=> 'prohibited',
				'expansion_policy'    => 'bounded',
			),
			'localization_posture' => array(
				'tone_safety'        => 'medium-high',
				'mood_compatibility' => 'warm-premium-safe locales',
				'fallback_strategy'  => 'fallback_to_assured_surface_copy_when_luxury_softness_does_not_localize_cleanly',
			),
		),
		'modern-lifestyle'   => array(
			'visual_identity'      => array(
				'label'       => 'Bright contemporary energy',
				'description' => 'Cleaner contrast, tighter grids, and lightly energetic accent behavior.',
			),
			'commerce_rhythm'      => array(
				'label'       => 'Brisk discovery',
				'description' => 'Discovery and product scanning move faster while preserving purchase clarity.',
			),
			'density_profile'      => array(
				'label'       => 'Compact density',
				'description' => 'Tighter browsing surfaces with strong scannability and reduced dead space.',
			),
			'motion_personality'   => array(
				'label'       => 'Quick confidence',
				'description' => 'Responsive transitions with moderate energy and clear feedback timing.',
			),
			'mood_family'          => array(
				'tone'        => 'Confident lifestyle',
				'description' => 'Contemporary, upbeat, and direct commerce language with controlled energy.',
			),
			'shell_language'       => array(
				'label'       => 'Active shell framing',
				'description' => 'Shell guidance supports movement and discovery without becoming promotional noise.',
			),
			'content_pacing'       => array(
				'commerce'    => 'Brisk pacing',
				'description' => 'Discovery and reassurance copy stays concise and action-oriented.',
			),
			'capability_matrix'    => array(
				'supported'   => array( 'design_tokens', 'spacing_rhythm', 'motion_rhythm', 'density', 'shell_pacing', 'content_mood' ),
				'unsupported' => array( 'template_branching', 'component_structure', 'runtime_divergence', 'arbitrary_controls' ),
			),
			'governance_boundary'  => array(
				'summary'             => 'Energy may increase through tokens and pacing language, never through divergent templates.',
				'template_branching'  => 'prohibited',
				'markup_divergence'   => 'prohibited',
				'runtime_feature_fork'=> 'prohibited',
				'expansion_policy'    => 'bounded',
			),
			'localization_posture' => array(
				'tone_safety'        => 'medium',
				'mood_compatibility' => 'concise-direct-safe locales',
				'fallback_strategy'  => 'fallback_to_premium_dtc_surface_copy_when_brisk_tone_over-compresses_in_locale',
			),
		),
		'dark-commerce'      => array(
			'visual_identity'      => array(
				'label'       => 'Focused dark commerce',
				'description' => 'Dark surfaces, controlled highlight contrast, and concentrated attention framing.',
			),
			'commerce_rhythm'      => array(
				'label'       => 'Controlled focus',
				'description' => 'Pacing narrows attention without changing commerce flow or interaction models.',
			),
			'density_profile'      => array(
				'label'       => 'Moderate compactness',
				'description' => 'Focused spacing that keeps dark surfaces readable and non-crowded.',
			),
			'motion_personality'   => array(
				'label'       => 'Focused settle',
				'description' => 'Controlled motion with crisp emphasis and no dramatic animation behavior.',
			),
			'mood_family'          => array(
				'tone'        => 'Dramatic control',
				'description' => 'Strong but measured commerce language with disciplined reassurance.',
			),
			'shell_language'       => array(
				'label'       => 'High-focus shell',
				'description' => 'Shell treatment stays concentrated and legible across darker surfaces.',
			),
			'content_pacing'       => array(
				'commerce'    => 'Controlled pacing',
				'description' => 'Copy remains concise and trust-forward without aggressive urgency.',
			),
			'capability_matrix'    => array(
				'supported'   => array( 'design_tokens', 'spacing_rhythm', 'motion_rhythm', 'density', 'shell_pacing', 'content_mood' ),
				'unsupported' => array( 'template_branching', 'component_structure', 'runtime_divergence', 'arbitrary_controls' ),
			),
			'governance_boundary'  => array(
				'summary'             => 'Dark preset remains a surface-language variation on the same governed commerce framework.',
				'template_branching'  => 'prohibited',
				'markup_divergence'   => 'prohibited',
				'runtime_feature_fork'=> 'prohibited',
				'expansion_policy'    => 'bounded',
			),
			'localization_posture' => array(
				'tone_safety'        => 'medium-high',
				'mood_compatibility' => 'focused-trust-safe locales',
				'fallback_strategy'  => 'fallback_to_premium_reassurance_when_dramatic_tone_reduces_clarity',
			),
		),
	);

	return apply_filters( 'tailwindscore/presets/personality_registry', $presets );
}

/**
 * @return array<string, mixed>
 */
function tailwindscore_preset_personality_definition( string $key ): array {
	$registry = tailwindscore_preset_personality_registry();
	$key      = sanitize_key( $key );

	return isset( $registry[ $key ] ) && is_array( $registry[ $key ] ) ? $registry[ $key ] : array();
}
