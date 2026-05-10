<?php
/**
 * PDP summary column — spacing + stacking only (hooks unchanged).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Optional filters via {@see tailwindscore/component/section-product-summary/classes}.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$args      = (array) ( $args ?? array() );
$classes   = array(
	'ts-section',
	'ts-product-summary-section',
);
$class_attr = tailwindscore_component_classes( $classes, $args, 'section-product-summary' );

?>
<section class="<?php echo esc_attr( $class_attr ); ?>" data-ts-section="product-summary">
	<div class="summary entry-summary ts-product-summary ts-commerce-summary ts-stack ts-stack--lg ts-product-summary-section__inner">
		<?php do_action( 'woocommerce_single_product_summary' ); ?>
	</div>
</section>
