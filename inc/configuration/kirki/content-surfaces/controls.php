<?php
/**
 * Content surface control definitions.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Register curated content surface controls.
 */
function tailwindscore_kirki_register_content_surface_controls(): void {
	foreach ( tailwindscore_kirki_configurable_content_surfaces() as $surface_key => $ui_meta ) {
		$surface = tailwindscore_content_surface_definition( $surface_key );

		if ( ! is_array( $surface ) ) {
			continue;
		}

		$entry        = isset( $surface['customization_entry'] ) && is_array( $surface['customization_entry'] ) ? $surface['customization_entry'] : array();
		$setting_id   = isset( $entry['setting_id'] ) ? (string) $entry['setting_id'] : '';
		$control_type = isset( $entry['control'] ) && in_array( $entry['control'], array( 'text', 'textarea' ), true ) ? $entry['control'] : 'text';
		$sanitizer    = $surface['sanitizer'] ?? null;
		$mood_surface = isset( $surface['mood_surface'] ) ? (string) $surface['mood_surface'] : '';
		$description  = sprintf(
			/* translators: 1: mood surface key, 2: setting transport */
			__( 'Governed surface: %1$s. Stored via %2$s and resolved on the server for the active preset mood.', 'tailwindscore' ),
			$mood_surface,
			isset( $entry['transport'] ) ? (string) $entry['transport'] : 'theme_mod'
		);

		if ( '' === $setting_id || ! is_callable( $sanitizer ) ) {
			continue;
		}

		tailwindscore_register_content_surface_control(
			array(
				'setting_id'            => $setting_id,
				'section'               => $ui_meta['section'],
				'label'                 => isset( $surface['label'] ) ? (string) $surface['label'] : $surface_key,
				'description'           => $description,
				'control_type'          => $control_type,
				'default'               => tailwindscore_content_surface_value( $surface_key ),
				'governance_owner'      => 'content_surfaces',
				'sanitize_callback'     => $sanitizer,
				'transport_boundary'    => 'refresh',
				'preset_compatibility'  => array( 'all' ),
				'localization_strategy' => array(
					'mode'              => ! empty( $surface['translation_support'] ) ? 'i18n_fallback_with_theme_mod_override' : 'structured_non_translatable',
					'translation_safe'  => ! empty( $surface['translation_support'] ),
					'mood_surface'      => $mood_surface,
				),
				'content_surface_key'   => $surface_key,
			)
		);
	}
}
