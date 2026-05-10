<?php
/**
 * WooCommerce theme support declarations (native-friendly).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function (): void {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_theme_support( 'woocommerce' );

		/**
		 * Intentionally omit `wc-product-gallery-*` theme supports so WC single-product.js
		 * keeps Flexslider, legacy PhotoSwipe, and jQuery zoom disabled; gallery is TailwindScore runtime.
		 */
	},
	20
);
