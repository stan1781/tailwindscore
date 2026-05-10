<?php
/**
 * Order detail summary content.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$order = $args['order'] ?? null;

if ( ! $order instanceof WC_Order ) {
	return;
}

$items = $order->get_items();
?>
<div class="ts-order-detail">
	<?php if ( ! empty( $items ) ) : ?>
		<div class="ts-order-detail__group">
			<h3 class="ts-order-detail__heading"><?php esc_html_e( 'Items', 'tailwindscore' ); ?></h3>
			<ul class="ts-order-detail__items">
				<?php foreach ( $items as $item ) : ?>
					<?php if ( ! $item instanceof WC_Order_Item_Product ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<li class="ts-order-detail__item">
						<div>
							<p class="ts-order-detail__item-name"><?php echo esc_html( $item->get_name() ); ?></p>
							<p class="ts-order-detail__item-meta">
								<?php
								printf(
									/* translators: %d: quantity */
									esc_html__( 'Quantity %d', 'tailwindscore' ),
									(int) $item->get_quantity()
								);
								?>
							</p>
						</div>
						<p class="ts-order-detail__item-total"><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<div class="ts-order-detail__meta-grid">
		<div class="ts-order-detail__group">
			<h3 class="ts-order-detail__heading"><?php esc_html_e( 'Delivery', 'tailwindscore' ); ?></h3>
			<dl class="ts-order-detail__meta">
				<div>
					<dt><?php esc_html_e( 'Shipping method', 'tailwindscore' ); ?></dt>
					<dd><?php echo esc_html( $order->get_shipping_method() ?: __( 'No shipping required', 'tailwindscore' ) ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Shipping address', 'tailwindscore' ); ?></dt>
					<dd><?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: __( 'No shipping address provided.', 'tailwindscore' ) ); ?></dd>
				</div>
			</dl>
		</div>

		<div class="ts-order-detail__group">
			<h3 class="ts-order-detail__heading"><?php esc_html_e( 'Payment', 'tailwindscore' ); ?></h3>
			<dl class="ts-order-detail__meta">
				<div>
					<dt><?php esc_html_e( 'Method', 'tailwindscore' ); ?></dt>
					<dd><?php echo esc_html( $order->get_payment_method_title() ?: __( 'To be confirmed', 'tailwindscore' ) ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'Billing address', 'tailwindscore' ); ?></dt>
					<dd><?php echo wp_kses_post( $order->get_formatted_billing_address() ?: __( 'No billing address provided.', 'tailwindscore' ) ); ?></dd>
				</div>
			</dl>
		</div>
	</div>
</div>
