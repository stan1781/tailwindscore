<?php
/**
 * Governed token control definitions.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Register token profile controls backed by the token whitelist.
 */
function tailwindscore_kirki_register_token_controls(): void {
	foreach ( tailwindscore_governed_token_registry() as $token_key => $definition ) {
		$choices = array();

		foreach ( $definition['choices'] as $choice_key => $choice ) {
			$choices[ $choice_key ] = isset( $choice['label'] ) ? (string) $choice['label'] : $choice_key;
		}

		tailwindscore_register_token_control(
			array(
				'setting_id'            => 'ts_token_' . sanitize_key( $token_key ),
				'section'               => 'tailwindscore_token_foundation',
				'label'                 => isset( $definition['label'] ) ? (string) $definition['label'] : $token_key,
				'description'           => __( 'Whitelisted preset-compatible token profile. No arbitrary values are accepted.', 'tailwindscore' ),
				'control_type'          => 'select',
				'default'               => isset( $definition['default'] ) ? (string) $definition['default'] : 'inherit',
				'choices'               => $choices,
				'governance_owner'      => isset( $definition['css_var_owner'] ) ? (string) $definition['css_var_owner'] : 'design_tokens',
				'sanitize_callback'     => tailwindscore_kirki_enum_sanitizer( array_keys( $choices ), isset( $definition['default'] ) ? (string) $definition['default'] : 'inherit' ),
				'transport_boundary'    => 'refresh',
				'preset_compatibility'  => array( 'all' ),
				'localization_strategy' => array(
					'mode'        => 'server_translated_choice_labels',
					'translation' => true,
				),
				'token_key'             => $token_key,
			)
		);
	}
}
