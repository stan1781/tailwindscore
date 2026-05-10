<?php
/**
 * 图片优先 Swatch — 缩略图 + 可选色块条 + 文案。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args SSR item（image_stack）。
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'value'                  => '',
	'label'                  => '',
	'selected'               => false,
	'image_url'              => '',
	'image_srcset'           => '',
	'image_sizes'            => '',
	'preview_url'            => '',
	'preview_srcset'         => '',
	'preview_sizes'          => '',
	'image_alt'              => '',
	'presentation_layer'     => '',
	'color_chip_primary'     => '',
	'color_chip_secondary'   => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$value    = is_string( $args['value'] ) ? $args['value'] : '';
$label    = is_string( $args['label'] ) ? $args['label'] : '';
$selected = (bool) $args['selected'];
$url      = is_string( $args['image_url'] ) ? $args['image_url'] : '';
$prev     = is_string( $args['preview_url'] ) ? $args['preview_url'] : '';
$layer    = is_string( $args['presentation_layer'] ) ? $args['presentation_layer'] : '';

if ( '' === $value ) {
	return;
}

$classes = array(
	'ts-swatch',
	'ts-swatch--image-stack',
);

if ( $selected ) {
	$classes[] = 'is-selected';
}

$class_attr = tailwindscore_component_classes( $classes, $args, 'swatch-image-stack' );

$chip_p = is_string( $args['color_chip_primary'] ) ? trim( $args['color_chip_primary'] ) : '';
$chip_s = is_string( $args['color_chip_secondary'] ) ? trim( $args['color_chip_secondary'] ) : '';

?>
<button
	type="button"
	class="<?php echo esc_attr( $class_attr ); ?>"
	role="radio"
	data-ts-swatch
	data-ts-swatch-type="image_stack"
	data-value="<?php echo esc_attr( $value ); ?>"
	data-preview-url="<?php echo esc_url( '' !== $prev ? $prev : $url ); ?>"
	data-thumb-url="<?php echo esc_url( $url ); ?>"
	data-presentation-layer="<?php echo esc_attr( $layer ); ?>"
	aria-checked="<?php echo $selected ? 'true' : 'false'; ?>"
	tabindex="<?php echo $selected ? '0' : '-1'; ?>"
	aria-label="<?php echo esc_attr( $label !== '' ? $label : $value ); ?>"
>
	<span class="ts-swatch-stack__media" aria-hidden="true">
		<?php if ( '' !== $url ) : ?>
			<span class="ts-swatch-stack__thumb">
				<img
					src="<?php echo esc_url( $url ); ?>"
					<?php if ( ! empty( $args['image_srcset'] ) ) : ?>
						srcset="<?php echo esc_attr( (string) $args['image_srcset'] ); ?>"
					<?php endif; ?>
					<?php if ( ! empty( $args['image_sizes'] ) ) : ?>
						sizes="<?php echo esc_attr( (string) $args['image_sizes'] ); ?>"
					<?php endif; ?>
					alt=""
					decoding="async"
					loading="lazy"
				/>
			</span>
		<?php else : ?>
			<span class="ts-swatch-stack__thumb ts-swatch-stack__thumb--empty"></span>
		<?php endif; ?>

		<?php if ( '' !== $chip_p || '' !== $chip_s ) : ?>
			<span class="ts-swatch-stack__chips">
				<?php if ( '' !== $chip_p ) : ?>
					<span class="ts-swatch-stack__chip" style="--ts-swatch-chip: <?php echo esc_attr( $chip_p ); ?>"></span>
				<?php endif; ?>
				<?php if ( '' !== $chip_s ) : ?>
					<span class="ts-swatch-stack__chip ts-swatch-stack__chip--secondary" style="--ts-swatch-chip: <?php echo esc_attr( $chip_s ); ?>"></span>
				<?php endif; ?>
			</span>
		<?php endif; ?>
	</span>

	<?php if ( '' !== $label ) : ?>
		<span class="ts-swatch-stack__caption"><?php echo esc_html( $label ); ?></span>
	<?php endif; ?>
</button>
