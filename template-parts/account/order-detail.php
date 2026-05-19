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
$copy  = tailwindscore_account_order_detail_copy();
?>
<div class="ts-order-detail">
	<?php if ( ! empty( $items ) ) : ?>
		<div class="ts-order-detail__group">
			<h3 class="ts-order-detail__heading"><?php echo esc_html( (string) ( $copy['items_heading'] ?? '' ) ); ?></h3>
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
									esc_html( (string) ( $copy['quantity_format'] ?? '' ) ),
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
			<h3 class="ts-order-detail__heading"><?php echo esc_html( (string) ( $copy['delivery_heading'] ?? '' ) ); ?></h3>
			<dl class="ts-order-detail__meta">
				<div>
					<dt><?php echo esc_html( (string) ( $copy['shipping_method_label'] ?? '' ) ); ?></dt>
					<dd><?php echo esc_html( $order->get_shipping_method() ?: ( $copy['no_shipping_method'] ?? '' ) ); ?></dd>
				</div>
				<div>
					<dt><?php echo esc_html( (string) ( $copy['shipping_address_label'] ?? '' ) ); ?></dt>
					<dd><?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: ( $copy['no_shipping_address'] ?? '' ) ); ?></dd>
				</div>
			</dl>
		</div>

		<div class="ts-order-detail__group">
			<h3 class="ts-order-detail__heading"><?php echo esc_html( (string) ( $copy['payment_heading'] ?? '' ) ); ?></h3>
			<dl class="ts-order-detail__meta">
				<div>
					<dt><?php echo esc_html( (string) ( $copy['payment_method_label'] ?? '' ) ); ?></dt>
					<dd><?php echo esc_html( $order->get_payment_method_title() ?: ( $copy['payment_method_pending'] ?? '' ) ); ?></dd>
				</div>
				<div>
					<dt><?php echo esc_html( (string) ( $copy['billing_address_label'] ?? '' ) ); ?></dt>
					<dd><?php echo wp_kses_post( $order->get_formatted_billing_address() ?: ( $copy['no_billing_address'] ?? '' ) ); ?></dd>
				</div>
			</dl>
		</div>
	</div>
</div>
