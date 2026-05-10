<?php
/**
 * PDP gallery column — `woocommerce_before_single_product_summary` loads TailwindScore gallery runtime (Embla + PhotoSwipe).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Optional — passed to class filter.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$args       = (array) ( $args ?? array() );
$classes    = array(
	'ts-section',
	'ts-product-gallery-section',
);
$class_attr = tailwindscore_component_classes( $classes, $args, 'section-product-gallery' );

?>
<section class="<?php echo esc_attr( $class_attr ); ?>" data-ts-section="product-gallery">
	<div class="ts-product-gallery-section__inner">
		<?php
		/**
		 * Product images template (`woocommerce/single-product/product-image.php` override).
		 *
		 * @hooked woocommerce_show_product_images — priority 20 (WC core).
		 */
		do_action( 'woocommerce_before_single_product_summary' );
		?>
	</div>
</section>
