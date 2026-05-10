<?php
/**
 * Premium cart drawer surface.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$payload = wp_parse_args(
	(array) ( $args ?? array() ),
	array(
		'count'         => tailwindscore_cart_surface_count(),
		'items'         => tailwindscore_cart_surface_items(),
		'subtotal_html' => '',
		'cart_url'      => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
		'checkout_url'  => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' ),
		'shop_url'      => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
		'is_empty'      => 0 === tailwindscore_cart_surface_count(),
	)
);

$drawer_id = 'ts-cart-drawer';
$copy      = tailwindscore_cart_surface_copy();
?>
<div
	id="<?php echo esc_attr( $drawer_id ); ?>"
	class="ts-cart-drawer"
	data-ts-module="cart-drawer"
	data-cart-endpoint="<?php echo esc_url( tailwindscore_cart_surface_endpoint_url() ); ?>"
	hidden
>
	<div class="ts-cart-drawer__backdrop" data-cart-close></div>
	<aside class="ts-cart-drawer__panel" role="dialog" aria-modal="true" aria-labelledby="ts-cart-drawer-title" data-ts-module="cart-focus">
		<div class="ts-cart-drawer__header">
			<div class="ts-cart-drawer__heading">
				<p class="ts-cart-drawer__eyebrow"><?php esc_html_e( 'Bag', 'tailwindscore' ); ?></p>
				<h2 id="ts-cart-drawer-title" class="ts-cart-drawer__title"><?php esc_html_e( 'Cart', 'tailwindscore' ); ?></h2>
			</div>
			<button class="ts-cart-drawer__close ts-icon-button ts-icon-button--utility" type="button" data-cart-close>
				<span class="screen-reader-text"><?php esc_html_e( 'Close cart', 'tailwindscore' ); ?></span>
				<?php echo tailwindscore_icon( 'close', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>

		<div
			class="ts-cart-drawer__body"
			data-cart-surface-root
			data-feedback-validation-title="<?php echo esc_attr( $copy['validation_title'] ?? __( 'Please review your bag', 'tailwindscore' ) ); ?>"
			data-feedback-loading-message="<?php echo esc_attr( $copy['loading_message'] ?? __( 'Updating bag', 'tailwindscore' ) ); ?>"
			data-feedback-update-error-message="<?php echo esc_attr( $copy['update_error'] ?? __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ) ); ?>"
			data-feedback-item-updated-message="<?php echo esc_attr( $copy['item_updated'] ?? __( 'Bag updated', 'tailwindscore' ) ); ?>"
			data-feedback-item-removed-message="<?php echo esc_attr( $copy['item_removed'] ?? __( 'Removed from bag', 'tailwindscore' ) ); ?>"
		>
			<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
			<?php tailwindscore_feedback_part( 'loading', array( 'hidden' => true, 'message' => $copy['loading_message'] ?? __( 'Updating bag', 'tailwindscore' ) ) ); ?>
			<?php if ( ! empty( $payload['is_empty'] ) ) : ?>
				<?php echo tailwindscore_cart_surface_render( 'cart-empty', $payload ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="ts-cart-drawer__items">
					<?php foreach ( (array) $payload['items'] as $item ) : ?>
						<?php echo tailwindscore_cart_surface_render( 'cart-line-item', (array) $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endforeach; ?>
				</div>
				<div class="ts-cart-drawer__summary">
					<?php echo tailwindscore_cart_surface_render( 'cart-summary', $payload ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	</aside>
</div>
