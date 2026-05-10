<?php
/**
 * Preset control definitions.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Register the preset-driven Customizer field set.
 */
function tailwindscore_kirki_register_preset_controls(): void {
	$choices = tailwindscore_kirki_preset_choices();

	tailwindscore_register_preset_control(
		array(
			'setting_id'            => 'ts_preset_key',
			'section'               => 'tailwindscore_preset_foundation',
			'label'                 => __( 'Commerce Preset', 'tailwindscore' ),
			'description'           => tailwindscore_kirki_preset_preview_description(),
			'control_type'          => 'select',
			'default'               => tailwindscore_preset_default_key(),
			'choices'               => $choices,
			'governance_owner'      => 'preset_governance',
			'sanitize_callback'     => tailwindscore_kirki_enum_sanitizer( array_keys( $choices ), tailwindscore_preset_default_key() ),
			'transport_boundary'    => 'refresh',
			'preset_compatibility'  => array( 'all' ),
			'localization_strategy' => array(
				'mode'        => 'labels_translated_server_side',
				'translation' => true,
			),
		)
	);
}
