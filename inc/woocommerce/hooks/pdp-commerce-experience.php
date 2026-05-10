<?php
/**
 * PDP commerce experience — SSR wrappers for hierarchy, purchase rhythm, sticky summary default.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Open “hero” stack: title → rating → price (closed before excerpt).
 */
function tailwindscore_pdp_commerce_open_summary_lead(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}
	echo '<div class="ts-commerce-summary__lead">';
}

/**
 * Close hero stack before short description.
 */
function tailwindscore_pdp_commerce_close_summary_lead(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}
	echo '</div>';
}

/**
 * Open purchase + stock region (wraps `woocommerce_template_single_add_to_cart` output).
 */
function tailwindscore_pdp_commerce_open_purchase_region(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}
	echo '<div class="ts-purchase-region">';
}

/**
 * Close purchase region after add-to-cart form(s).
 */
function tailwindscore_pdp_commerce_close_purchase_region(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}
	echo '</div>';
}

add_filter(
	'tailwindscore/pdp/sticky-summary-column',
	static function ( bool $enabled ): bool {
		if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
			return $enabled;
		}
		/**
		 * Sticky summary column on large viewports (conversion-focused PDP).
		 *
		 * @param bool $enabled Default true when commerce experience is on.
		 */
		return (bool) apply_filters( 'tailwindscore/pdp/commerce-sticky-summary', true );
	},
	9
);

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
			return;
		}

		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_open_summary_lead', 4 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_close_summary_lead', 19 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_open_purchase_region', 29 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_close_purchase_region', 31 );
	},
	30
);
