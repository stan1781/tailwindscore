<?php
/**
 * Attribute → Presentation 映射。
 *
 * 推荐用法（两种展示方案）：
 * 1. `tailwindscore/swatches/image_attributes` — 列出需「图片优先、无图则颜色」的属性 slug；
 * 2. 未列入的属性 — 固定文本按钮。
 *
 * 可选：`tailwindscore/swatches/presentation_map` 对单个属性精细覆盖（优先级高于 image_attributes）。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array<string, array{type?: string, fallback?: string}>
 */
function tailwindscore_swatches_presentation_map(): array {
	$default = array();

	/**
	 * 全局 Attribute → 展示策略（单属性精细覆盖，优先级高于 image_attributes）。
	 *
	 * @param array<string, array<string, string>> $map 映射表。
	 */
	return (array) apply_filters( 'tailwindscore/swatches/presentation_map', $default );
}

/**
 * 需「图片优先 → 无图则颜色」的属性名列表（与 WC attribute 名一致，如 pa_color）。
 *
 * @return string[]
 */
function tailwindscore_swatches_image_attributes(): array {
	$default = array();

	/**
	 * 指定哪些属性使用图片方案（变体图 / term 图优先，否则色块）。
	 * 未出现在此列表中的属性 → 文本按钮。
	 *
	 * @param string[] $slugs Attribute names e.g. pa_color, pa_finish.
	 */
	$list = apply_filters( 'tailwindscore/swatches/image_attributes', $default );

	if ( ! is_array( $list ) ) {
		return array();
	}

	$out = array();
	foreach ( $list as $slug ) {
		if ( is_string( $slug ) && '' !== $slug ) {
			$out[] = $slug;
		}
	}

	return array_values( array_unique( $out ) );
}

/**
 * @param string           $attribute WC attribute 名（如 pa_color）。
 * @param WC_Product|false $product   当前 variable 商品。
 * @return array{type: string, fallback: string}
 */
function tailwindscore_get_attribute_presentation_config( string $attribute, $product ): array {
	$map = tailwindscore_swatches_presentation_map();

	if ( isset( $map[ $attribute ] ) && is_array( $map[ $attribute ] ) ) {
		$entry = $map[ $attribute ];
		$type  = isset( $entry['type'] ) && is_string( $entry['type'] ) ? $entry['type'] : '';
		if ( in_array( $type, array( 'image', 'color', 'text', 'auto' ), true ) ) {
			$fallback = isset( $entry['fallback'] ) && is_string( $entry['fallback'] ) ? $entry['fallback'] : 'color';
			if ( ! in_array( $fallback, array( 'color', 'text' ), true ) ) {
				$fallback = 'color';
			}
			$config = array(
				'type'     => $type,
				'fallback' => $fallback,
			);

			return (array) apply_filters( 'tailwindscore/swatches/presentation_config', $config, $attribute, $product );
		}
	}

	if ( in_array( $attribute, tailwindscore_swatches_image_attributes(), true ) ) {
		$config = array(
			'type'     => 'image',
			'fallback' => 'color',
		);

		return (array) apply_filters( 'tailwindscore/swatches/presentation_config', $config, $attribute, $product );
	}

	$config = array(
		'type'     => 'text',
		'fallback' => 'color',
	);

	return (array) apply_filters( 'tailwindscore/swatches/presentation_config', $config, $attribute, $product );
}

/**
 * @param string           $attribute Attribute name.
 * @param WC_Product|false $product   Product.
 */
function tailwindscore_swatch_default_type_for_attribute( string $attribute, $product ): string {
	$config = tailwindscore_get_attribute_presentation_config( $attribute, $product );

	return $config['type'];
}
