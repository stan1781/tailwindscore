<?php
/**
 * 图片 Swatch — 纹理 / 材质缩略图。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args value, label, selected, image_url, image_srcset, image_sizes, image_alt
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'value'        => '',
	'label'        => '',
	'selected'     => false,
	'image_url'    => '',
	'image_srcset' => '',
	'image_sizes'  => '',
	'image_alt'    => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$value    = is_string( $args['value'] ) ? $args['value'] : '';
$label    = is_string( $args['label'] ) ? $args['label'] : '';
$selected = (bool) $args['selected'];
$url      = is_string( $args['image_url'] ) ? $args['image_url'] : '';
$srcset   = is_string( $args['image_srcset'] ) ? $args['image_srcset'] : '';
$sizes    = is_string( $args['image_sizes'] ) ? $args['image_sizes'] : '';
$alt      = is_string( $args['image_alt'] ) ? $args['image_alt'] : '';

if ( '' === $value ) {
	return;
}

$classes = array(
	'ts-swatch',
	'ts-swatch--image',
);

if ( $selected ) {
	$classes[] = 'is-selected';
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'swatch-image' );

?>
<button
	type="button"
	class="<?php echo esc_attr( $class_attr ); ?>"
	role="radio"
	data-ts-swatch
	data-ts-swatch-type="image"
	data-value="<?php echo esc_attr( $value ); ?>"
	aria-checked="<?php echo $selected ? 'true' : 'false'; ?>"
	tabindex="<?php echo $selected ? '0' : '-1'; ?>"
	aria-label="<?php echo esc_attr( $label !== '' ? $label : $value ); ?>"
>
	<?php if ( '' !== $url ) : ?>
		<span class="ts-swatch__thumb" aria-hidden="true">
			<img
				src="<?php echo esc_url( $url ); ?>"
				<?php if ( '' !== $srcset ) : ?>
					srcset="<?php echo esc_attr( $srcset ); ?>"
				<?php endif; ?>
				<?php if ( '' !== $sizes ) : ?>
					sizes="<?php echo esc_attr( $sizes ); ?>"
				<?php endif; ?>
				alt=""
				decoding="async"
				loading="lazy"
			/>
		</span>
	<?php else : ?>
		<span class="ts-swatch__thumb ts-swatch__thumb--placeholder" aria-hidden="true"></span>
	<?php endif; ?>
	<?php if ( '' !== $label ) : ?>
		<span class="ts-swatch__caption"><?php echo esc_html( $label ); ?></span>
	<?php endif; ?>
</button>
