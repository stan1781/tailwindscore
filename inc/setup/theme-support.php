<?php
/**
 * Theme supports and baseline registrations.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function (): void {
		load_theme_textdomain( 'tailwindscore', TAILWINDSCORE_PATH . 'languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'search-form',
			)
		);
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );

		register_nav_menus(
			array(
				'primary'          => __( 'Primary Menu', 'tailwindscore' ),
				'footer'           => __( 'Footer Commerce Menu', 'tailwindscore' ),
				'footer_support'   => __( 'Footer Support Menu', 'tailwindscore' ),
				'footer_editorial' => __( 'Footer Editorial Menu', 'tailwindscore' ),
				'footer_social'    => __( 'Footer Social Menu', 'tailwindscore' ),
			)
		);
	}
);
