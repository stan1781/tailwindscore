<?php
/**
 * Cart trigger.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'label'      => __( 'Cart', 'tailwindscore' ),
	'target'     => 'ts-cart-drawer',
	'class'      => '',
	'context'    => 'utility',
	'show_label' => true,
	'count'      => tailwindscore_cart_surface_count(),
);
$args       = wp_parse_args( (array) ( $args ?? array() ), $defaults );
$classes    = array_filter( array( 'ts-cart-trigger', 'ts-cart-trigger--' . sanitize_html_class( (string) $args['context'] ), sanitize_html_class( (string) $args['class'] ) ) );
?>
<button
	type="button"
	class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	data-cart-trigger="<?php echo esc_attr( (string) $args['target'] ); ?>"
	aria-controls="<?php echo esc_attr( (string) $args['target'] ); ?>"
	aria-expanded="false"
>
	<span class="ts-cart-trigger__icon ts-commerce-icon ts-commerce-icon--utility" aria-hidden="true">
		<?php echo tailwindscore_icon( 'bag', array( 'class' => 'ts-icon--utility' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>
	<?php if ( (bool) $args['show_label'] ) : ?>
		<span class="ts-cart-trigger__label"><?php echo esc_html( (string) $args['label'] ); ?></span>
	<?php else : ?>
		<span class="screen-reader-text"><?php echo esc_html( (string) $args['label'] ); ?></span>
	<?php endif; ?>
	<span class="ts-cart-trigger__badge" data-cart-count <?php echo ( (int) $args['count'] > 0 ) ? '' : 'hidden'; ?>><?php echo esc_html( (string) (int) $args['count'] ); ?></span>
</button>
