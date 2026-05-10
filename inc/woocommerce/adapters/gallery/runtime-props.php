<?php
/**
 * Gallery runtime adapter — WC gallery data → SSR props for TailwindScore gallery.
 *
 * Preserves attachment order: featured image, then gallery-only IDs (WC semantics).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Build props for `tailwindscore_component( 'gallery/gallery', … )`.
 *
 * @param WC_Product $product Product.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_gallery_runtime_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array(
			'product_id' => 0,
			'columns'    => 4,
			'slides'     => array(),
			'thumbs'     => array(),
		);
	}

	if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
		return array(
			'product_id' => (int) $product->get_id(),
			'columns'    => 4,
			'slides'     => array(),
			'thumbs'     => array(),
		);
	}

	$columns = (int) apply_filters( 'woocommerce_product_thumbnails_columns', 4 );

	$featured_id = (int) $product->get_image_id();
	$gallery_ids = array_map( 'intval', (array) $product->get_gallery_image_ids() );

	$attachment_ids = array();
	if ( $featured_id ) {
		$attachment_ids[] = $featured_id;
	}
	foreach ( $gallery_ids as $gid ) {
		if ( $gid > 0 && ! in_array( $gid, $attachment_ids, true ) ) {
			$attachment_ids[] = $gid;
		}
	}

	$slides = array();
	$thumbs = array();

	$gallery_thumbnail = function_exists( 'wc_get_image_size' ) ? wc_get_image_size( 'gallery_thumbnail' ) : array( 'width' => 100, 'height' => 100 );
	$thumb_size        = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
	$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );

	if ( ! empty( $attachment_ids ) ) {
		foreach ( $attachment_ids as $index => $attachment_id ) {
			$main_image = ( 0 === $index );
			$html       = wc_get_gallery_image_html( $attachment_id, $main_image, $index );

			$html = str_replace(
				'class="woocommerce-product-gallery__image"',
				'class="woocommerce-product-gallery__image embla__slide ts-gallery__slide"',
				$html
			);

			$html = preg_replace(
				'/<div\s+/',
				'<div data-attachment-id="' . esc_attr( (string) $attachment_id ) . '" ',
				$html,
				1
			);

			/**
			 * Filter a single gallery slide HTML block (after TailwindScore wrappers).
			 *
			 * @param string     $html          Slide markup.
			 * @param int        $attachment_id Attachment.
			 * @param WC_Product $product       Product.
			 * @param int        $index         Zero-based index.
			 */
			$html = apply_filters( 'tailwindscore/gallery/slide_html', $html, $attachment_id, $product, $index );

			$html = preg_replace( '/<a\s+href=/', '<a class="ts-gallery__lightbox-link" href=', $html, 1 );

			$thumb_src = wp_get_attachment_image_src( $attachment_id, $thumb_size );
			$full_src  = wp_get_attachment_image_src( $attachment_id, $full_size );

			$caption = '';
			if ( function_exists( 'wp_get_attachment_caption' ) ) {
				$c = wp_get_attachment_caption( $attachment_id );
				$caption = is_string( $c ) ? $c : '';
			}

			$slides[] = array(
				'attachment_id' => $attachment_id,
				'html'          => $html,
				'full_src'      => isset( $full_src[0] ) ? (string) $full_src[0] : '',
				'full_w'        => isset( $full_src[1] ) ? (int) $full_src[1] : 0,
				'full_h'        => isset( $full_src[2] ) ? (int) $full_src[2] : 0,
				'caption'       => $caption,
			);

			$thumbs[] = array(
				'attachment_id' => $attachment_id,
				'src'           => isset( $thumb_src[0] ) ? (string) $thumb_src[0] : '',
				'alt'           => trim( (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ),
			);
		}
	} else {
		// Match WC placeholder behaviour for variable products with priced children.
		$use_placeholder_class = false;
		$use_placeholder_class = $product->is_type( 'variable' )
			&& ! empty( $product->get_visible_children() )
			&& '' !== $product->get_price();

		$wrapper_classes = $use_placeholder_class
			? 'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder embla__slide ts-gallery__slide'
			: 'woocommerce-product-gallery__image--placeholder embla__slide ts-gallery__slide';

		$html  = '<div class="' . esc_attr( $wrapper_classes ) . '" data-attachment-id="0">';
		$html .= sprintf(
			'<a href="%s" class="ts-gallery__lightbox-link">%s</a>',
			esc_url( is_string( wc_placeholder_img_src( 'woocommerce_single' ) ) ? wc_placeholder_img_src( 'woocommerce_single' ) : '' ),
			sprintf(
				'<img src="%s" alt="%s" class="wp-post-image" />',
				esc_url( is_string( wc_placeholder_img_src( 'woocommerce_single' ) ) ? wc_placeholder_img_src( 'woocommerce_single' ) : '' ),
				esc_attr__( 'Awaiting product image', 'woocommerce' )
			)
		);
		$html .= '</div>';

		$slides[] = array(
			'attachment_id' => 0,
			'html'          => $html,
			'full_src'      => '',
			'full_w'        => 0,
			'full_h'        => 0,
			'caption'       => '',
		);

		$ph_src = wc_placeholder_img_src( $thumb_size );
		$thumbs[] = array(
			'attachment_id' => 0,
			'src'           => is_string( $ph_src ) ? $ph_src : '',
			'alt'           => '',
		);
	}

	$props = array(
		'product_id' => (int) $product->get_id(),
		'columns'    => $columns,
		'slides'     => $slides,
		'thumbs'     => $thumbs,
	);

	/**
	 * Filter full gallery runtime props (SSR / JSON boundary).
	 *
	 * @param array<string, mixed> $props   Gallery props.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/gallery_runtime_props', $props, $product );
}
