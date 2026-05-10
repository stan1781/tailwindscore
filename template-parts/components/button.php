<?php
/**
 * Button component — `.ts-btn` variants only (no Tailwind utilities).
 *
 * Responsibility: render accessible button or anchor with loading/disabled/icon variants.
 *
 * Unsupported: dropdown menus, split buttons, toggle groups (use dedicated components later).
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Arguments from {@see tailwindscore_component()} / get_template_part.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'variant'       => 'primary',
	'size'          => 'md',
	'label'         => '',
	'href'          => '',
	'type'          => 'button',
	'name'          => '',
	'value'         => '',
	'icon_name'     => '',
	'icon_args'     => array(),
	'icon_html'     => '',
	'icon_position' => 'start',
	'loading'       => false,
	'disabled'      => false,
	'aria_label'    => '',
	'attributes'    => array(),
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$variants       = array( 'primary', 'secondary', 'ghost' );
$sizes          = array( 'sm', 'md', 'lg' );
$types          = array( 'button', 'submit', 'reset' );
$icon_positions = array( 'start', 'end' );

$variant = in_array( $args['variant'], $variants, true ) ? $args['variant'] : 'primary';
$size    = in_array( $args['size'], $sizes, true ) ? $args['size'] : 'md';
$type    = in_array( $args['type'], $types, true ) ? $args['type'] : 'button';
$icon_pos = in_array( $args['icon_position'], $icon_positions, true ) ? $args['icon_position'] : 'start';

$href = is_string( $args['href'] ) ? esc_url( $args['href'] ) : '';

$label      = is_string( $args['label'] ) ? $args['label'] : '';
$icon_html  = is_string( $args['icon_html'] ) ? tailwindscore_kses_icon_html( $args['icon_html'] ) : '';
$loading    = (bool) $args['loading'];
$disabled   = (bool) $args['disabled'];
$aria_label = is_string( $args['aria_label'] ) ? sanitize_text_field( $args['aria_label'] ) : '';

if ( '' === $icon_html && is_string( $args['icon_name'] ) && '' !== $args['icon_name'] ) {
	$icon_args = is_array( $args['icon_args'] ) ? $args['icon_args'] : array();
	if ( ! isset( $icon_args['decorative'] ) ) {
		$icon_args['decorative'] = true;
	}
	if ( ! isset( $icon_args['class'] ) ) {
		$icon_args['class'] = 'ts-icon--inline';
	}
	$icon_html = tailwindscore_icon( sanitize_title( $args['icon_name'] ), $icon_args );
}

$is_icon_only = '' !== $icon_html && '' === trim( $label );
if ( $is_icon_only && '' === $aria_label ) {
	$aria_label = __( 'Button', 'tailwindscore' );
}

$name  = is_string( $args['name'] ) ? sanitize_key( $args['name'] ) : '';
$value = is_string( $args['value'] ) ? $args['value'] : '';

$classes = array( 'ts-btn', 'ts-btn--' . $variant );

if ( 'md' !== $size ) {
	$classes[] = 'ts-btn--' . $size;
}

if ( $loading ) {
	$classes[] = 'ts-btn--loading';
}

if ( $is_icon_only ) {
	$classes[] = 'ts-btn--icon';
	if ( 'sm' === $size ) {
		$classes[] = 'ts-btn--sm';
	}
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'button' );

$merged = array(
	'class' => $class_attr,
);

if ( '' !== $aria_label ) {
	$merged['aria-label'] = $aria_label;
}

if ( is_array( $args['attributes'] ) ) {
	foreach ( $args['attributes'] as $attr_key => $attr_val ) {
		$attr_key = sanitize_key( (string) $attr_key );
		if ( '' === $attr_key || 'class' === $attr_key ) {
			continue;
		}
		if ( null === $attr_val || false === $attr_val ) {
			continue;
		}
		if ( true === $attr_val ) {
			$merged[ $attr_key ] = true;
			continue;
		}
		$merged[ $attr_key ] = is_scalar( $attr_val ) ? (string) $attr_val : '';
	}
}

if ( '' !== $href ) {
	$merged['href'] = $href;
	if ( $disabled || $loading ) {
		$merged['aria-disabled'] = 'true';
		$merged['tabindex']       = '-1';
	}
	$tag = 'a';
} else {
	$merged['type'] = $type;
	if ( '' !== $name ) {
		$merged['name'] = $name;
	}
	if ( '' !== $value ) {
		$merged['value'] = $value;
	}
	if ( $loading ) {
		$merged['aria-busy'] = 'true';
	}
	if ( $disabled || $loading ) {
		$merged['disabled'] = true;
	}
	$tag = 'button';
}

$attr_html = tailwindscore_attributes_html( $merged );

?>
<<?php echo esc_attr( $tag ); ?><?php echo $attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<?php if ( '' !== $icon_html && 'start' === $icon_pos ) : ?>
	<span class="ts-btn__icon" aria-hidden="true"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif; ?>
<?php if ( ! $is_icon_only ) : ?>
	<span class="ts-btn__label"><?php echo esc_html( $label ); ?></span>
<?php endif; ?>
<?php if ( '' !== $icon_html && 'end' === $icon_pos ) : ?>
	<span class="ts-btn__icon" aria-hidden="true"><?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif; ?>
</<?php echo esc_attr( $tag ); ?>>
