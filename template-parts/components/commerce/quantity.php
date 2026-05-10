<?php
/**
 * Quantity control — SSR markup compatible with `.ts-qty` / WC `.quantity .qty`; mounts `commerce-quantity`.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args {
 *   @type string $input_id       Input id attribute.
 *   @type string $input_name     Name attribute (required for WC forms).
 *   @type string $input_value    Current value.
 *   @type string $min            Min (optional).
 *   @type string $max            Max (optional).
 *   @type string $step           Step (optional).
 *   @type bool   $show_buttons  Render +/- buttons.
 *   @type array  $attributes    Extra attributes on input (sanitized keys).
 * }
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'input_id'       => '',
	'input_name'     => 'quantity',
	'input_value'    => '1',
	'min'            => '1',
	'max'            => '',
	'step'           => '1',
	'show_buttons'   => true,
	'attributes'     => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$id = is_string( $args['input_id'] ) ? sanitize_key( $args['input_id'] ) : '';

$name = is_string( $args['input_name'] ) ? sanitize_key( $args['input_name'] ) : 'quantity';

$value = is_string( $args['input_value'] ) ? $args['input_value'] : '1';

$input_attrs = array(
	'type'  => 'number',
	'class' => 'qty input-text',
	'name'  => $name,
	'value' => $value,
);

if ( '' !== $id ) {
	$input_attrs['id'] = $id;
}

$min = is_string( $args['min'] ) ? $args['min'] : '';
if ( '' !== $min ) {
	$input_attrs['min'] = $min;
}

$max = is_string( $args['max'] ) ? $args['max'] : '';
if ( '' !== $max ) {
	$input_attrs['max'] = $max;
}

$step = is_string( $args['step'] ) ? $args['step'] : '';
if ( '' !== $step ) {
	$input_attrs['step'] = $step;
}

if ( is_array( $args['attributes'] ) ) {
	foreach ( $args['attributes'] as $key => $val ) {
		$key = sanitize_key( (string) $key );
		if ( '' === $key || 'class' === $key ) {
			continue;
		}
		if ( null === $val || false === $val ) {
			continue;
		}
		if ( true === $val ) {
			$input_attrs[ $key ] = true;
			continue;
		}
		$input_attrs[ $key ] = is_scalar( $val ) ? (string) $val : '';
	}
}

$input_html = tailwindscore_attributes_html( $input_attrs );

$show_buttons = (bool) $args['show_buttons'];

?>
<div class="ts-qty quantity" data-ts-module="commerce-quantity">
	<?php if ( $show_buttons ) : ?>
		<button type="button" class="minus ts-qty__btn" aria-label="<?php esc_attr_e( 'Decrease quantity', 'tailwindscore' ); ?>">−</button>
	<?php endif; ?>
	<input<?php echo $input_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
	<?php if ( $show_buttons ) : ?>
		<button type="button" class="plus ts-qty__btn" aria-label="<?php esc_attr_e( 'Increase quantity', 'tailwindscore' ); ?>">+</button>
	<?php endif; ?>
</div>
