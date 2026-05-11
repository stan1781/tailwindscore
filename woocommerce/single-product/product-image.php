<?php
/**
 * Single Product Image with direct gallery feature ownership.
 *
 * @package TailwindScore
 * @version 10.5.0
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

if ( ! $product || ! $product instanceof WC_Product ) {
	return;
}

$props       = tailwindscore_product_gallery_runtime_props( $product );
$product_id  = isset( $props['product_id'] ) ? (int) $props['product_id'] : 0;
$columns     = isset( $props['columns'] ) ? (int) $props['columns'] : 4;
$slides      = isset( $props['slides'] ) && is_array( $props['slides'] ) ? $props['slides'] : array();
$thumbs      = isset( $props['thumbs'] ) && is_array( $props['thumbs'] ) ? $props['thumbs'] : array();
$slide_count = count( $slides );
$first_id    = $slide_count > 0 && isset( $slides[0]['attachment_id'] ) ? (int) $slides[0]['attachment_id'] : 0;
$with_images = $slide_count > 0 && ! ( 1 === $slide_count && 0 === $first_id );

$wrapper_classes = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $with_images ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
		'ts-gallery',
	)
);

$class_attr = trim( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) );

$json_payload = wp_json_encode(
	array(
		'productId' => $product_id,
		'slides'    => array_map(
			static function ( array $s ): array {
				return array(
					'attachmentId' => isset( $s['attachment_id'] ) ? (int) $s['attachment_id'] : 0,
					'fullSrc'      => isset( $s['full_src'] ) ? (string) $s['full_src'] : '',
					'fullW'        => isset( $s['full_w'] ) ? (int) $s['full_w'] : 0,
					'fullH'        => isset( $s['full_h'] ) ? (int) $s['full_h'] : 0,
					'caption'      => isset( $s['caption'] ) ? (string) $s['caption'] : '',
				);
			},
			$slides
		),
	),
	JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
?>
<div
	class="<?php echo esc_attr( $class_attr ); ?>"
	data-columns="<?php echo esc_attr( (string) $columns ); ?>"
	data-product-id="<?php echo esc_attr( (string) $product_id ); ?>"
	data-ts-module="tailwindscore-product-gallery"
>
	<?php if ( is_string( $json_payload ) ) : ?>
		<script type="application/json" class="ts-gallery__json"><?php echo $json_payload; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
	<?php endif; ?>

	<div class="ts-gallery__layout">
		<?php if ( count( $thumbs ) > 1 ) : ?>
			<div class="ts-gallery__col ts-gallery__col--thumbs">
				<div class="ts-gallery__thumbs-shell">
					<div class="ts-gallery__thumbs-viewport embla">
						<ul class="flex-control-nav embla__container ts-gallery__thumbs-list">
							<?php foreach ( $thumbs as $idx => $thumb ) : ?>
								<?php
								$tid = isset( $thumb['attachment_id'] ) ? (int) $thumb['attachment_id'] : 0;
								$src = isset( $thumb['src'] ) ? (string) $thumb['src'] : '';
								$alt = isset( $thumb['alt'] ) ? (string) $thumb['alt'] : '';
								?>
								<li class="embla__slide ts-gallery__thumb" data-thumb-index="<?php echo esc_attr( (string) $idx ); ?>">
									<button type="button" class="ts-gallery__thumb-btn" aria-label="<?php echo esc_attr( sprintf( __( 'Go to image %d', 'tailwindscore' ), $idx + 1 ) ); ?>">
										<img
											src="<?php echo esc_url( $src ); ?>"
											alt="<?php echo esc_attr( $alt ); ?>"
											loading="lazy"
											decoding="async"
											data-attachment-id="<?php echo esc_attr( (string) $tid ); ?>"
										/>
									</button>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="ts-gallery__col ts-gallery__col--main">
			<div class="woocommerce-product-gallery__wrapper ts-gallery__main-wrapper">
				<div class="ts-gallery__main-viewport embla">
					<div class="ts-gallery__main-track embla__container">
						<?php
						foreach ( $slides as $slide_args ) {
							if ( ! is_array( $slide_args ) ) {
								continue;
							}
							$html = isset( $slide_args['html'] ) && is_string( $slide_args['html'] ) ? $slide_args['html'] : '';
							echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
				</div>
				<?php if ( $slide_count > 1 ) : ?>
					<div class="ts-gallery__controls" aria-hidden="false">
						<button type="button" class="ts-gallery__nav ts-gallery__nav--prev" data-gallery-nav="prev" aria-label="<?php esc_attr_e( 'Previous image', 'tailwindscore' ); ?>">
							<span class="ts-gallery__nav-icon ts-commerce-icon ts-commerce-icon--action" aria-hidden="true"><?php echo tailwindscore_icon( 'chevron-right', array( 'class' => 'ts-icon--nav ts-gallery__nav-icon-svg ts-gallery__nav-icon-svg--prev' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</button>
						<button type="button" class="ts-gallery__nav ts-gallery__nav--next" data-gallery-nav="next" aria-label="<?php esc_attr_e( 'Next image', 'tailwindscore' ); ?>">
							<span class="ts-gallery__nav-icon ts-commerce-icon ts-commerce-icon--action" aria-hidden="true"><?php echo tailwindscore_icon( 'chevron-right', array( 'class' => 'ts-icon--nav ts-gallery__nav-icon-svg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</button>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
