<?php
/**
 * Product card adapter — WC_Product → product-card component `$args`.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array{image_id:int,url:string,alt:string,width:int|string,height:int|string}
 */
function tailwindscore_adapter_product_card_media_attachment( int $image_id, string $fallback_alt = '' ): array {
	$url    = '';
	$width  = '';
	$height = '';

	if ( $image_id > 0 ) {
		$src = wp_get_attachment_image_src( $image_id, 'woocommerce_thumbnail' );
		if ( is_array( $src ) && isset( $src[0] ) ) {
			$url    = (string) $src[0];
			$width  = isset( $src[1] ) ? (int) $src[1] : '';
			$height = isset( $src[2] ) ? (int) $src[2] : '';
		}
	}

	$alt = '';
	if ( $image_id > 0 ) {
		$alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	}
	if ( '' === $alt ) {
		$alt = $fallback_alt;
	}

	return array(
		'image_id' => $image_id,
		'url'      => $url,
		'alt'      => $alt,
		'width'    => $width,
		'height'   => $height,
	);
}

/**
 * @return array{image_id:int,url:string,alt:string,width:int|string,height:int|string}
 */
function tailwindscore_adapter_product_card_secondary_media( WC_Product $product ): array {
	$gallery_ids = array_map( 'intval', (array) $product->get_gallery_image_ids() );
	$image_id    = isset( $gallery_ids[0] ) ? (int) $gallery_ids[0] : 0;

	return tailwindscore_adapter_product_card_media_attachment( $image_id, $product->get_name() );
}

function tailwindscore_adapter_product_card_primary_action_html( WC_Product $product ): string {
	$is_simple = $product->is_type( 'simple' );
	$url       = $is_simple ? $product->add_to_cart_url() : get_permalink( $product->get_id() );
	$text      = $is_simple ? $product->add_to_cart_text() : $product->add_to_cart_text();
	$label     = $product->add_to_cart_description();

	$classes = array(
		'button',
		'product_type_' . $product->get_type(),
		'ts-product-card__action-btn',
	);

	if ( $is_simple ) {
		$classes[] = 'add_to_cart_button';
		if ( $product->supports( 'ajax_add_to_cart' ) ) {
			$classes[] = 'ajax_add_to_cart';
		}
	}

	$attrs = array(
		'href'         => (string) $url,
		'data-quantity'=> '1',
		'class'        => implode( ' ', array_filter( $classes ) ),
		'aria-label'   => is_string( $label ) && '' !== $label ? $label : $text,
		'rel'          => 'nofollow',
	);

	if ( $is_simple ) {
		$attrs['data-product_id']  = (string) $product->get_id();
		$attrs['data-product_sku'] = (string) $product->get_sku();
		$attrs['data-success_message'] = '';
	}

	return sprintf(
		'<a %1$s>%2$s</a>',
		tailwindscore_attributes_html( $attrs ),
		esc_html( (string) $text )
	);
}

/**
 * @return array{mode:string,items:array<int,array<string,mixed>>,max_visible:int,more_count:int}
 */
