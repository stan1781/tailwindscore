<?php
/**
 * Archive product card actions.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$actions   = isset( $args['actions'] ) && is_array( $args['actions'] ) ? $args['actions'] : array();
$primary   = isset( $actions['primary'] ) && is_array( $actions['primary'] ) ? $actions['primary'] : array();
$primary_html = isset( $primary['html'] ) ? (string) $primary['html'] : '';
$primary_type = isset( $primary['type'] ) ? (string) $primary['type'] : '';
$wishlist  = isset( $actions['wishlist_slot_html'] ) ? (string) $actions['wishlist_slot_html'] : '';
$quick     = isset( $actions['quick_slot_html'] ) ? (string) $actions['quick_slot_html'] : '';

if ( '' === $primary_html && '' === $wishlist && '' === $quick ) {
	return;
}
?>
<div class="ts-product-card__actions" data-ts-archive-actions>
	<?php if ( '' !== $primary_html ) : ?>
		<?php if ( 'add_to_cart' === $primary_type ) : ?>
			<?php tailwindscore_component( 'commerce/add-to-cart-button', array( 'inner_html' => $primary_html ) ); ?>
		<?php else : ?>
			<div class="ts-product-card__action-slot ts-product-card__action-slot--primary">
				<?php echo tailwindscore_kses_actions_slot( $primary_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( '' !== $wishlist ) : ?>
		<div class="ts-product-card__action-slot ts-product-card__action-slot--wishlist">
			<?php echo tailwindscore_kses_actions_slot( $wishlist ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>

	<?php if ( '' !== $quick ) : ?>
		<div class="ts-product-card__action-slot ts-product-card__action-slot--quick">
			<?php echo tailwindscore_kses_actions_slot( $quick ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</div>
