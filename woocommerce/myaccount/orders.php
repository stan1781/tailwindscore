<?php
/**
 * Premium account orders.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$has_orders   = isset( $has_orders ) ? (bool) $has_orders : false;
$current_page = isset( $current_page ) ? max( 1, (int) $current_page ) : 1;
$browse_label = tailwindscore_account_surface_text( 'orders-empty-action-label', __( 'Browse products', 'tailwindscore' ) );
?>
<section class="ts-account-panel ts-account-orders" aria-label="<?php esc_attr_e( 'Orders', 'tailwindscore' ); ?>">
	<?php if ( $has_orders && isset( $customer_orders->orders ) && is_array( $customer_orders->orders ) ) : ?>
		<div class="ts-account-orders__list">
			<?php foreach ( $customer_orders->orders as $customer_order ) : ?>
				<?php
				$order = wc_get_order( $customer_order );
				if ( ! $order instanceof WC_Order ) {
					continue;
				}
				tailwindscore_account_part( 'order-card', array( 'order' => $order ) );
				?>
			<?php endforeach; ?>
		</div>

		<?php if ( 1 < (int) $customer_orders->max_num_pages ) : ?>
			<nav class="ts-account-pagination" aria-label="<?php esc_attr_e( 'Orders pagination', 'tailwindscore' ); ?>">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="ts-btn ts-btn--secondary ts-btn--sm" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">
						<?php esc_html_e( 'Previous', 'tailwindscore' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="ts-btn ts-btn--secondary ts-btn--sm" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">
						<?php esc_html_e( 'Next', 'tailwindscore' ); ?>
					</a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<?php
		tailwindscore_account_part(
			'account-empty',
			array(
				'context'      => 'orders',
				'actions_html' => sprintf(
					'<a class="ts-btn ts-btn--secondary ts-btn--sm" href="%s">%s</a>',
					esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ),
					esc_html( $browse_label )
				),
			)
		);
		?>
	<?php endif; ?>
</section>
