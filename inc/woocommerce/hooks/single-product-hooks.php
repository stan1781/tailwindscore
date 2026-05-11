<?php
/**
 * Single product feature hooks and runtime helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Resolve the current single-product context.
 */
function tailwindscore_single_product_summary_product(): ?WC_Product {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}

	global $product;

	if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	return $product && is_a( $product, 'WC_Product' ) ? $product : null;
}

/**
 * Whether the TailwindScore single-product summary replacement is enabled.
 */
function tailwindscore_use_single_product_summary_components(): bool {
	return (bool) apply_filters( 'tailwindscore/woocommerce/single-product-summary/use_components', true );
}

/**
 * Render the title block.
 */
function tailwindscore_render_single_product_summary_title(): void {
	if ( ! tailwindscore_use_single_product_summary_components() ) {
		return;
	}

	$product = tailwindscore_single_product_summary_product();
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$title = $product->get_name();
	if ( '' === $title ) {
		return;
	}

	echo '<div class="ts-product-summary__title-wrap">';
	echo '<h1 class="ts-product-summary__title ts-heading-1">' . esc_html( $title ) . '</h1>';
	echo '</div>';
}

/**
 * Render the rating block.
 */
function tailwindscore_render_single_product_summary_rating(): void {
	if ( ! tailwindscore_use_single_product_summary_components() ) {
		return;
	}

	$product = tailwindscore_single_product_summary_product();
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$average      = (float) $product->get_average_rating();
	$review_count = (int) $product->get_review_count();
	$rating_html  = function_exists( 'wc_get_rating_html' ) ? wc_get_rating_html( $average, $review_count ) : '';

	if ( ! (bool) apply_filters( 'tailwindscore/adapter/single-product/rating/show_if_empty', false, $product ) && '' === trim( $rating_html ) ) {
		return;
	}

	echo '<div class="ts-product-summary__rating ts-rating">';
	echo wp_kses_post( $rating_html );
	echo '</div>';
}

/**
 * Render the price block.
 */
function tailwindscore_render_single_product_summary_price(): void {
	if ( ! tailwindscore_use_single_product_summary_components() ) {
		return;
	}

	$product = tailwindscore_single_product_summary_product();
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$price_html = $product->get_price_html();
	$suffix     = is_callable( array( $product, 'get_price_suffix' ) ) ? (string) $product->get_price_suffix() : '';
	$unit_html  = apply_filters( 'tailwindscore/adapter/price/unit_html', '', $product );

	if ( '' === trim( (string) $price_html ) && '' === trim( $suffix ) && ( ! is_string( $unit_html ) || '' === $unit_html ) ) {
		return;
	}

	echo '<div class="ts-price-block ts-product-summary__price">';
	echo wp_kses_post( $price_html );
	if ( '' !== $suffix ) {
		echo '<span class="ts-price-block__suffix">' . wp_kses_post( $suffix ) . '</span>';
	}
	if ( is_string( $unit_html ) && '' !== $unit_html ) {
		echo '<span class="ts-product-text">' . wp_kses_post( $unit_html ) . '</span>';
	}
	echo '</div>';
}

/**
 * Render the short description block.
 */
function tailwindscore_render_single_product_summary_excerpt(): void {
	if ( ! tailwindscore_use_single_product_summary_components() ) {
		return;
	}

	$product = tailwindscore_single_product_summary_product();
	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$raw      = $product->get_short_description();
	$filtered = (string) apply_filters( 'woocommerce_short_description', $raw );

	if ( '' === trim( wp_strip_all_tags( $filtered ) ) ) {
		return;
	}

	$html = function_exists( 'wc_format_content' )
		? wc_format_content( $filtered )
		: wpautop( wp_kses_post( $filtered ) );

	echo '<div class="ts-product-summary__excerpt ts-prose ts-body">';
	echo wp_kses_post( $html );
	echo '</div>';
}

/**
 * Render the add-to-cart block wrapped in the TailwindScore interaction host.
 */
function tailwindscore_render_single_product_summary_add_to_cart(): void {
	if ( ! tailwindscore_use_single_product_summary_components() ) {
		woocommerce_template_single_add_to_cart();
		return;
	}

	ob_start();
	woocommerce_template_single_add_to_cart();
	$html = (string) ob_get_clean();

	if ( '' === trim( $html ) ) {
		return;
	}

	tailwindscore_component(
		'commerce/add-to-cart-button',
		array(
			'inner_html' => $html,
		)
	);
}

/**
 * Open the summary lead stack: title, rating, and price.
 */
function tailwindscore_pdp_commerce_open_summary_lead(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}

	echo '<div class="ts-commerce-summary__lead">';
}

/**
 * Close the summary lead stack before the excerpt.
 */
function tailwindscore_pdp_commerce_close_summary_lead(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}

	echo '</div>';
}

/**
 * Open the purchase region around add-to-cart output.
 */
