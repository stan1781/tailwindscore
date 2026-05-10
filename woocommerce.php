<?php
/**
 * WooCommerce wrapper template — uses native wc loop (no custom UI).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

if ( is_singular( 'product' ) ) {
	woocommerce_content();
} else {
	do_action( 'woocommerce_before_main_content' );
	get_template_part( 'template-parts/woocommerce/archive-discovery' );
	do_action( 'woocommerce_after_main_content' );
}

get_footer( 'shop' );
