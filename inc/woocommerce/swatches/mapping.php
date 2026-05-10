<?php
/**
 * Swatch 开关过滤 — 不做属性名称推断。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @param string           $attribute Attribute name.
 * @param WC_Product|false $product Product.
 */
function tailwindscore_swatch_enabled_for_attribute( string $attribute, $product ): bool {
	$enabled = (bool) apply_filters( 'tailwindscore/swatches/enabled', true, $product );

	/**
	 * Filter whether swatch UI wraps this attribute’s `<select>`.
	 *
	 * @param bool             $show      Default true when global swatches on.
	 * @param string           $attribute Attribute name.
	 * @param WC_Product|false $product   Product.
	 */
	return (bool) apply_filters( 'tailwindscore/swatches/attribute_enabled', $enabled, $attribute, $product );
}
