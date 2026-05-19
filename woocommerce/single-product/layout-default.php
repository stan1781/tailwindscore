<?php
/**
 * Single product — default section composition.
 *
 * When `tailwindscore/pdp/use-section-layout` is false, WC `woocommerce_after_single_product_summary`
 * runs once here (tabs + upsells + related). Otherwise those callbacks fire inside section templates.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$use_section_layout = tailwindscore_pdp_use_section_layout();

$grid_classes = array( 'ts-pdp__grid', 'ts-pdp__grid--split' );

if ( tailwindscore_pdp_sticky_gallery_column() ) {
	$grid_classes[] = 'ts-pdp__grid--sticky-gallery';
}

if ( tailwindscore_pdp_sticky_summary_column() ) {
	$grid_classes[] = 'ts-pdp__grid--sticky-summary';
}

$grid_class_attr = implode( ' ', array_map( 'sanitize_html_class', $grid_classes ) );

?>
<div class="ts-container">
	<div class="<?php echo esc_attr( $grid_class_attr ); ?>">
		<?php get_template_part( 'template-parts/sections/product-gallery-section' ); ?>
		<?php get_template_part( 'template-parts/sections/product-summary-section' ); ?>
	</div>
</div>

<?php if ( $use_section_layout ) : ?>
	<?php get_template_part( 'template-parts/sections/product-details-section' ); ?>
	<?php get_template_part( 'template-parts/sections/related-products-section' ); ?>
<?php else : ?>
	<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
<?php endif; ?>