function tailwindscore_adapter_product_card_swatches( WC_Product $product ): array {
	$empty = array(
		'mode'        => 'preview',
		'items'       => array(),
		'max_visible' => 5,
		'more_count'  => 0,
	);

	if ( ! $product->is_type( 'variable' ) || ! $product instanceof WC_Product_Variable ) {
		return $empty;
	}

	if ( ! function_exists( 'tailwindscore_swatch_enabled_for_attribute' ) || ! function_exists( 'tailwindscore_swatch_resolve_taxonomy_term_visual' ) ) {
		return $empty;
	}

	$attributes = $product->get_variation_attributes();
	if ( ! is_array( $attributes ) || array() === $attributes ) {
		return $empty;
	}

	$chosen_attribute = '';
	foreach ( array_keys( $attributes ) as $attribute ) {
		if ( ! is_string( $attribute ) || ! taxonomy_exists( $attribute ) ) {
			continue;
		}
		if ( ! tailwindscore_swatch_enabled_for_attribute( $attribute, $product ) ) {
			continue;
		}
		$chosen_attribute = $attribute;
		break;
	}

	if ( '' === $chosen_attribute ) {
		return $empty;
	}

	$terms = wc_get_product_terms(
		$product->get_id(),
		$chosen_attribute,
		array( 'fields' => 'all' )
	);
	if ( ! is_array( $terms ) || array() === $terms ) {
		return $empty;
	}

	$items = array();
	foreach ( $terms as $term ) {
		if ( ! $term instanceof WP_Term ) {
			continue;
		}
		$resolved = tailwindscore_swatch_resolve_taxonomy_term_visual( $product, $chosen_attribute, $term, $term->slug );
		$image    = isset( $resolved['image'] ) && is_array( $resolved['image'] ) ? $resolved['image'] : array();
		$preview  = isset( $image['preview_url'] ) ? (string) $image['preview_url'] : '';
		$thumb    = isset( $image['thumb_url'] ) ? (string) $image['thumb_url'] : '';
		$color_1  = isset( $resolved['color_primary'] ) ? trim( (string) $resolved['color_primary'] ) : '';
		$color_2  = isset( $resolved['color_secondary'] ) ? trim( (string) $resolved['color_secondary'] ) : '';
		$kind     = '' !== $thumb || '' !== $preview ? 'image' : ( '' !== $color_1 ? 'color' : 'text' );

		$items[] = array(
			'value'            => $term->slug,
			'label'            => $term->name,
			'kind'             => $kind,
			'preview_image'    => '' !== $preview ? $preview : $thumb,
			'thumb_image'      => $thumb,
			'color_primary'    => $color_1,
			'color_secondary'  => $color_2,
			'selected'         => 0 === count( $items ),
			'presentation_layer' => isset( $resolved['layer'] ) ? (string) $resolved['layer'] : 'text',
		);
	}

	if ( array() === $items ) {
		return $empty;
	}

	$max_visible = 5;

	return array(
		'mode'        => 'preview',
		'items'       => array_slice( $items, 0, $max_visible ),
		'max_visible' => $max_visible,
		'more_count'  => max( 0, count( $items ) - $max_visible ),
	);
}

/**
 * Build product-card component props from a WC product (data only; no layout HTML).
 *
 * @param WC_Product $product Product instance.
 * @return array<string, mixed>
 */
function tailwindscore_adapter_product_card_props( $product ): array {
	if ( ! class_exists( 'WC_Product' ) || ! $product instanceof WC_Product ) {
		return array();
	}

	$image_id = (int) $product->get_image_id();
	$primary  = tailwindscore_adapter_product_card_media_attachment( $image_id, $product->get_name() );
	$secondary = tailwindscore_adapter_product_card_secondary_media( $product );

	$badges = tailwindscore_adapter_product_badges_props( $product );
	$swatches = tailwindscore_adapter_product_card_swatches( $product );
	$actions_html = (string) apply_filters( 'tailwindscore/adapter/product-card/actions_html', '', $product );
	$primary_action_html = '' !== $actions_html ? $actions_html : tailwindscore_adapter_product_card_primary_action_html( $product );

	$props = array(
		'permalink'    => get_permalink( $product->get_id() ),
		'title'        => $product->get_name(),
		'title_tag'    => 'h3',
		'media'        => array(
			'primary'       => $primary,
			'secondary'     => $secondary,
			'aspect_ratio'  => '4 / 5',
			'hover_enabled' => '' !== (string) $secondary['url'],
		),
		'image_url'    => (string) $primary['url'],
		'image_alt'    => (string) $primary['alt'],
		'image_width'  => $primary['width'],
		'image_height' => $primary['height'],
		'badge'        => ! empty( $badges ) ? $badges[0] : null,
		'badges'       => $badges,
		'swatches'     => $swatches,
		'actions'      => array(
			'primary'            => array(
				'html' => $primary_action_html,
				'type' => $product->is_type( 'simple' ) ? 'add_to_cart' : 'link',
			),
			'wishlist_slot_html' => '',
			'quick_slot_html'    => '',
			'reveal_mode'        => 'hover',
		),
		'collection'   => array(
			'density'        => 'default',
			'card_style'     => 'quiet',
			'mobile_compact' => false,
		),
		'price'        => tailwindscore_adapter_price_props( $product ),
		'actions_html' => $primary_action_html,
	);

	/**
	 * Filter product-card component props.
	 *
	 * @param array<string, mixed> $props   Args for `tailwindscore_component( 'product-card', … )`.
	 * @param WC_Product           $product Product.
	 */
	return apply_filters( 'tailwindscore/adapter/product/card_props', $props, $product );
}
