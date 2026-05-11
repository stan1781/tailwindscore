<?php
/**
 * Checkout form layout.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$checkout = $args['checkout'] ?? null;
$copy     = tailwindscore_checkout_surface_copy();

if ( ! $checkout instanceof WC_Checkout ) {
	return;
}
?>
<form
	name="checkout"
	method="post"
	class="checkout woocommerce-checkout ts-checkout-form"
	action="<?php echo esc_url( wc_get_checkout_url() ); ?>"
	enctype="multipart/form-data"
>
	<section
		class="ts-checkout-shell"
		data-ts-module="checkout-loading"
		data-checkout-loading-message="<?php echo esc_attr( $copy['loading_message'] ?? __( 'Refreshing your order', 'tailwindscore' ) ); ?>"
	>
		<?php tailwindscore_feedback_part( 'loading', array( 'hidden' => true, 'message' => $copy['loading_message'] ?? __( 'Refreshing your order', 'tailwindscore' ) ) ); ?>

		<header class="ts-checkout-shell__header">
			<p class="ts-checkout-shell__eyebrow"><?php echo esc_html( (string) ( $copy['eyebrow'] ?? __( 'Secure purchase', 'tailwindscore' ) ) ); ?></p>
			<h1 class="ts-checkout-shell__title"><?php echo esc_html( (string) ( $copy['title'] ?? __( 'Checkout', 'tailwindscore' ) ) ); ?></h1>
			<div class="ts-checkout-shell__support" aria-label="<?php esc_attr_e( 'Checkout guidance', 'tailwindscore' ); ?>">
				<?php foreach ( (array) ( $copy['support_items'] ?? array() ) as $item ) : ?>
					<?php if ( is_string( $item ) && '' !== trim( $item ) ) : ?>
						<span><?php echo esc_html( $item ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</header>

		<div class="ts-checkout-shell__body">
			<div class="ts-checkout-shell__main">
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="col2-set" id="customer_details">
					<div class="col-1">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<?php if ( WC()->cart && WC()->cart->needs_shipping_address() ) : ?>
						<div class="col-2">
							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>
					<?php endif; ?>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
			</div>

			<aside class="ts-checkout-shell__aside">
				<div class="ts-checkout-shell__aside-head">
					<h2 id="order_review_heading"><?php esc_html_e( 'Order review', 'tailwindscore' ); ?></h2>
					<p class="ts-checkout-shell__aside-copy"><?php echo esc_html( (string) ( $copy['review_intro'] ?? '' ) ); ?></p>
				</div>

				<div class="ts-checkout-shell__focus-anchor" data-ts-module="checkout-focus" aria-hidden="true"></div>
				<div id="order_review" class="woocommerce-checkout-review-order">
					<?php do_action( 'woocommerce_checkout_order_review' ); ?>
				</div>
			</aside>
		</div>
	</section>
</form>
