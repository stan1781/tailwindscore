<?php
/**
 * Cart drawer line item.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$copy = tailwindscore_cart_surface_copy();
?>
<article class="ts-cart-line-item" data-cart-item-key="<?php echo esc_attr( (string) ( $args['key'] ?? '' ) ); ?>">
	<a class="ts-cart-line-item__media" href="<?php echo esc_url( (string) ( $args['url'] ?? '#' ) ); ?>">
		<?php if ( '' !== (string) ( $args['image'] ?? '' ) ) : ?>
			<img src="<?php echo esc_url( (string) $args['image'] ); ?>" alt="" loading="lazy">
		<?php else : ?>
			<span class="ts-cart-line-item__placeholder" aria-hidden="true"><?php echo tailwindscore_icon( 'bag', array( 'class' => 'ts-icon--sm' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<?php endif; ?>
	</a>

	<div class="ts-cart-line-item__content">
		<?php if ( '' !== (string) ( $args['category'] ?? '' ) ) : ?>
			<p class="ts-cart-line-item__eyebrow"><?php echo esc_html( (string) $args['category'] ); ?></p>
		<?php endif; ?>
		<h3 class="ts-cart-line-item__title">
			<a href="<?php echo esc_url( (string) ( $args['url'] ?? '#' ) ); ?>"><?php echo esc_html( (string) ( $args['title'] ?? '' ) ); ?></a>
		</h3>
		<div class="ts-cart-line-item__price">
			<?php
			tailwindscore_component(
				'price',
				array(
					'price_html' => (string) ( $args['price_html'] ?? '' ),
				)
			);
			?>
		</div>

		<div class="ts-cart-line-item__footer">
			<div class="ts-cart-line-item__qty-block" data-cart-item-key="<?php echo esc_attr( (string) ( $args['key'] ?? '' ) ); ?>">
				<span class="ts-cart-line-item__qty-label"><?php esc_html_e( 'Quantity', 'tailwindscore' ); ?></span>
				<div class="ts-cart-line-item__qty">
					<button type="button" class="ts-cart-line-item__qty-btn minus" data-cart-qty-step="-1" aria-label="<?php esc_attr_e( 'Decrease quantity', 'tailwindscore' ); ?>">-</button>
					<input
						class="ts-cart-line-item__qty-input qty"
						type="number"
						min="0"
						<?php if ( (int) ( $args['max_qty'] ?? 0 ) > 0 ) : ?>max="<?php echo esc_attr( (string) (int) $args['max_qty'] ); ?>"<?php endif; ?>
						step="1"
						value="<?php echo esc_attr( (string) (int) ( $args['quantity'] ?? 0 ) ); ?>"
						data-cart-qty-input
					>
					<button type="button" class="ts-cart-line-item__qty-btn plus" data-cart-qty-step="1" aria-label="<?php esc_attr_e( 'Increase quantity', 'tailwindscore' ); ?>">+</button>
				</div>
			</div>
			<div class="ts-cart-line-item__meta">
				<div class="ts-cart-line-item__subtotal-block">
					<span class="ts-cart-line-item__subtotal-label"><?php echo esc_html( (string) ( $copy['line_item_subtotal_label'] ?? '' ) ); ?></span>
					<span class="ts-cart-line-item__subtotal"><?php echo wp_kses_post( (string) ( $args['subtotal'] ?? '' ) ); ?></span>
				</div>
				<a class="ts-cart-line-item__remove" href="<?php echo esc_url( (string) ( $args['remove_url'] ?? '#' ) ); ?>" data-cart-remove><?php esc_html_e( 'Remove', 'tailwindscore' ); ?></a>
			</div>
		</div>
	</div>
</article>
