<?php
/**
 * 通过 `woocommerce_dropdown_variation_attribute_options_html` 注入 Swatch UI + 保留原生 `<select>`。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @param array<string, mixed> $args WooCommerce dropdown args.
 * @return array<int, array<string, mixed>>
 */
function tailwindscore_swatch_build_items( array $args ): array {
	$product   = isset( $args['product'] ) && $args['product'] instanceof WC_Product ? $args['product'] : false;
	$attribute = isset( $args['attribute'] ) && is_string( $args['attribute'] ) ? $args['attribute'] : '';

	if ( '' === $attribute || ! $product || ! $product->is_type( 'variable' ) ) {
		return array();
	}

	/** @var WC_Product_Variable $variable */
	$variable = $product;

	$options       = isset( $args['options'] ) && is_array( $args['options'] ) ? $args['options'] : array();
	$raw_selected  = isset( $args['selected'] ) && false !== $args['selected'] ? (string) $args['selected'] : '';
	$sel_slug      = '' !== $raw_selected ? sanitize_title( $raw_selected ) : '';

	if ( empty( $options ) ) {
		$attributes = $product->get_variation_attributes();
		if ( isset( $attributes[ $attribute ] ) && is_array( $attributes[ $attribute ] ) ) {
			$options = $attributes[ $attribute ];
		}
	}

	if ( empty( $options ) ) {
		return array();
	}

	$presentation = tailwindscore_get_attribute_presentation_config( $attribute, $product );
	$items        = array();

	if ( taxonomy_exists( $attribute ) ) {
		$terms = wc_get_product_terms(
			$product->get_id(),
			$attribute,
			array( 'fields' => 'all' )
		);
		if ( ! is_array( $terms ) ) {
			return array();
		}
		foreach ( $terms as $term ) {
			if ( ! $term instanceof WP_Term || ! in_array( $term->slug, $options, true ) ) {
				continue;
			}
			$label = (string) apply_filters( 'woocommerce_variation_option_name', $term->name, $term, $attribute, $product );
			$item  = tailwindscore_swatch_map_term_item(
				$variable,
				$attribute,
				$term,
				$term->slug,
				( '' !== $sel_slug && $sel_slug === $term->slug ),
				$label,
				$presentation
			);
			if ( array() !== $item ) {
				$items[] = $item;
			}
		}
	} else {
		foreach ( $options as $option ) {
			if ( ! is_string( $option ) ) {
				continue;
			}
			$slug  = sanitize_title( $option );
			$label = (string) apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product );
			$items[] = array(
				'type'     => 'text',
				'value'    => $option,
				'label'    => $label,
				'selected' => ( '' !== $raw_selected && ( $raw_selected === $option || $sel_slug === $slug ) ),
			);
		}
	}

	return $items;
}

/**
 * @param array{type: string, fallback: string} $presentation Presentation config.
 * @return array<string, mixed>
 */
function tailwindscore_swatch_map_term_item(
	WC_Product_Variable $product,
	string $attribute,
	WP_Term $term,
	string $term_slug,
	bool $selected,
	string $label,
	array $presentation
): array {
	$override = get_term_meta( $term->term_id, 'tailwindscore_swatch_display', true );
	$type_in  = $presentation['type'];
	if ( is_string( $override ) && in_array( $override, array( 'text', 'color', 'image', 'auto' ), true ) ) {
		$type_in = $override;
	}

	$resolved = tailwindscore_swatch_resolve_taxonomy_term_visual( $product, $attribute, $term, $term_slug );
	$img      = isset( $resolved['image'] ) && is_array( $resolved['image'] ) ? $resolved['image'] : array();
	$thumb    = isset( $img['thumb_url'] ) && is_string( $img['thumb_url'] ) ? $img['thumb_url'] : '';
	$has_img  = '' !== $thumb;

	$cp = isset( $resolved['color_primary'] ) ? trim( (string) $resolved['color_primary'] ) : '';
	$cs = isset( $resolved['color_secondary'] ) ? trim( (string) $resolved['color_secondary'] ) : '';

	$layer = isset( $resolved['layer'] ) ? (string) $resolved['layer'] : 'text';

	$fallback = $presentation['fallback'];

	$base = array(
		'value'               => $term_slug,
		'label'               => $label,
		'selected'            => $selected,
		'presentation_layer'  => $layer,
	);

	if ( 'color' === $type_in ) {
		if ( '' !== $cp ) {
			return array_merge(
				$base,
				array(
					'type'            => 'color',
					'color_primary'   => $cp,
					'color_secondary' => $cs,
				)
			);
		}
		if ( $has_img ) {
			return tailwindscore_swatch_item_image_stack( $base, $img, $cp, $cs );
		}
		return array_merge( $base, array( 'type' => 'text' ) );
	}

	if ( 'text' === $type_in ) {
		return array_merge( $base, array( 'type' => 'text' ) );
	}

	if ( 'image' === $type_in ) {
		if ( $has_img ) {
			return tailwindscore_swatch_item_image_stack( $base, $img, $cp, $cs );
		}
		if ( 'color' === $fallback && '' !== $cp ) {
			return array_merge( $base, array( 'type' => 'color', 'color_primary' => $cp, 'color_secondary' => $cs ) );
		}
		return array_merge( $base, array( 'type' => 'text' ) );
	}

	// auto
	if ( $has_img ) {
		return tailwindscore_swatch_item_image_stack( $base, $img, $cp, $cs );
	}
	if ( '' !== $cp ) {
		return array_merge(
			$base,
			array(
				'type'            => 'color',
				'color_primary'   => $cp,
				'color_secondary' => $cs,
			)
		);
	}

	return array_merge( $base, array( 'type' => 'text' ) );
}

