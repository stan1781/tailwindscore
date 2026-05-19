<?php
/**
 * Governed commerce behavior registry and runtime resolvers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_behavior_registry(): array {
	$registry = array(
		'pdp-use-section-layout'     => array(
			'key'          => 'pdp-use-section-layout',
			'label'        => __( 'PDP Section Layout', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_pdp_use_section_layout',
			'default'      => 'enabled',
			'choices'      => array(
				'enabled'  => __( 'Enabled', 'tailwindscore' ),
				'disabled' => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'enabled'  => true,
				'disabled' => false,
			),
			'description'  => __( 'Use the section-composed single-product layout instead of WooCommerce post-summary output only.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
		'pdp-sticky-gallery-column'  => array(
			'key'          => 'pdp-sticky-gallery-column',
			'label'        => __( 'PDP Sticky Gallery Column', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_pdp_sticky_gallery_column',
			'default'      => 'enabled',
			'choices'      => array(
				'enabled'  => __( 'Enabled', 'tailwindscore' ),
				'disabled' => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'enabled'  => true,
				'disabled' => false,
			),
			'description'  => __( 'Keep the PDP gallery column pinned during scroll when the layout allows it.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
		'pdp-sticky-summary-column'  => array(
			'key'          => 'pdp-sticky-summary-column',
			'label'        => __( 'PDP Sticky Summary Column', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_pdp_sticky_summary_column',
			'default'      => 'disabled',
			'choices'      => array(
				'enabled'  => __( 'Enabled', 'tailwindscore' ),
				'disabled' => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'enabled'  => true,
				'disabled' => false,
			),
			'description'  => __( 'Allow the purchase summary column to stay pinned as a governed PDP behavior.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
		'pdp-commerce-experience'    => array(
			'key'          => 'pdp-commerce-experience',
			'label'        => __( 'PDP Commerce Experience', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_pdp_commerce_experience',
			'default'      => 'enabled',
			'choices'      => array(
				'enabled'  => __( 'Enabled', 'tailwindscore' ),
				'disabled' => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'enabled'  => true,
				'disabled' => false,
			),
			'description'  => __( 'Wrap the PDP purchase flow with TailwindScore commerce affordances and summary regions.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
		'site-header-sticky'         => array(
			'key'          => 'site-header-sticky',
			'label'        => __( 'Sticky Header', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_site_header_sticky',
			'default'      => 'enabled',
			'choices'      => array(
				'enabled'  => __( 'Enabled', 'tailwindscore' ),
				'disabled' => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'enabled'  => true,
				'disabled' => false,
			),
			'description'  => __( 'Keep the global site header sticky across the storefront.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
		'site-header-transparent'    => array(
			'key'          => 'site-header-transparent',
			'label'        => __( 'Transparent Header', 'tailwindscore' ),
			'setting_id'   => 'ts_behavior_site_header_transparent',
			'default'      => 'contextual',
			'choices'      => array(
				'contextual' => __( 'Inherit runtime context', 'tailwindscore' ),
				'enabled'    => __( 'Enabled', 'tailwindscore' ),
				'disabled'   => __( 'Disabled', 'tailwindscore' ),
			),
			'value_map'    => array(
				'contextual' => 'contextual',
				'enabled'    => true,
				'disabled'   => false,
			),
			'description'  => __( 'Allow the site header to inherit front-page transparency or force a stable storefront state.', 'tailwindscore' ),
			'section'      => 'tailwindscore_commerce_behavior_foundation',
			'owner'        => 'commerce_behaviors',
		),
	);

	return apply_filters( 'tailwindscore/configuration/commerce_behaviors', $registry );
}

/**
 * @return array<string, mixed>|null
 */
function tailwindscore_behavior_definition( string $key ): ?array {
	$registry = tailwindscore_behavior_registry();
	$key      = sanitize_key( $key );

	return isset( $registry[ $key ] ) && is_array( $registry[ $key ] ) ? $registry[ $key ] : null;
}

/**
 * Resolve the governed stored choice for a behavior.
 */
function tailwindscore_behavior_choice( string $key ): string {
	$definition = tailwindscore_behavior_definition( $key );

	if ( ! is_array( $definition ) ) {
		return '';
	}

	$setting_id = isset( $definition['setting_id'] ) ? sanitize_key( (string) $definition['setting_id'] ) : '';
	$default    = isset( $definition['default'] ) ? sanitize_key( (string) $definition['default'] ) : '';
	$choices    = isset( $definition['choices'] ) && is_array( $definition['choices'] ) ? array_keys( $definition['choices'] ) : array();

	if ( '' === $setting_id || array() === $choices ) {
		return $default;
	}

	return tailwindscore_kirki_sanitize_enum( get_theme_mod( $setting_id, $default ), $choices, $default );
}

/**
 * Resolve the mapped runtime value for a behavior.
 *
 * @return mixed
 */
function tailwindscore_behavior_value( string $key, $context_default = null ) {
	$definition = tailwindscore_behavior_definition( $key );

	if ( ! is_array( $definition ) ) {
		return $context_default;
	}

	$choice    = tailwindscore_behavior_choice( $key );
	$value_map = isset( $definition['value_map'] ) && is_array( $definition['value_map'] ) ? $definition['value_map'] : array();
	$value     = $value_map[ $choice ] ?? $context_default;

	if ( 'contextual' === $value ) {
		return $context_default;
	}

	return $value;
}

/**
 * Resolve a boolean behavior while supporting contextual defaults.
 */
function tailwindscore_behavior_flag( string $key, bool $context_default = false ): bool {
	$value = tailwindscore_behavior_value( $key, $context_default );

	return is_bool( $value ) ? $value : $context_default;
}

function tailwindscore_pdp_use_section_layout(): bool {
	return (bool) apply_filters( 'tailwindscore/pdp/use-section-layout', tailwindscore_behavior_flag( 'pdp-use-section-layout', true ) );
}

function tailwindscore_pdp_sticky_gallery_column(): bool {
	return (bool) apply_filters( 'tailwindscore/pdp/sticky-gallery-column', tailwindscore_behavior_flag( 'pdp-sticky-gallery-column', true ) );
}

function tailwindscore_pdp_commerce_experience(): bool {
	return (bool) apply_filters( 'tailwindscore/pdp/commerce-experience', tailwindscore_behavior_flag( 'pdp-commerce-experience', true ) );
}

function tailwindscore_pdp_sticky_summary_column(): bool {
	return (bool) apply_filters( 'tailwindscore/pdp/sticky-summary-column', tailwindscore_behavior_flag( 'pdp-sticky-summary-column', false ) );
}

function tailwindscore_site_header_is_sticky(): bool {
	return (bool) apply_filters( 'tailwindscore/site_shell/header/is_sticky', tailwindscore_behavior_flag( 'site-header-sticky', true ) );
}

function tailwindscore_site_header_is_transparent( bool $context_default ): bool {
	return (bool) apply_filters( 'tailwindscore/site_shell/header/is_transparent', tailwindscore_behavior_flag( 'site-header-transparent', $context_default ) );
}
