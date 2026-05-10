<?php
/**
 * 颜色 Swatch — 支持 solid / dual chip。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args value, label, selected, color_primary, color_secondary
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'value'             => '',
	'label'             => '',
	'selected'          => false,
	'color_primary'     => '',
	'color_secondary'   => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$value    = is_string( $args['value'] ) ? $args['value'] : '';
$label    = is_string( $args['label'] ) ? $args['label'] : '';
$selected = (bool) $args['selected'];
$primary  = is_string( $args['color_primary'] ) ? trim( $args['color_primary'] ) : '';
$secondary = is_string( $args['color_secondary'] ) ? trim( $args['color_secondary'] ) : '';

if ( '' === $value ) {
	return;
}

if ( '' === $primary ) {
	$primary = 'transparent';
}

$classes = array(
	'ts-swatch',
	'ts-swatch--color',
);

if ( $selected ) {
	$classes[] = 'is-selected';
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'swatch-color' );

?>
<button
	type="button"
	class="<?php echo esc_attr( $class_attr ); ?>"
	role="radio"
	data-ts-swatch
	data-ts-swatch-type="color"
	data-value="<?php echo esc_attr( $value ); ?>"
	aria-checked="<?php echo $selected ? 'true' : 'false'; ?>"
	tabindex="<?php echo $selected ? '0' : '-1'; ?>"
	aria-label="<?php echo esc_attr( $label !== '' ? $label : $value ); ?>"
>
	<span class="ts-swatch__chip" aria-hidden="true">
		<?php if ( '' !== $secondary ) : ?>
			<span class="ts-swatch__chip-half ts-swatch__chip-half--primary" style="--ts-swatch-color: <?php echo esc_attr( $primary ); ?>"></span>
			<span class="ts-swatch__chip-half ts-swatch__chip-half--secondary" style="--ts-swatch-color: <?php echo esc_attr( $secondary ); ?>"></span>
		<?php else : ?>
			<span class="ts-swatch__chip-solid" style="--ts-swatch-color: <?php echo esc_attr( $primary ); ?>"></span>
		<?php endif; ?>
	</span>
	<?php if ( '' !== $label ) : ?>
		<span class="ts-swatch__caption"><?php echo esc_html( $label ); ?></span>
	<?php endif; ?>
</button>
