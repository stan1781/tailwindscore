<?php
/**
 * 文本类 Swatch（尺寸、容量等）。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args value, label, selected
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'value'    => '',
	'label'    => '',
	'selected' => false,
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$value    = is_string( $args['value'] ) ? $args['value'] : '';
$label    = is_string( $args['label'] ) ? $args['label'] : '';
$selected = (bool) $args['selected'];

if ( '' === $value ) {
	return;
}

$classes = array(
	'ts-swatch',
	'ts-swatch--text',
);

if ( $selected ) {
	$classes[] = 'is-selected';
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'swatch-text' );

?>
<button
	type="button"
	class="<?php echo esc_attr( $class_attr ); ?>"
	role="radio"
	data-ts-swatch
	data-ts-swatch-type="text"
	data-value="<?php echo esc_attr( $value ); ?>"
	aria-checked="<?php echo $selected ? 'true' : 'false'; ?>"
	tabindex="<?php echo $selected ? '0' : '-1'; ?>"
	aria-label="<?php echo esc_attr( $label !== '' ? $label : $value ); ?>"
>
	<span class="ts-swatch__label"><?php echo esc_html( $label !== '' ? $label : $value ); ?></span>
</button>
