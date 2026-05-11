<?php
/**
 * Variable product experience: SSR hosts and runtime wrapper.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Wrap the variable add-to-cart form for the variation runtime host.
 */
function tailwindscore_variation_runtime_open_wrap(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	global $product;

	if ( ! is_a( $product, 'WC_Product', false ) || ! $product->is_type( 'variable' ) ) {
		return;
	}

	echo '<div class="ts-variation-runtime" data-ts-module="tailwindscore-variation-runtime">';
}

/**
 * Close the runtime wrapper after the variable form.
 */
function tailwindscore_variation_runtime_close_wrap(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	global $product;

	if ( ! is_a( $product, 'WC_Product', false ) || ! $product->is_type( 'variable' ) ) {
		return;
	}

	echo '</div>';
}

/**
 * Render progressive enhancement slots after the variation table.
 */
function tailwindscore_variation_experience_after_variations_table(): void {
	if ( ! apply_filters( 'tailwindscore/variation/experience', true ) ) {
		return;
	}

	if ( ! apply_filters( 'tailwindscore/variation/experience-ssr-slots', true ) ) {
		return;
	}

	$message = trim( (string) apply_filters( 'tailwindscore/variation/stock_hint_message', '' ) );
	?>
	<div class="ts-variation-price-state screen-reader-text" data-ts-variation-price-state aria-live="polite" aria-atomic="true"></div>
	<div
		class="ts-variation-feedback"
		data-ts-variation-feedback
		data-feedback-validation-title="<?php echo esc_attr__( 'Review this selection', 'tailwindscore' ); ?>"
		data-feedback-unavailable-message="<?php echo esc_attr__( 'This option is currently unavailable. Choose another combination.', 'tailwindscore' ); ?>"
		data-feedback-hidden-message="<?php echo esc_attr__( 'This combination is currently unavailable. Choose another option.', 'tailwindscore' ); ?>"
	>
		<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
		<?php if ( '' !== $message ) : ?>
			<div class="ts-variation-stock-hint ts-trust-label" data-ts-variation-stock-hint>
				<?php echo wp_kses_post( $message ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

add_action( 'woocommerce_before_add_to_cart_form', 'tailwindscore_variation_runtime_open_wrap', 0 );
add_action( 'woocommerce_after_add_to_cart_form', 'tailwindscore_variation_runtime_close_wrap', 99999 );
add_action( 'woocommerce_after_variations_table', 'tailwindscore_variation_experience_after_variations_table', 4 );
