<?php
/**
 * Loop product render with direct archive feature ownership.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! isset( $product ) || ! is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( get_the_ID() );
}

if ( ! $product instanceof WC_Product ) {
	return;
}

$permalink      = (string) get_permalink( $product->get_id() );
$title          = $product->get_name();
$image_id       = (int) $product->get_image_id();
$primary        = tailwindscore_archive_product_card_media_attachment( $image_id, $title );
$secondary      = tailwindscore_archive_product_card_secondary_media( $product );
$badges         = tailwindscore_archive_product_badges( $product );
$swatches       = tailwindscore_archive_product_card_swatches( $product );
$price          = tailwindscore_adapter_price_props( $product );
$actions_html   = (string) apply_filters( 'tailwindscore/adapter/product-card/actions_html', '', $product );
$primary_action = '' !== $actions_html ? $actions_html : tailwindscore_archive_product_card_primary_action_html( $product );
$primary_type   = $product->is_type( 'simple' ) ? 'add_to_cart' : 'link';
$hover          = '' !== (string) ( $secondary['url'] ?? '' );
$primary_w      = isset( $primary['width'] ) && is_numeric( $primary['width'] ) ? (int) $primary['width'] : null;
$primary_h      = isset( $primary['height'] ) && is_numeric( $primary['height'] ) ? (int) $primary['height'] : null;
$secondary_w    = isset( $secondary['width'] ) && is_numeric( $secondary['width'] ) ? (int) $secondary['width'] : null;
$secondary_h    = isset( $secondary['height'] ) && is_numeric( $secondary['height'] ) ? (int) $secondary['height'] : null;
?>
<article class="ts-product-card" data-ts-module="tailwindscore-archive-runtime">
	<a class="ts-product-card__shell" href="<?php echo esc_url( $permalink ); ?>">
		<div
			class="ts-product-card__media"
			data-ts-archive-media
			data-hover-enabled="<?php echo $hover ? 'true' : 'false'; ?>"
			style="--ts-product-card-ratio: 4 / 5"
		>
			<?php if ( array() !== $badges ) : ?>
				<div class="ts-product-card__badges">
					<?php foreach ( $badges as $badge_args ) : ?>
						<?php tailwindscore_component( 'badge', $badge_args ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="ts-product-card__media-frame">
				<?php if ( ! empty( $primary['url'] ) ) : ?>
					<img
						class="ts-product-card__image ts-product-card__image--primary"
						src="<?php echo esc_url( (string) $primary['url'] ); ?>"
						alt="<?php echo esc_attr( (string) ( $primary['alt'] ?? '' ) ); ?>"
						<?php echo null !== $primary_w ? ' width="' . esc_attr( (string) $primary_w ) . '"' : ''; ?>
						<?php echo null !== $primary_h ? ' height="' . esc_attr( (string) $primary_h ) . '"' : ''; ?>
						loading="lazy"
						decoding="async"
						data-ts-primary-image
					/>
				<?php endif; ?>

				<?php if ( ! empty( $secondary['url'] ) ) : ?>
					<img
						class="ts-product-card__image ts-product-card__image--secondary"
						src="<?php echo esc_url( (string) $secondary['url'] ); ?>"
						alt=""
						aria-hidden="true"
						<?php echo null !== $secondary_w ? ' width="' . esc_attr( (string) $secondary_w ) . '"' : ''; ?>
						<?php echo null !== $secondary_h ? ' height="' . esc_attr( (string) $secondary_h ) . '"' : ''; ?>
						loading="lazy"
						decoding="async"
						data-ts-secondary-image
					/>
				<?php endif; ?>
			</div>
		</div>

		<div class="ts-product-card__body">
			<h3 class="ts-product-card__title"><?php echo esc_html( $title ); ?></h3>
		</div>
	</a>

	<?php if ( ! empty( $swatches['items'] ) && is_array( $swatches['items'] ) ) : ?>
		<div class="ts-product-card__swatches-row" data-ts-archive-swatches>
			<?php foreach ( $swatches['items'] as $item ) : ?>
				<?php
				$kind     = isset( $item['kind'] ) ? (string) $item['kind'] : 'text';
				$label    = isset( $item['label'] ) ? (string) $item['label'] : '';
				$selected = ! empty( $item['selected'] );
				$preview  = isset( $item['preview_image'] ) ? (string) $item['preview_image'] : '';
				$thumb    = isset( $item['thumb_image'] ) ? (string) $item['thumb_image'] : '';
				$color_1  = isset( $item['color_primary'] ) ? trim( (string) $item['color_primary'] ) : '';
				$color_2  = isset( $item['color_secondary'] ) ? trim( (string) $item['color_secondary'] ) : '';
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
					<?php elseif ( 'color' === $kind && '' !== $color_1 ) : ?>
						<span
							class="ts-archive-swatch__chip ts-archive-swatch__chip--color"
							style="--ts-swatch-color-primary: <?php echo esc_attr( $color_1 ); ?>; --ts-swatch-color-secondary: <?php echo esc_attr( $color_2 ); ?>;"
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
	<?php endif; ?>

	<div class="ts-product-card__footer">
		<?php if ( ! empty( $price ) ) : ?>
			<?php tailwindscore_component( 'price', $price ); ?>
		<?php endif; ?>

		<?php if ( '' !== $primary_action ) : ?>
			<div class="ts-product-card__actions" data-ts-archive-actions>
				<?php if ( 'add_to_cart' === $primary_type ) : ?>
					<?php tailwindscore_component( 'commerce/add-to-cart-button', array( 'inner_html' => $primary_action ) ); ?>
				<?php else : ?>
					<div class="ts-product-card__action-slot ts-product-card__action-slot--primary">
						<?php echo tailwindscore_kses_actions_slot( $primary_action ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
