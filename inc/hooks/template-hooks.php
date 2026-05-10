<?php
/**
 * Template-oriented hooks (non-WooCommerce).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Example extension surface — keep empty in foundation phase.
 */
add_filter(
	'body_class',
	static function ( array $classes ): array {
		$classes[] = 'tailwindscore';
		return $classes;
	}
);
