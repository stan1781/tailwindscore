<?php
/**
 * Bootstrap the governed Kirki foundation layer.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/sanitizers/enums.php';
require_once __DIR__ . '/transport/registry.php';
require_once __DIR__ . '/fields/api.php';
require_once __DIR__ . '/panels.php';
require_once __DIR__ . '/sections.php';
require_once __DIR__ . '/presets/registry.php';
require_once __DIR__ . '/presets/controls.php';
require_once __DIR__ . '/content-surfaces/registry.php';
require_once __DIR__ . '/content-surfaces/controls.php';
require_once __DIR__ . '/fields/token-controls.php';

/**
 * Register all governed control definitions once.
 */
function tailwindscore_kirki_register_foundation_controls(): void {
	static $registered = false;

	if ( $registered ) {
		return;
	}

	tailwindscore_kirki_register_preset_controls();
	tailwindscore_kirki_register_token_controls();
	tailwindscore_kirki_register_content_surface_controls();

	$registered = true;
}

add_action(
	'after_setup_theme',
	static function (): void {
		tailwindscore_kirki_register_foundation_controls();
		tailwindscore_kirki_register_config();
		tailwindscore_kirki_register_panels();
		tailwindscore_kirki_register_sections();
		tailwindscore_kirki_register_controls();
	},
	20
);

add_action(
	'customize_register',
	static function ( WP_Customize_Manager $wp_customize ): void {
		tailwindscore_kirki_register_panels( $wp_customize );
		tailwindscore_kirki_register_sections( $wp_customize );
		tailwindscore_kirki_register_controls( $wp_customize );
	},
	20
);
