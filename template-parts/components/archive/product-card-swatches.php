<?php
/**
 * Archive display-only swatches.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$swatches = isset( $args['swatches'] ) && is_array( $args['swatches'] ) ? $args['swatches'] : array();
$items    = isset( $swatches['items'] ) && is_array( $swatches['items'] ) ? $swatches['items'] : array();

if ( array() === $items ) {
	return;
}
?>
<div class="ts-product-card__swatches-row" data-ts-archive-swatches>
	<?php foreach ( $items as $item ) : ?>
		<?php
		$kind      = isset( $item['kind'] ) ? (string) $item['kind'] : 'text';
		$label     = isset( $item['label'] ) ? (string) $item['label'] : '';
		$selected  = ! empty( $item['selected'] );
		$preview   = isset( $item['preview_image'] ) ? (string) $item['preview_image'] : '';
		$thumb     = isset( $item['thumb_image'] ) ? (string) $item['thumb_image'] : '';
		$primary   = isset( $item['color_primary'] ) ? trim( (string) $item['color_primary'] ) : '';
		$secondary = isset( $item['color_secondary'] ) ? trim( (string) $item['color_secondary'] ) : '';
		?>
		<button
			type="button"
			class="ts-archive-swatch<?php echo $selected ? ' is-selected' : ''; ?>"
			data-ts-archive-swatch
			data-preview-image="<?php echo esc_attr( $preview ); ?>"
			aria-pressed="<?php echo $selected ? 'true' : 'false'; ?>"
			aria-label="<?php echo esc_attr( $label ); ?>"
		>
			<?php if ( 'image' === $kind && '' !== $thumb ) : ?>
				<span class="ts-archive-swatch__chip ts-archive-swatch__chip--image">
					<img src="<?php echo esc_url( $thumb ); ?>" alt="" loading="lazy" decoding="async" />
				</span>
			<?php elseif ( 'color' === $kind && '' !== $primary ) : ?>
				<span
					class="ts-archive-swatch__chip ts-archive-swatch__chip--color"
					style="--ts-swatch-color-primary: <?php echo esc_attr( $primary ); ?>; --ts-swatch-color-secondary: <?php echo esc_attr( $secondary ); ?>;"
				></span>
			<?php else : ?>
				<span class="ts-archive-swatch__chip ts-archive-swatch__chip--text"><?php echo esc_html( substr( $label, 0, 1 ) ); ?></span>
			<?php endif; ?>
		</button>
	<?php endforeach; ?>

	<?php if ( ! empty( $swatches['more_count'] ) ) : ?>
		<span class="ts-product-card__swatches-more">+<?php echo esc_html( (string) $swatches['more_count'] ); ?></span>
	<?php endif; ?>
</div>
