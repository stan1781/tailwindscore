<?php
/**
 * PHP component runtime — loads template-parts/components/{name}.php with explicit $args.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

require_once TAILWINDSCORE_PATH . 'inc/helpers/kses.php';

/**
 * Render a theme component from template-parts/components.
 *
 * @param string               $name Machine-readable path without `.php` (e.g. `button`, `product-summary/title`).
 * @param array<string, mixed> $args Explicit props (merged with defaults inside each component).
 */
function tailwindscore_component( string $name, array $args = array() ): void {
	$name = preg_replace( '#[^a-zA-Z0-9\-/]#', '', $name );
	$name = trim( $name, '/' );

	if ( '' === $name || str_contains( $name, '..' ) ) {
		return;
	}

	$path = 'template-parts/components/' . $name;

	/**
	 * Filter component arguments before locate_template.
	 *
	 * @param array<string, mixed> $args Arguments.
	 * @param string               $name Component name.
	 */
	$args = apply_filters( 'tailwindscore/component/args', $args, $name );

	get_template_part( $path, null, $args );
}

/**
 * Convert associative array to HTML attributes string (keys and values escaped).
 *
 * @param array<string, string|bool|int|float|null> $attrs Attributes.
 */
function tailwindscore_attributes_html( array $attrs ): string {
	$html = '';

	foreach ( $attrs as $key => $value ) {
		if ( null === $value || false === $value ) {
			continue;
		}

		$key = sanitize_key( (string) $key );
		if ( '' === $key ) {
			continue;
		}

		if ( true === $value ) {
			$html .= ' ' . esc_attr( $key );
			continue;
		}

		$html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( (string) $value ) );
	}

	return $html;
}

/**
 * Merge component class strings (token/component classes only — no utilities from callers).
 *
 * @param list<string>       $classes Classes.
 * @param array<string, mixed> $args   Original component args (for filters).
 * @param string             $component Component name.
 */
function tailwindscore_component_classes( array $classes, array $args, string $component ): string {
	$classes = array_values( array_filter( array_unique( array_map( 'sanitize_html_class', $classes ) ) ) );

	/**
	 * Filter final HTML class attribute value for a component.
	 *
	 * @param list<string>         $classes   Classes.
	 * @param array<string, mixed> $args      Component arguments.
	 */
	$classes = apply_filters( "tailwindscore/component/{$component}/classes", $classes, $args );

	return implode( ' ', $classes );
}
