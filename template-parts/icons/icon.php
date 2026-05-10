<?php
/**
 * SSR icon template.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'name'         => '',
	'size'         => 'md',
	'class'        => '',
	'decorative'   => true,
	'aria_label'   => '',
	'stroke_width' => null,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );
$name = sanitize_key( str_replace( '-', '_', (string) $args['name'] ) );
$name = str_replace( '_', '-', $name );

if ( '' === $name ) {
	return;
}

echo tailwindscore_icon( $name, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
