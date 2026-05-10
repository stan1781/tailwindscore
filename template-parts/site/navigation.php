<?php
/**
 * Site navigation wrapper.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'location'    => 'primary',
	'label'       => __( 'Primary navigation', 'tailwindscore' ),
	'menu_id'     => 'ts-site-navigation',
	'menu_class'  => 'ts-nav__list',
	'nav_class'   => 'ts-nav ts-nav--desktop',
	'nav_context' => 'desktop',
	'depth'       => 3,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

tailwindscore_site_shell_render_menu( $args );
