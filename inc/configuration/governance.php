<?php
/**
 * Theme configuration governance boundaries.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Canonical configuration boundaries for the theme.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_configuration_boundaries(): array {
	$boundaries = array(
		'design_tokens'      => array(
			'label'            => 'Layer 1 - Design Tokens',
			'philosophy'       => 'token-first customization',
			'governance'       => 'open',
			'supported'        => array(
				'colors',
				'typography',
				'spacing',
				'radius',
				'motion',
				'layout_width',
				'density',
				'shell_spacing',
			),
			'unsupported'      => array(
				'component-specific styling panels',
				'arbitrary template spacing overrides',
				'per-page visual forks',
			),
			'transport'        => 'css_custom_properties',
			'documentation_ref'=> 'docs/configuration/token-governance.md',
		),
		'commerce_behaviors' => array(
			'label'            => 'Layer 2 - Commerce Behaviors',
			'philosophy'       => 'controlled behavioral customization',
			'governance'       => 'limited',
			'supported'        => array(
				'sticky_header',
				'sticky_add_to_cart',
				'cart_drawer_behavior',
				'archive_density',
				'gallery_layout_mode',
				'mobile_shell_behavior',
			),
			'unsupported'      => array(
				'arbitrary component manipulation',
				'per-component layout controls',
				'visual builder composition',
			),
			'transport'        => 'enumerated_settings',
			'documentation_ref'=> 'docs/configuration/configuration-rules.md',
		),
		'content_surfaces'   => array(
			'label'            => 'Layer 3 - Content Surfaces',
			'philosophy'       => 'centralized content governance',
			'governance'       => 'registry',
			'supported'        => array(
				'announcement_content',
				'trust_messaging',
				'footer_copy',
				'social_links',
				'empty_state_language',
				'newsletter_copy',
				'guarantee_text',
				'support_messaging',
			),
			'unsupported'      => array(
				'scattered theme options',
				'duplicated text settings',
				'plugin-style admin pages',
			),
			'transport'        => 'registry_first',
			'documentation_ref'=> 'docs/content-surfaces/content-surface-rules.md',
		),
	);

	/**
	 * Filter theme configuration boundaries.
	 *
	 * @param array<string, array<string, mixed>> $boundaries Boundary map.
	 */
	return apply_filters( 'tailwindscore/configuration/boundaries', $boundaries );
}

/**
 * Preset governance metadata.
 *
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_configuration_preset_axes(): array {
	$axes = array(
		'token_variation'         => array(
			'label'       => 'Token variation',
			'description' => 'Palette, typography, radius, and motion values may vary through shared token slots.',
		),
		'spacing_rhythm_variation'=> array(
			'label'       => 'Spacing rhythm variation',
			'description' => 'Density and shell rhythm may vary inside approved token ranges.',
		),
		'commerce_mood_variation' => array(
			'label'       => 'Commerce mood variation',
			'description' => 'Interaction emphasis may shift through tokens and enumerated behavior presets.',
		),
	);

	/**
	 * Filter preset axes.
	 *
	 * @param array<string, array<string, mixed>> $axes Preset axis map.
	 */
	return apply_filters( 'tailwindscore/configuration/preset_axes', $axes );
}

/**
 * Whether a configuration key is supported inside a layer.
 */
function tailwindscore_configuration_supports( string $layer, string $key ): bool {
	$boundaries = tailwindscore_configuration_boundaries();
	$layer      = sanitize_key( $layer );
	$key        = sanitize_key( $key );
	$supported  = $boundaries[ $layer ]['supported'] ?? array();

	return is_array( $supported ) && in_array( $key, $supported, true );
}
