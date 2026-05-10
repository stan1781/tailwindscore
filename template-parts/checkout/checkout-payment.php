<?php
/**
 * Checkout payment section.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$available_gateways = is_array( $args['available_gateways'] ?? null ) ? $args['available_gateways'] : array();
$order_button_text  = is_string( $args['order_button_text'] ?? null ) ? $args['order_button_text'] : __( 'Place order', 'tailwindscore' );
$copy               = tailwindscore_checkout_surface_copy();
?>
<div id="payment" class="woocommerce-checkout-payment ts-checkout-payment" data-ts-module="checkout-payment">
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<p class="ts-checkout-payment__intro"><?php echo esc_html( (string) ( $copy['payment_intro'] ?? '' ) ); ?></p>

		<ul class="wc_payment_methods payment_methods methods ts-checkout-payment__methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					$method_id = 'payment_method_' . $gateway->id;
					$has_box   = $gateway->has_fields() || $gateway->get_description();
					?>
					<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> ts-checkout-payment__method<?php echo $gateway->chosen ? ' is-selected' : ''; ?>">
						<div class="ts-checkout-payment__choice">
							<input
								id="<?php echo esc_attr( $method_id ); ?>"
								type="radio"
								class="input-radio"
								name="payment_method"
								value="<?php echo esc_attr( $gateway->id ); ?>"
								<?php checked( $gateway->chosen, true ); ?>
								data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>"
							/>
							<label class="ts-checkout-payment__label" for="<?php echo esc_attr( $method_id ); ?>">
								<span><?php echo esc_html( $gateway->get_title() ); ?></span>
								<?php echo wp_kses_post( $gateway->get_icon() ); ?>
							</label>
						</div>

						<?php if ( $has_box ) : ?>
							<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?> <?php echo $gateway->chosen ? 'is-expanded' : 'is-collapsed'; ?>" <?php if ( ! $gateway->chosen ) : ?>hidden<?php endif; ?>>
								<?php $gateway->payment_fields(); ?>
							</div>
						<?php endif; ?>
					</li>
					<?php
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html( tailwindscore_content_surface_text( 'support-message', __( 'No payment methods are available for this order. Please review your details or contact us for help.', 'tailwindscore' ) ) ) : esc_html( tailwindscore_content_surface_text( 'checkout-reassurance-message', __( 'Enter your billing details to view available payment methods.', 'tailwindscore' ) ) ) ), 'notice' );
				echo '</li>';
			}
			?>
		</ul>
	<?php endif; ?>

	<?php if ( ! WC()->cart || ! WC()->cart->needs_payment() ) : ?>
		<p class="ts-checkout-payment__empty"><?php esc_html_e( 'No payment is needed for this order.', 'tailwindscore' ); ?></p>
	<?php endif; ?>

	<div class="form-row place-order">
		<noscript>
			<?php
			printf(
				esc_html__( 'Because your browser does not support JavaScript, totals may update only after you confirm with the %s button.', 'tailwindscore' ),
				esc_html__( 'Update totals', 'tailwindscore' )
			);
			?>
			<br />
			<button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'tailwindscore' ); ?>"><?php esc_html_e( 'Update totals', 'tailwindscore' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php
		echo apply_filters(
			'woocommerce_order_button_html',
			sprintf(
				'<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="%1$s" data-value="%1$s">%2$s</button>',
				esc_attr( $order_button_text ),
				esc_html( $order_button_text )
			)
		);
		?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>
