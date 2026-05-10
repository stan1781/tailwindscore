<?php
/**
 * Head / WP cleanup — conservative defaults for commerce storefronts.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

remove_action( 'wp_head', 'wp_generator' );

/**
 * Disable emoji scripts/styles on front-end (keeps admin/editor behavior intact).
 */
add_action(
	'init',
	static function (): void {
		if ( is_admin() ) {
			return;
		}

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
	},
	20
);