/**
 * @param array<string, mixed> $base Base item fields.
 * @param array<string, mixed> $img  Resolver image layers.
 * @return array<string, mixed>
 */
function tailwindscore_swatch_item_image_stack( array $base, array $img, string $color_primary, string $color_secondary ): array {
	return array_merge(
		$base,
		array(
			'type'               => 'image_stack',
			'image_url'          => isset( $img['thumb_url'] ) ? (string) $img['thumb_url'] : '',
			'image_srcset'       => isset( $img['thumb_srcset'] ) ? (string) $img['thumb_srcset'] : '',
			'image_sizes'        => isset( $img['thumb_sizes'] ) ? (string) $img['thumb_sizes'] : '',
			'preview_url'        => isset( $img['preview_url'] ) ? (string) $img['preview_url'] : '',
			'preview_srcset'     => isset( $img['preview_srcset'] ) ? (string) $img['preview_srcset'] : '',
			'preview_sizes'      => isset( $img['preview_sizes'] ) ? (string) $img['preview_sizes'] : '',
			'image_alt'          => isset( $img['alt'] ) ? (string) $img['alt'] : '',
			'attachment_id'      => isset( $img['attachment_id'] ) ? (int) $img['attachment_id'] : 0,
			'color_chip_primary' => $color_primary,
			'color_chip_secondary' => $color_secondary,
		)
	);
}

/**
 * @param string               $html Original `<select>` markup.
 * @param array<string, mixed> $args WC args.
 */
function tailwindscore_filter_variation_dropdown_swatch_html( string $html, array $args ): string {
	if ( ! apply_filters( 'tailwindscore/swatches/enabled', true, isset( $args['product'] ) ? $args['product'] : false ) ) {
		return $html;
	}

	$product   = isset( $args['product'] ) && $args['product'] instanceof WC_Product ? $args['product'] : false;
	$attribute = isset( $args['attribute'] ) && is_string( $args['attribute'] ) ? $args['attribute'] : '';

	if ( ! $product || '' === $attribute ) {
		return $html;
	}

	if ( ! tailwindscore_swatch_enabled_for_attribute( $attribute, $product ) ) {
		return $html;
	}

	$items = tailwindscore_swatch_build_items( $args );
	if ( array() === $items ) {
		return $html;
	}

	$id = isset( $args['id'] ) && is_string( $args['id'] ) && '' !== $args['id']
		? $args['id']
		: sanitize_title( $attribute );

	$name = isset( $args['name'] ) && is_string( $args['name'] ) && '' !== $args['name']
		? $args['name']
		: 'attribute_' . sanitize_title( $attribute );

	$aria = isset( $args['aria-label'] ) && is_string( $args['aria-label'] ) && '' !== $args['aria-label']
		? $args['aria-label']
		: wc_attribute_label( $attribute, $product );

	$html = tailwindscore_swatch_patch_select_class( $html );

	ob_start();
	tailwindscore_component(
		'swatches/swatch-group',
		array(
			'select_id'   => $id,
			'select_name' => $name,
			'attribute'   => $attribute,
			'aria_label'  => $aria,
			'items'       => $items,
			'inner_html'  => $html,
		)
	);
	$wrapped = (string) ob_get_clean();

	/**
	 * Filter full swatch field markup (group + native select).
	 *
	 * @param string               $wrapped Wrapped HTML.
	 * @param string               $html    Original select HTML.
	 * @param array<string, mixed> $args    WC args.
	 */
	return (string) apply_filters( 'tailwindscore/swatches/dropdown_html', $wrapped, $html, $args );
}

/**
 * 为 `<select>` 追加 `ts-swatch-native-select`，便于样式与运行时定位。
 */
function tailwindscore_swatch_patch_select_class( string $html ): string {
	if ( ! preg_match( '/<select\s+([^>]+)>/', $html, $m ) ) {
		return $html;
	}
	$attrs = $m[1];
	if ( preg_match( '/class\s*=\s*(["\'])(.*?)\1/', $attrs, $cm ) ) {
		$new_class = trim( $cm[2] . ' ts-swatch-native-select' );
		$attrs     = preg_replace(
			'/class\s*=\s*(["\'])(.*?)\1/',
			'class=' . $cm[1] . esc_attr( $new_class ) . $cm[1],
			$attrs,
			1
		);
	} else {
		$attrs = 'class="ts-swatch-native-select" ' . $attrs;
	}

	return (string) preg_replace( '/<select\s+[^>]+>/', '<select ' . trim( $attrs ) . '>', $html, 1 );
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'tailwindscore_filter_variation_dropdown_swatch_html', 10, 2 );
