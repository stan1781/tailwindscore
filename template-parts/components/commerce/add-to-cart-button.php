<?php
/**
 * Add-to-cart interaction host — wraps SSR slot; mounts `commerce-add-to-cart`.
 *
 * Place around `form.cart` / `form.variations_form` region (single product or loop).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args {
 *   @type string $inner_html Raw HTML inside wrapper (buttons/forms from WC).
 *   @type array<string, string> $feedback Optional feedback copy overrides.
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'inner_html' => '',
	'feedback'   => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$html     = is_string( $args['inner_html'] ) ? $args['inner_html'] : '';
$feedback = isset( $args['feedback'] ) && is_array( $args['feedback'] ) ? $args['feedback'] : array();

$feedback_attrs = array(
	'validation_title' => 'data-feedback-validation-title',
	'loading_message'  => 'data-feedback-loading-message',
	'success_message'  => 'data-feedback-success-message',
	'error_message'    => 'data-feedback-error-message',
	'selection_message'=> 'data-feedback-selection-message',
);

?>
<div
	class="ts-commerce-atc"
	data-ts-module="commerce-add-to-cart"
	<?php foreach ( $feedback_attrs as $key => $attribute ) : ?>
		<?php if ( ! empty( $feedback[ $key ] ) && is_string( $feedback[ $key ] ) ) : ?>
			<?php echo $attribute; ?>="<?php echo esc_attr( $feedback[ $key ] ); ?>"
		<?php endif; ?>
	<?php endforeach; ?>
>
	<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
	<?php tailwindscore_feedback_part( 'loading', array( 'hidden' => true, 'message' => is_string( $feedback['loading_message'] ?? null ) ? $feedback['loading_message'] : __( 'Adding to bag', 'tailwindscore' ) ) ); ?>
	<?php echo tailwindscore_kses_actions_slot( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
