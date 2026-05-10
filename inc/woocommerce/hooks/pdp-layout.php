<?php
/**
 * PDP layout composition — relocate WC outputs after summary into TailwindScore section hooks.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! apply_filters( 'tailwindscore/pdp/use-section-layout', true ) ) {
			return;
		}

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		add_action( 'tailwindscore/pdp/section/details', 'woocommerce_output_product_data_tabs', 10 );
		add_action( 'tailwindscore/pdp/section/related', 'woocommerce_upsell_display', 5 );
		add_action( 'tailwindscore/pdp/section/related', 'woocommerce_output_related_products', 10 );
	},
	25
);
