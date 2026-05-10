<?php
/**
 * Checkout order review summary.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
?>
<section class="ts-checkout-summary woocommerce-checkout-review-order-table" data-ts-module="checkout-summary" data-checkout-summary>
	<p class="ts-checkout-summary__header">
		<?php esc_html_e( 'Items in your order', 'tailwindscore' ); ?>
		<span data-checkout-summary-count aria-hidden="true"><?php echo esc_html( (string) count( WC()->cart->get_cart() ) ); ?></span>
	</p>
	<p class="ts-checkout-summary__note"><?php echo esc_html( tailwindscore_checkout_surface_copy()['summary_note'] ?? '' ); ?></p>

	<table class="ts-checkout-summary__table shop_table woocommerce-checkout-review-order-table">
		<thead>
			<tr>
				<th class="product-name"><?php esc_html_e( 'Product', 'tailwindscore' ); ?></th>
				<th class="product-total"><?php esc_html_e( 'Subtotal', 'tailwindscore' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( ! $_product || ! $_product->exists() || 0 >= (int) $cart_item['quantity'] || ! apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					continue;
				}
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<th class="product-name">
						<div class="ts-checkout-summary__product">
							<span class="ts-checkout-summary__product-name"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?></span>
							<span class="ts-checkout-summary__qty"><?php printf( esc_html__( 'Quantity %d', 'tailwindscore' ), (int) $cart_item['quantity'] ); ?></span>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</th>
					<td class="product-total">
						<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ) ); ?>
					</td>
				</tr>
				<?php
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
		<tfoot>
			<tr class="cart-subtotal">
				<th><?php esc_html_e( 'Subtotal', 'tailwindscore' ); ?></th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<tr class="shipping">
					<th><?php esc_html_e( 'Shipping', 'tailwindscore' ); ?></th>
					<td><?php wc_cart_totals_shipping_html(); ?></td>
				</tr>
				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $fee->name ); ?></th>
					<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
				<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<th><?php echo esc_html( $tax->label ); ?></th>
							<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total">
				<th><?php esc_html_e( 'Total', 'tailwindscore' ); ?></th>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
		</tfoot>
	</table>
</section>
