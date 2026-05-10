<?php
/**
 * SVG icon helper and registry.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Icon registry.
 *
 * @return array<string, array<string, string|float>>
 */
function tailwindscore_icon_registry(): array {
	static $registry = null;

	if ( null !== $registry ) {
		return $registry;
	}

	$registry = array(
		'menu'          => array( 'group' => 'core', 'file' => 'menu.svg', 'stroke_width' => 1.5 ),
		'close'         => array( 'group' => 'core', 'file' => 'close.svg', 'stroke_width' => 1.5 ),
		'chevron-down'  => array( 'group' => 'core', 'file' => 'chevron-down.svg', 'stroke_width' => 1.5 ),
		'chevron-right' => array( 'group' => 'core', 'file' => 'chevron-right.svg', 'stroke_width' => 1.5 ),
		'plus'          => array( 'group' => 'core', 'file' => 'plus.svg', 'stroke_width' => 1.5 ),
		'minus'         => array( 'group' => 'core', 'file' => 'minus.svg', 'stroke_width' => 1.5 ),
		'cart'          => array( 'group' => 'commerce', 'file' => 'cart.svg', 'stroke_width' => 1.5 ),
		'bag'           => array( 'group' => 'commerce', 'file' => 'bag.svg', 'stroke_width' => 1.5 ),
		'search'        => array( 'group' => 'commerce', 'file' => 'search.svg', 'stroke_width' => 1.5 ),
		'user'          => array( 'group' => 'commerce', 'file' => 'user.svg', 'stroke_width' => 1.5 ),
		'heart'         => array( 'group' => 'commerce', 'file' => 'heart.svg', 'stroke_width' => 1.5 ),
		'filter'        => array( 'group' => 'commerce', 'file' => 'filter.svg', 'stroke_width' => 1.5 ),
		'instagram'     => array( 'group' => 'social', 'file' => 'instagram.svg', 'stroke_width' => 1.5 ),
		'x'             => array( 'group' => 'social', 'file' => 'x.svg', 'stroke_width' => 1.5 ),
		'youtube'       => array( 'group' => 'social', 'file' => 'youtube.svg', 'stroke_width' => 1.5 ),
	);

	/**
	 * Filter icon registry entries.
	 *
	 * @param array<string, array<string, string|float>> $registry Registry.
	 */
	return apply_filters( 'tailwindscore/icon/registry', $registry );
}

/**
 * Resolve icon absolute path.
 */
function tailwindscore_icon_path( string $name ): string {
	$registry = tailwindscore_icon_registry();
	$icon     = $registry[ $name ] ?? null;

	if ( ! is_array( $icon ) || empty( $icon['group'] ) || empty( $icon['file'] ) ) {
		return '';
	}

	return TAILWINDSCORE_PATH . 'src/icons/' . $icon['group'] . '/' . $icon['file'];
}

/**
 * Get raw SVG markup for an icon.
 */
function tailwindscore_icon_source( string $name ): string {
	static $cache = array();

	if ( isset( $cache[ $name ] ) ) {
		return $cache[ $name ];
	}

	$path = tailwindscore_icon_path( $name );
	if ( '' === $path || ! file_exists( $path ) ) {
		$cache[ $name ] = '';
		return '';
	}

	$raw = file_get_contents( $path );
	if ( false === $raw ) {
		$cache[ $name ] = '';
		return '';
	}

	$cache[ $name ] = trim( $raw );
	return $cache[ $name ];
}

/**
 * Render SVG icon.
 *
 * @param string               $name Icon name.
 * @param array<string, mixed> $args Render args.
 */
function tailwindscore_icon( string $name, array $args = array() ): string {
	$source = tailwindscore_icon_source( $name );
	if ( '' === $source ) {
		return '';
	}

	$registry = tailwindscore_icon_registry();
	$icon     = $registry[ $name ] ?? array();
	$defaults = array(
		'size'         => 'md',
		'class'        => '',
		'decorative'   => true,
		'aria_label'   => '',
		'stroke_width' => is_array( $icon ) && isset( $icon['stroke_width'] ) ? (float) $icon['stroke_width'] : 1.5,
	);

	$args        = wp_parse_args( $args, $defaults );
	$size_raw    = is_scalar( $args['size'] ) ? (string) $args['size'] : 'md';
	$decorative  = (bool) $args['decorative'];
	$aria_label  = sanitize_text_field( (string) $args['aria_label'] );
	$stroke      = is_numeric( $args['stroke_width'] ) ? (string) $args['stroke_width'] : (string) $defaults['stroke_width'];
	$extra_class = sanitize_text_field( (string) $args['class'] );

	$semantic_sizes = array(
		'xs' => '14',
		'sm' => '16',
		'md' => '20',
		'lg' => '24',
	);
	$size_key = strtolower( $size_raw );
	$size     = $semantic_sizes[ $size_key ] ?? ( is_numeric( $size_raw ) ? $size_raw : $semantic_sizes['md'] );

	if ( '' === $aria_label && ! $decorative ) {
		$aria_label = ucwords( str_replace( '-', ' ', $name ) );
	}

	$classes = array( 'ts-icon', 'ts-icon--' . sanitize_html_class( $name ) );
	if ( isset( $semantic_sizes[ $size_key ] ) ) {
		$classes[] = 'ts-icon--' . sanitize_html_class( $size_key );
	}
	if ( '' !== $extra_class ) {
		$classes = array_merge( $classes, preg_split( '/\s+/', $extra_class ) ?: array() );
	}
	$class_attr = implode( ' ', array_filter( array_unique( array_map( 'sanitize_html_class', $classes ) ) ) );

	$view_box = '0 0 24 24';
	if ( preg_match( '/viewBox=(["\'])([^"\']+)\1/i', $source, $view_box_match ) ) {
		$view_box = (string) $view_box_match[2];
	}

	$fill = 'none';
	if ( preg_match( '/fill=(["\'])([^"\']+)\1/i', $source, $fill_match ) ) {
		$fill = (string) $fill_match[2];
	}

	$attrs = array(
		'class'        => $class_attr,
		'xmlns'        => 'http://www.w3.org/2000/svg',
		'viewBox'      => $view_box,
		'fill'         => $fill,
		'width'        => $size,
		'height'       => $size,
		'stroke-width' => $stroke,
		'focusable'    => 'false',
	);

	$attr_parts = array();
	foreach ( $attrs as $key => $value ) {
		$attr_parts[] = sprintf( '%s="%s"', $key, esc_attr( (string) $value ) );
	}

	if ( $decorative ) {
		$attr_parts[] = 'aria-hidden="true"';
	} else {
		$attr_parts[] = 'role="img"';
		$attr_parts[] = sprintf( 'aria-label="%s"', esc_attr( $aria_label ) );
	}

	$svg = preg_replace( '/<svg\b[^>]*>/i', '<svg ' . implode( ' ', $attr_parts ) . '>', $source, 1 );

	if ( null === $svg ) {
		return '';
	}

	$svg = tailwindscore_kses_icon_html( $svg );

	/**
	 * Filter final icon markup.
	 *
	 * @param string               $svg  SVG markup.
	 * @param string               $name Icon name.
	 * @param array<string, mixed> $args Render args.
	 */
	return (string) apply_filters( 'tailwindscore/icon/html', $svg, $name, $args );
}
