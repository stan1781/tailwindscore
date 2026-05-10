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
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'inner_html' => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$html = is_string( $args['inner_html'] ) ? $args['inner_html'] : '';

?>
<div
	class="ts-commerce-atc"
	data-ts-module="commerce-add-to-cart"
	data-feedback-validation-title="<?php echo esc_attr__( 'Please review this selection', 'tailwindscore' ); ?>"
	data-feedback-loading-message="<?php echo esc_attr__( 'Adding to bag', 'tailwindscore' ); ?>"
	data-feedback-success-message="<?php echo esc_attr__( 'Added to bag', 'tailwindscore' ); ?>"
	data-feedback-error-message="<?php echo esc_attr__( 'We could not update the bag just now. Please try again.', 'tailwindscore' ); ?>"
	data-feedback-selection-message="<?php echo esc_attr__( 'Select an option before adding to bag.', 'tailwindscore' ); ?>"
>
	<?php tailwindscore_feedback_part( 'validation', array( 'hidden' => true ) ); ?>
	<?php tailwindscore_feedback_part( 'loading', array( 'hidden' => true, 'message' => __( 'Adding to bag', 'tailwindscore' ) ) ); ?>
	<?php echo tailwindscore_kses_actions_slot( $html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
