<?php
/**
 * Swatch 图片 / 色值解析 — 按 presentation priority，不修改 WC 数据结构。
 *
 * Priority:
 * 1. Term meta `tailwindscore_swatch_variation_image`（variation swatch 专用附件）
 * 2. 匹配该属性值的 variation `image_id`（variation featured）
 * 3. Term meta `tailwindscore_swatch_image`（属性词条图）
 * 4. Term color meta
 * 5. text（无图无色）
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * WC variation 属性键，与 dropdown `name` 一致。
 */
function tailwindscore_swatch_variation_attr_key( string $attribute ): string {
	return 'attribute_' . sanitize_title( $attribute );
}

/**
 * Variation 对象属性数组的兼容键列表。
 *
 * WooCommerce 运行时通常使用 `pa_color` 这类键；表单字段名则是 `attribute_pa_color`。
 * 这里同时兼容两种形式，避免因内部返回格式差异而丢失 variation featured image。
 *
 * @return string[]
 */
function tailwindscore_swatch_variation_attr_keys( string $attribute ): array {
	$normalized = sanitize_title( $attribute );

	return array_values(
		array_unique(
			array(
				$normalized,
				tailwindscore_swatch_variation_attr_key( $attribute ),
			)
		)
	);
}

/**
 * 首个「包含当前词条」且带有图片的变体 attachment ID。
 */
function tailwindscore_swatch_resolve_variation_featured_attachment_id(
	WC_Product_Variable $product,
	string $attribute,
	string $term_slug
): int {
	$keys = tailwindscore_swatch_variation_attr_keys( $attribute );

	foreach ( $product->get_children() as $child_id ) {
		$vid = (int) $child_id;
		if ( $vid <= 0 ) {
			continue;
		}
		$v = wc_get_product( $vid );
		if ( ! $v || ! $v->is_type( 'variation' ) ) {
			continue;
		}
		$attrs = $v->get_attributes();
		if ( ! is_array( $attrs ) ) {
			continue;
		}
		$val = '';
		foreach ( $keys as $key ) {
			if ( isset( $attrs[ $key ] ) ) {
				$val = (string) $attrs[ $key ];
				break;
			}
		}
		if ( $val !== $term_slug ) {
			continue;
		}
		$img = (int) $v->get_image_id();
		if ( $img > 0 ) {
			return $img;
		}
	}

	return 0;
}

/**
 * @return array{
 *   priority: string,
 *   attachment_id: int,
 *   thumb_url: string,
 *   thumb_srcset: string,
 *   thumb_sizes: string,
 *   preview_url: string,
 *   preview_srcset: string,
 *   preview_sizes: string,
 *   alt: string,
 *   color_primary: string,
 *   color_secondary: string
 * }
 */
/**
 * @param string $priority_label Internal label for debugging / data-attribute.
 */
function tailwindscore_swatch_attachment_visual_layers( int $attachment_id, string $priority_label = '' ): array {
	$empty = array(
		'priority'        => '',
		'attachment_id'   => 0,
		'thumb_url'       => '',
		'thumb_srcset'    => '',
		'thumb_sizes'     => '',
		'preview_url'     => '',
		'preview_srcset'  => '',
		'preview_sizes'   => '',
		'alt'             => '',
		'color_primary'   => '',
		'color_secondary' => '',
	);

	if ( $attachment_id <= 0 ) {
		return $empty;
	}

	$thumb_size   = 'woocommerce_gallery_thumbnail';
	$preview_size = apply_filters( 'tailwindscore/swatches/preview_image_size', 'woocommerce_single' );

	$thumb_url = wp_get_attachment_image_url( $attachment_id, $thumb_size ) ?: '';
	$prev_url  = wp_get_attachment_image_url( $attachment_id, $preview_size ) ?: $thumb_url;

	return array(
		'priority'        => $priority_label,
		'attachment_id'   => $attachment_id,
		'thumb_url'       => is_string( $thumb_url ) ? $thumb_url : '',
		'thumb_srcset'    => (string) wp_get_attachment_image_srcset( $attachment_id, $thumb_size ),
		'thumb_sizes'     => (string) wp_get_attachment_image_sizes( $attachment_id, $thumb_size ),
		'preview_url'     => is_string( $prev_url ) ? $prev_url : '',
		'preview_srcset'  => (string) wp_get_attachment_image_srcset( $attachment_id, $preview_size ),
		'preview_sizes'   => (string) wp_get_attachment_image_sizes( $attachment_id, $preview_size ),
		'alt'             => (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
		'color_primary'   => '',
		'color_secondary' => '',
	);
}

/**
 * @return array{
 *   layer: string,
 *   image: array<string, string|int>,
 *   color_primary: string,
 *   color_secondary: string
 * }
 */
function tailwindscore_swatch_resolve_taxonomy_term_visual(
	WC_Product_Variable $product,
	string $attribute,
	WP_Term $term,
	string $term_slug
): array {
	$tid = (int) $term->term_id;

	$p1 = (int) get_term_meta( $tid, 'tailwindscore_swatch_variation_image', true );
	if ( $p1 > 0 ) {
		$img_layers = tailwindscore_swatch_attachment_visual_layers( $p1, 'variation_swatch_image' );

		return array(
			'layer'           => 'variation_swatch_image',
			'image'           => $img_layers,
			'color_primary'   => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color', true ) : '',
			'color_secondary' => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) : '',
		);
	}

	$p2 = tailwindscore_swatch_resolve_variation_featured_attachment_id( $product, $attribute, $term_slug );
	if ( $p2 > 0 ) {
		$img_layers = tailwindscore_swatch_attachment_visual_layers( $p2, 'variation_featured_image' );

		return array(
			'layer'           => 'variation_featured_image',
			'image'           => $img_layers,
			'color_primary'   => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color', true ) : '',
			'color_secondary' => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) : '',
		);
	}

	$p3 = (int) get_term_meta( $tid, 'tailwindscore_swatch_image', true );
	if ( $p3 > 0 ) {
		$img_layers = tailwindscore_swatch_attachment_visual_layers( $p3, 'term_image' );

		return array(
			'layer'           => 'term_image',
			'image'           => $img_layers,
			'color_primary'   => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color', true ) : '',
			'color_secondary' => is_string( get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) ) ? (string) get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) : '',
		);
	}

	$primary   = is_string( get_term_meta( $tid, 'tailwindscore_swatch_color', true ) ) ? trim( (string) get_term_meta( $tid, 'tailwindscore_swatch_color', true ) ) : '';
	$secondary = is_string( get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) ) ? trim( (string) get_term_meta( $tid, 'tailwindscore_swatch_color_secondary', true ) ) : '';

	if ( '' !== $primary ) {
		return array(
			'layer'           => 'color',
			'image'           => tailwindscore_swatch_attachment_visual_layers( 0 ),
			'color_primary'   => $primary,
			'color_secondary' => $secondary,
		);
	}

	return array(
		'layer'           => 'text',
		'image'           => tailwindscore_swatch_attachment_visual_layers( 0 ),
		'color_primary'   => '',
		'color_secondary' => '',
	);
}
