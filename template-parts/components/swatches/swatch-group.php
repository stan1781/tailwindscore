<?php
/**
 * Swatch group SSR: radiogroup plus native WooCommerce select fallback.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'select_id'   => '',
	'select_name' => '',
	'attribute'   => '',
	'aria_label'  => '',
	'items'       => array(),
	'inner_html'  => '',
);

$args = wp_parse_args( (array) ( $args ?? array() ), $defaults );

$select_id  = is_string( $args['select_id'] ) ? $args['select_id'] : '';
$aria_label = is_string( $args['aria_label'] ) ? $args['aria_label'] : '';
$items      = isset( $args['items'] ) && is_array( $args['items'] ) ? $args['items'] : array();
$inner_html = is_string( $args['inner_html'] ) ? $args['inner_html'] : '';

if ( '' === $select_id || array() === $items ) {
	echo $inner_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$group_classes = tailwindscore_component_classes(
	array( 'ts-swatch-group' ),
	$args,
	'swatch-group'
);

?>
<div class="ts-swatch-field" data-ts-swatch-field data-attribute="<?php echo esc_attr( (string) $args['attribute'] ); ?>">
	<div class="ts-swatch-preview" data-ts-swatch-preview hidden aria-hidden="true">
		<img class="ts-swatch-preview__img" alt="" decoding="async" loading="eager" />
	</div>
	<div
		id="<?php echo esc_attr( $select_id ); ?>-swatches"
		class="<?php echo esc_attr( $group_classes ); ?>"
		role="radiogroup"
		aria-label="<?php echo esc_attr( $aria_label ); ?>"
		data-ts-swatch-group
		data-select-id="<?php echo esc_attr( $select_id ); ?>"
	>
		<div class="ts-swatch-group__list" role="presentation">
			<?php
			foreach ( $items as $item ) {
				if ( ! is_array( $item ) ) {
					continue;
				}

				$item = wp_parse_args(
					$item,
					array(
						'value'                => '',
						'label'                => '',
						'selected'             => false,
						'color_primary'        => '',
						'color_secondary'      => '',
						'image_url'            => '',
						'image_srcset'         => '',
						'image_sizes'          => '',
						'preview_url'          => '',
						'presentation_layer'   => '',
						'color_chip_primary'   => '',
						'color_chip_secondary' => '',
					)
				);

				$value    = is_string( $item['value'] ) ? $item['value'] : '';
				$label    = is_string( $item['label'] ) ? $item['label'] : '';
				$selected = (bool) $item['selected'];

				if ( '' === $value ) {
					continue;
				}

				$type = isset( $item['type'] ) ? (string) $item['type'] : 'text';

				switch ( $type ) {
					case 'color':
						$primary   = is_string( $item['color_primary'] ) ? trim( $item['color_primary'] ) : '';
						$secondary = is_string( $item['color_secondary'] ) ? trim( $item['color_secondary'] ) : '';

						if ( '' === $primary ) {
							$primary = 'transparent';
						}

						$classes = array( 'ts-swatch', 'ts-swatch--color' );
						if ( $selected ) {
							$classes[] = 'is-selected';
						}

						$class_attr = tailwindscore_component_classes( $classes, $item, 'swatch-color' );
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
						<?php
						break;

					case 'image_stack':
						$url    = is_string( $item['image_url'] ) ? $item['image_url'] : '';
						$prev   = is_string( $item['preview_url'] ) ? $item['preview_url'] : '';
						$layer  = is_string( $item['presentation_layer'] ) ? $item['presentation_layer'] : '';
						$chip_p = is_string( $item['color_chip_primary'] ) ? trim( $item['color_chip_primary'] ) : '';
						$chip_s = is_string( $item['color_chip_secondary'] ) ? trim( $item['color_chip_secondary'] ) : '';

						$classes = array( 'ts-swatch', 'ts-swatch--image-stack' );
						if ( $selected ) {
							$classes[] = 'is-selected';
						}

						$class_attr = tailwindscore_component_classes( $classes, $item, 'swatch-image-stack' );
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
											<?php if ( ! empty( $item['image_srcset'] ) ) : ?>
												srcset="<?php echo esc_attr( (string) $item['image_srcset'] ); ?>"
											<?php endif; ?>
											<?php if ( ! empty( $item['image_sizes'] ) ) : ?>
												sizes="<?php echo esc_attr( (string) $item['image_sizes'] ); ?>"
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
						<?php
						break;

					case 'image':
						$url    = is_string( $item['image_url'] ) ? $item['image_url'] : '';
						$srcset = is_string( $item['image_srcset'] ) ? $item['image_srcset'] : '';
						$sizes  = is_string( $item['image_sizes'] ) ? $item['image_sizes'] : '';

						$classes = array( 'ts-swatch', 'ts-swatch--image' );
						if ( $selected ) {
							$classes[] = 'is-selected';
						}

						$class_attr = tailwindscore_component_classes( $classes, $item, 'swatch-image' );
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
						<?php
						break;

					default:
						$classes = array( 'ts-swatch', 'ts-swatch--text' );
						if ( $selected ) {
							$classes[] = 'is-selected';
						}

						$class_attr = tailwindscore_component_classes( $classes, $item, 'swatch-text' );
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
						<?php
						break;
				}
			}
			?>
		</div>
	</div>
	<div class="ts-swatch-native-wrap">
		<?php echo $inner_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC select HTML. ?>
	</div>
</div>
