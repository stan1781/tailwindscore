<?php
/**
 * Order history card.
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

$order_number = $order->get_order_number();
$order_date   = $order->get_date_created();
$detail_id    = 'ts-order-detail-' . $order->get_id();
$actions      = wc_get_account_orders_actions( $order );
$quick_detail = tailwindscore_account_surface_text( 'order-quick-detail-label', __( 'Quick detail', 'tailwindscore' ) );
ob_start();
tailwindscore_account_part( 'order-detail', array( 'order' => $order ) );
$detail_html = trim( (string) ob_get_clean() );
?>
<article class="ts-account-order-card" data-ts-module="order-toggle">
	<div class="ts-account-order-card__summary">
		<div class="ts-account-order-card__lead">
			<p class="ts-account-order-card__eyebrow">
				<?php
				printf(
					/* translators: %s: order number */
					esc_html__( 'Order #%s', 'tailwindscore' ),
					esc_html( $order_number )
				);
				?>
			</p>
			<h2 class="ts-account-order-card__title">
				<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
			</h2>
			<p class="ts-account-order-card__meta">
				<?php
				printf(
					/* translators: 1: date, 2: item count, 3: total */
					esc_html__( '%1$s · %2$s · %3$s', 'tailwindscore' ),
					esc_html( $order_date ? wc_format_datetime( $order_date ) : __( 'Recent order', 'tailwindscore' ) ),
					esc_html( $order->get_item_count() . ' ' . _n( 'item', 'items', $order->get_item_count(), 'tailwindscore' ) ),
					wp_strip_all_tags( $order->get_formatted_order_total() )
				);
				?>
			</p>
		</div>

		<div class="ts-account-order-card__actions">
			<?php foreach ( $actions as $key => $action ) : ?>
				<a class="ts-btn <?php echo 'view' === $key ? 'ts-btn--primary' : 'ts-btn--secondary'; ?> ts-btn--sm" href="<?php echo esc_url( $action['url'] ); ?>">
					<?php echo esc_html( $action['name'] ); ?>
				</a>
			<?php endforeach; ?>

			<?php if ( '' !== $detail_html ) : ?>
				<button type="button" class="ts-account-order-card__toggle" aria-expanded="false" aria-controls="<?php echo esc_attr( $detail_id ); ?>" data-account-order-toggle>
					<?php echo esc_html( $quick_detail ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( '' !== $detail_html ) : ?>
		<div class="ts-account-order-card__detail" id="<?php echo esc_attr( $detail_id ); ?>" hidden data-account-order-detail>
			<?php echo $detail_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</article>
