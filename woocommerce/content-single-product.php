<?php
/**
 * Single product — section-composed layout (see `woocommerce/single-product/layout-default.php`).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

global $product;

$product = wc_get_product( get_the_ID() );

if ( ! $product ) {
	return;
}

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

do_action( 'woocommerce_before_single_product' );

$use_section_layout = apply_filters( 'tailwindscore/pdp/use-section-layout', true );

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ts-pdp', $product ); ?>>
	<?php get_template_part( 'woocommerce/single-product/layout', 'default' ); ?>
</div>
<?php

if ( $use_section_layout ) {
	/** Plugins that attach after tabs/related — core tabs/related render inside section hooks when section layout is on. */
	do_action( 'woocommerce_after_single_product_summary' );
}

do_action( 'woocommerce_after_single_product' );
