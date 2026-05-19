<?php
/**
 * Commerce behavior control definitions.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Register governed commerce behavior controls.
 */
function tailwindscore_kirki_register_behavior_controls(): void {
	foreach ( tailwindscore_kirki_configurable_behaviors() as $behavior_key => $definition ) {
		$choices = isset( $definition['choices'] ) && is_array( $definition['choices'] ) ? $definition['choices'] : array();
		$default = isset( $definition['default'] ) ? (string) $definition['default'] : '';

		if ( '' === $default || array() === $choices ) {
			continue;
		}

		tailwindscore_register_behavior_control(
			array(
				'setting_id'            => isset( $definition['setting_id'] ) ? (string) $definition['setting_id'] : '',
				'section'               => isset( $definition['section'] ) ? (string) $definition['section'] : 'tailwindscore_commerce_behavior_foundation',
				'label'                 => isset( $definition['label'] ) ? (string) $definition['label'] : $behavior_key,
				'description'           => isset( $definition['description'] ) ? (string) $definition['description'] : '',
				'control_type'          => 'select',
				'default'               => $default,
				'choices'               => $choices,
				'governance_owner'      => isset( $definition['owner'] ) ? (string) $definition['owner'] : 'commerce_behaviors',
				'sanitize_callback'     => tailwindscore_kirki_enum_sanitizer( array_keys( $choices ), $default ),
				'transport_boundary'    => 'refresh',
				'preset_compatibility'  => array( 'all' ),
				'localization_strategy' => array(
					'mode'        => 'labels_translated_server_side',
					'translation' => true,
				),
				'behavior_key'          => $behavior_key,
			)
		);
	}
}
