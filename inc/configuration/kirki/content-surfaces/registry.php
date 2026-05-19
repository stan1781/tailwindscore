<?php
/**
 * Curated content surface control registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_kirki_configurable_content_surfaces(): array {
	return apply_filters( 'tailwindscore/kirki/configurable_content_surfaces', array() );
}
