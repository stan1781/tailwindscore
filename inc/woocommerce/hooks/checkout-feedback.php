<?php
/**
 * Checkout field-level feedback integration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Open checkout feedback runtime wrapper.
 */
function tailwindscore_checkout_feedback_open_wrap(): void {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	?>
	<div
		class="ts-checkout-feedback"
		data-ts-module="checkout-feedback"
		data-feedback-validation-title="<?php echo esc_attr( tailwindscore_checkout_surface_copy()['validation_title'] ?? __( 'Please review your checkout details', 'tailwindscore' ) ); ?>"
		data-feedback-validation-summary="<?php echo esc_attr( tailwindscore_checkout_surface_copy()['validation_summary'] ?? __( 'Please review the highlighted checkout details.', 'tailwindscore' ) ); ?>"
		data-feedback-loading-message="<?php echo esc_attr( tailwindscore_checkout_surface_copy()['loading_message'] ?? __( 'Updating checkout', 'tailwindscore' ) ); ?>"
	>
		<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
		<?php tailwindscore_feedback_part( 'loading', array( 'hidden' => true, 'message' => tailwindscore_checkout_surface_copy()['loading_message'] ?? __( 'Updating checkout', 'tailwindscore' ) ) ); ?>
	<?php
}

/**
 * Close checkout feedback runtime wrapper.
 */
function tailwindscore_checkout_feedback_close_wrap(): void {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}
	echo '</div>';
}

add_action( 'woocommerce_before_checkout_form', 'tailwindscore_checkout_feedback_open_wrap', 12 );
add_action( 'woocommerce_after_checkout_form', 'tailwindscore_checkout_feedback_close_wrap', 999 );

add_filter(
	'woocommerce_form_field_args',
	static function ( array $args, string $key, $value ): array {
		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return $args;
		}

		$args['class']       = is_array( $args['class'] ?? null ) ? $args['class'] : array();
		$args['input_class'] = is_array( $args['input_class'] ?? null ) ? $args['input_class'] : array();
		$args['label_class'] = is_array( $args['label_class'] ?? null ) ? $args['label_class'] : array();

		$args['class'][] = 'ts-checkout-field';

		if ( ! in_array( (string) ( $args['type'] ?? '' ), array( 'checkbox', 'radio' ), true ) ) {
			$args['class'][]       = 'ts-field';
			$args['label_class'][] = 'ts-label';
		}

		if ( 'select' === ( $args['type'] ?? '' ) ) {
			$args['input_class'][] = 'ts-select';
		} elseif ( 'textarea' === ( $args['type'] ?? '' ) ) {
			$args['input_class'][] = 'ts-textarea';
		} elseif ( ! in_array( (string) ( $args['type'] ?? '' ), array( 'checkbox', 'radio', 'hidden' ), true ) ) {
			$args['input_class'][] = 'ts-input';
		}

		$args['custom_attributes']                         = is_array( $args['custom_attributes'] ?? null ) ? $args['custom_attributes'] : array();
		$args['custom_attributes']['data-feedback-field'] = $key;

		if ( ! empty( $args['label'] ) && is_string( $args['label'] ) ) {
			$args['custom_attributes']['data-feedback-label'] = wp_strip_all_tags( $args['label'] );
		}

		if ( ! empty( $args['required'] ) ) {
			$args['custom_attributes']['data-feedback-required'] = 'true';
		}

		return $args;
	},
	10,
	3
);

add_filter(
	'woocommerce_get_availability_text',
	static function ( string $text, $product ): string {
		if ( ! $product instanceof WC_Product_Variation ) {
			return $text;
		}

		if ( ! $product->is_in_stock() ) {
			return __( 'Currently unavailable', 'tailwindscore' );
		}

		if ( $product->backorders_allowed() && $product->is_on_backorder( 1 ) ) {
			return __( 'Available on backorder', 'tailwindscore' );
		}

		$quantity = $product->get_stock_quantity();
		$threshold = (int) apply_filters( 'tailwindscore/variation/low_stock_threshold', 3, $product );

		if ( is_numeric( $quantity ) && (int) $quantity > 0 && (int) $quantity <= $threshold ) {
			return sprintf(
				/* translators: %d: remaining stock quantity */
				_n( 'Only %d left for this option', 'Only %d left for this option', (int) $quantity, 'tailwindscore' ),
				(int) $quantity
			);
		}

		return __( 'Available to order', 'tailwindscore' );
	},
	10,
	2
);
