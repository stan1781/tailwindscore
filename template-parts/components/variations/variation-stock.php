<?php
/**
 * Optional static stock / fulfilment hint (WC availability still renders in variation template).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args {
 *   @type string $message Optional hint HTML (plain) — empty hides output.
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'message' => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$message = is_string( $args['message'] ) ? trim( $args['message'] ) : '';

/**
 * Filter optional merchant stock / shipping hint (SSR).
 *
 * @param string $message Plain text or minimal HTML.
 */
$message = apply_filters( 'tailwindscore/variation/stock_hint_message', $message );
?>
<div
	class="ts-variation-feedback"
	data-ts-variation-feedback
	data-feedback-validation-title="<?php echo esc_attr__( 'Review this selection', 'tailwindscore' ); ?>"
>
	<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
	<?php if ( '' !== $message ) : ?>
		<div class="ts-variation-stock-hint ts-trust-label" data-ts-variation-stock-hint>
			<?php echo wp_kses_post( $message ); ?>
		</div>
	<?php endif; ?>
</div>
