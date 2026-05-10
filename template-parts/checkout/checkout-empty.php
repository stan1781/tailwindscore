<?php
/**
 * Checkout unavailable state.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Template arguments.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$copy     = tailwindscore_feedback_empty_state_copy( 'checkout-unavailable' );
$headline = is_string( $args['headline'] ?? null ) ? $args['headline'] : ( $copy['title'] ?? '' );
$message  = is_string( $args['message'] ?? null ) ? $args['message'] : ( $copy['message'] ?? '' );
$cta_url  = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
?>
<section class="ts-checkout-shell ts-checkout-shell--empty">
	<header class="ts-checkout-shell__header">
		<p class="ts-checkout-shell__eyebrow"><?php echo esc_html( $copy['eyebrow'] ?? __( 'Purchase flow', 'tailwindscore' ) ); ?></p>
		<h1 class="ts-checkout-shell__title"><?php echo esc_html( $headline ); ?></h1>
		<p class="ts-checkout-shell__intro"><?php echo esc_html( $message ); ?></p>
	</header>
	<div>
		<a class="ts-btn ts-btn--primary" href="<?php echo esc_url( $cta_url ); ?>"><?php esc_html_e( 'Return to bag', 'tailwindscore' ); ?></a>
	</div>
</section>
