<?php
/**
 * Product gallery shell — Embla + WC-compatible hooks/classes.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args From {@see tailwindscore_adapter_gallery_runtime_props()}.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/** @var array<string, mixed> $args */
$args = isset( $args ) && is_array( $args ) ? $args : array();

$product_id = isset( $args['product_id'] ) ? (int) $args['product_id'] : 0;
$columns    = isset( $args['columns'] ) ? (int) $args['columns'] : 4;
$slides     = isset( $args['slides'] ) && is_array( $args['slides'] ) ? $args['slides'] : array();
$thumbs     = isset( $args['thumbs'] ) && is_array( $args['thumbs'] ) ? $args['thumbs'] : array();

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
				<?php tailwindscore_component( 'gallery/gallery-thumbs', array_merge( $args, array( 'thumbs' => $thumbs ) ) ); ?>
			</div>
		<?php endif; ?>

		<div class="ts-gallery__col ts-gallery__col--main">
			<div class="woocommerce-product-gallery__wrapper ts-gallery__main-wrapper">
				<div class="ts-gallery__main-viewport embla">
					<div class="ts-gallery__main-track embla__container">
						<?php
						foreach ( $slides as $slide_args ) {
							if ( is_array( $slide_args ) ) {
								tailwindscore_component( 'gallery/gallery-slide', $slide_args );
							}
						}
						?>
					</div>
				</div>
				<?php
				if ( $slide_count > 1 ) {
					tailwindscore_component( 'gallery/gallery-controls', array() );
				}
				?>
			</div>
		</div>
	</div>
</div>
