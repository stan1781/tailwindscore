<?php
/**
 * PDP related + upsells region — section composition only (WC outputs grids / lists).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$args       = (array) ( $args ?? array() );
$classes    = array(
	'ts-section',
	'ts-related-products-section',
);
$class_attr = tailwindscore_component_classes( $classes, $args, 'section-related-products' );

?>
<section class="<?php echo esc_attr( $class_attr ); ?>" data-ts-section="related-products">
	<div class="ts-container ts-section__inner ts-related-products-section__inner ts-commerce-related">
		<?php do_action( 'tailwindscore/pdp/section/related' ); ?>
	</div>
</section>
