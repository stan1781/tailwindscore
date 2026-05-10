<?php
/**
 * Single product summary — price wrapper (delegates to `price` component).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Same shape as `tailwindscore_adapter_price_props`.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$args = wp_parse_args( (array) ( $args ?? array() ), array() );

if ( empty( $args ) ) {
	return;
}

$wrap = tailwindscore_component_classes(
	array( 'ts-product-summary__price' ),
	$args,
	'product-summary-price'
);

?>
<div class="<?php echo esc_attr( $wrap ); ?>">
	<?php tailwindscore_component( 'price', $args ); ?>
</div>
