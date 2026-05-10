<?php
/**
 * Cart summary block.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;
?>
<div class="ts-cart-summary">
	<div class="ts-cart-summary__row">
		<span class="ts-cart-summary__label"><?php esc_html_e( 'Subtotal', 'tailwindscore' ); ?></span>
		<span class="ts-cart-summary__value" data-cart-subtotal><?php echo wp_kses_post( (string) ( $args['subtotal_html'] ?? '' ) ); ?></span>
	</div>
	<p class="ts-cart-summary__note"><?php esc_html_e( 'Shipping and taxes calculated at checkout.', 'tailwindscore' ); ?></p>
	<div class="ts-cart-summary__actions">
		<a class="ts-btn ts-btn--primary" href="<?php echo esc_url( (string) ( $args['checkout_url'] ?? '#' ) ); ?>"><?php esc_html_e( 'Checkout', 'tailwindscore' ); ?></a>
		<a class="ts-btn ts-btn--ghost" href="<?php echo esc_url( (string) ( $args['cart_url'] ?? '#' ) ); ?>"><?php esc_html_e( 'View cart', 'tailwindscore' ); ?></a>
	</div>
</div>