function tailwindscore_pdp_commerce_open_purchase_region(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}

	echo '<div class="ts-purchase-region">';
}

/**
 * Close the purchase region after add-to-cart output.
 */
function tailwindscore_pdp_commerce_close_purchase_region(): void {
	if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
		return;
	}

	echo '</div>';
}

/**
 * Build SSR gallery props for the single-product gallery template.
 *
 * @return array<string, mixed>
 */
function tailwindscore_product_gallery_runtime_props( $product ): array {
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

	$columns        = (int) apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	$featured_id    = (int) $product->get_image_id();
	$gallery_ids    = array_map( 'intval', (array) $product->get_gallery_image_ids() );
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
			$main_image = 0 === $index;
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

			$html = apply_filters( 'tailwindscore/gallery/slide_html', $html, $attachment_id, $product, $index );
			$html = preg_replace( '/<a\s+href=/', '<a class="ts-gallery__lightbox-link" href=', $html, 1 );

			$thumb_src = wp_get_attachment_image_src( $attachment_id, $thumb_size );
			$full_src  = wp_get_attachment_image_src( $attachment_id, $full_size );
			$caption   = function_exists( 'wp_get_attachment_caption' ) ? (string) wp_get_attachment_caption( $attachment_id ) : '';

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
		$use_placeholder_class = $product->is_type( 'variable' )
			&& ! empty( $product->get_visible_children() )
			&& '' !== $product->get_price();

		$wrapper_classes = $use_placeholder_class
			? 'woocommerce-product-gallery__image woocommerce-product-gallery__image--placeholder embla__slide ts-gallery__slide'
			: 'woocommerce-product-gallery__image--placeholder embla__slide ts-gallery__slide';

		$placeholder_src = wc_placeholder_img_src( 'woocommerce_single' );
		$html            = '<div class="' . esc_attr( $wrapper_classes ) . '" data-attachment-id="0">';
		$html           .= sprintf(
			'<a href="%s" class="ts-gallery__lightbox-link">%s</a>',
			esc_url( is_string( $placeholder_src ) ? $placeholder_src : '' ),
			sprintf(
				'<img src="%s" alt="%s" class="wp-post-image" />',
				esc_url( is_string( $placeholder_src ) ? $placeholder_src : '' ),
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

		$ph_src   = wc_placeholder_img_src( $thumb_size );
		$thumbs[] = array(
			'attachment_id' => 0,
			'src'           => is_string( $ph_src ) ? $ph_src : '',
			'alt'           => '',
		);
	}

	return array(
		'product_id' => (int) $product->get_id(),
		'columns'    => $columns,
		'slides'     => $slides,
		'thumbs'     => $thumbs,
	);
}

add_filter(
	'tailwindscore/pdp/sticky-summary-column',
	static function ( bool $enabled ): bool {
		if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
			return $enabled;
		}

		return (bool) apply_filters( 'tailwindscore/pdp/commerce-sticky-summary', true );
	},
	9
);

add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false', 100 );
add_filter( 'woocommerce_single_product_photoswipe_enabled', '__return_false', 100 );
add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false', 100 );

/**
 * Document native WooCommerce hook priorities; customize in feature hooks, not templates.
 *
 * Gallery: woocommerce_before_single_product_summary (default product images around 20).
 * Summary: woocommerce_single_product_summary (title, rating, price, cart...).
 */
add_action(
	'woocommerce_before_single_product',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/single/before_product' );
	},
	5
);

add_action(
	'woocommerce_after_single_product',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/single/after_product' );
	},
	5
);

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! tailwindscore_use_single_product_summary_components() ) {
			return;
		}

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_title', 5 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_price', 10 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_render_single_product_summary_add_to_cart', 30 );
	},
	20
);

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! apply_filters( 'tailwindscore/pdp/use-section-layout', true ) ) {
			return;
		}

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		add_action( 'tailwindscore/pdp/section/details', 'woocommerce_output_product_data_tabs', 10 );
		add_action( 'tailwindscore/pdp/section/related', 'woocommerce_upsell_display', 5 );
		add_action( 'tailwindscore/pdp/section/related', 'woocommerce_output_related_products', 10 );
	},
	25
);

add_action(
	'woocommerce_init',
	static function (): void {
		if ( ! apply_filters( 'tailwindscore/pdp/commerce-experience', true ) ) {
			return;
		}

		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_open_summary_lead', 4 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_close_summary_lead', 19 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_open_purchase_region', 29 );
		add_action( 'woocommerce_single_product_summary', 'tailwindscore_pdp_commerce_close_purchase_region', 31 );
	},
	30
);

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		wp_dequeue_script( 'flexslider' );
		wp_dequeue_style( 'photoswipe' );
		wp_dequeue_style( 'photoswipe-default-skin' );
		wp_dequeue_script( 'photoswipe' );
		wp_dequeue_script( 'photoswipe-ui-default' );
	},
	100
);
