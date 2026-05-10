<?php
/**
 * Dequeue legacy WooCommerce gallery assets superseded by TailwindScore (Embla + PhotoSwipe 5).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false', 100 );
add_filter( 'woocommerce_single_product_photoswipe_enabled', '__return_false', 100 );
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false', 100 );

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_style( 'photoswipe' );
		wp_dequeue_style( 'photoswipe-default-skin' );
		wp_dequeue_script( 'photoswipe' );
		wp_dequeue_script( 'photoswipe-ui-default' );
	},
	100
);
