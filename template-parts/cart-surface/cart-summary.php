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

$summary_copy = is_array( $args['summary_copy'] ?? null ) ? $args['summary_copy'] : array();
?>
<div class="ts-cart-summary">
	<div class="ts-cart-summary__row">
		<span class="ts-cart-summary__label"><?php echo esc_html( (string) ( $summary_copy['subtotal_label'] ?? '' ) ); ?></span>
		<span class="ts-cart-summary__value" data-cart-subtotal><?php echo wp_kses_post( (string) ( $args['subtotal_html'] ?? '' ) ); ?></span>
	</div>
	<p class="ts-cart-summary__note"><?php echo esc_html( (string) ( $summary_copy['summary_note'] ?? '' ) ); ?></p>
	<div class="ts-cart-summary__actions">
		<a class="ts-btn ts-btn--primary" href="<?php echo esc_url( (string) ( $args['checkout_url'] ?? '#' ) ); ?>"><?php echo esc_html( (string) ( $summary_copy['checkout_label'] ?? '' ) ); ?></a>
		<a class="ts-btn ts-btn--ghost" href="<?php echo esc_url( (string) ( $args['cart_url'] ?? '#' ) ); ?>"><?php echo esc_html( (string) ( $summary_copy['view_cart_label'] ?? '' ) ); ?></a>
	</div>
</div>
