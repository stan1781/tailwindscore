<?php
/**
 * Premium account single order view.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ?? 0 );

if ( ! $order instanceof WC_Order ) {
	return;
}

$created_at = $order->get_date_created();
$account_copy = tailwindscore_account_template_copy();
?>
<section class="ts-account-panel ts-account-order-view">
	<div class="ts-account-order-card ts-account-order-card--static">
		<div class="ts-account-order-card__summary">
			<div class="ts-account-order-card__lead">
				<p class="ts-account-order-card__eyebrow">
					<?php
					printf(
						/* translators: %s: order number */
						esc_html__( 'Order #%s', 'tailwindscore' ),
						esc_html( $order->get_order_number() )
					);
					?>
				</p>
				<h2 class="ts-account-order-card__title"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></h2>
				<p class="ts-account-order-card__meta"><?php echo esc_html( $created_at ? wc_format_datetime( $created_at ) : __( 'Recent order', 'tailwindscore' ) ); ?> · <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
			</div>
			<div class="ts-account-order-card__actions">
				<a class="ts-btn ts-btn--secondary ts-btn--sm" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
					<?php echo esc_html( $account_copy['view_order_back_label'] ); ?>
				</a>
			</div>
		</div>

		<div class="ts-account-order-card__detail is-static">
			<?php tailwindscore_account_part( 'order-detail', array( 'order' => $order ) ); ?>
		</div>
	</div>

	<div class="ts-account-order-native">
		<?php do_action( 'woocommerce_view_order', $order_id ); ?>
	</div>
</section>
