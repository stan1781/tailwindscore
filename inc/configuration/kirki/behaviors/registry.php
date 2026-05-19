<?php
/**
 * Governed commerce behavior control registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array<string, mixed>>
 */
function tailwindscore_kirki_configurable_behaviors(): array {
	return tailwindscore_behavior_registry();
}
