<?php
/**
 * PDP details region — tabs / accordion shell spacing only (content from WC tabs callback).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$args       = (array) ( $args ?? array() );
$classes    = array(
	'ts-section',
	'ts-product-details-section',
);
$class_attr = tailwindscore_component_classes( $classes, $args, 'section-product-details' );

?>
<section class="<?php echo esc_attr( $class_attr ); ?>" data-ts-section="product-details">
	<div class="ts-container ts-section__inner ts-product-details-section__inner ts-commerce-product-information">
		<?php do_action( 'tailwindscore/pdp/section/details' ); ?>
	</div>
</section>
