<?php
/**
 * Swatch 展示默认：Color、Style 使用图片优先链路（无图则颜色）。
 *
 * 可被子主题同一 filter 更高 priority 覆盖，或与返回数组合并。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * 默认列入「图片优先」的属性（全局属性常见 slug：pa_color、pa_style）。
 */
add_filter(
	'tailwindscore/swatches/image_attributes',
	static function ( $list ): array {
		$defaults = array(
			'pa_color',
			'pa_style',
		);
		$prev = is_array( $list ) ? $list : array();

		return array_values( array_unique( array_merge( $defaults, $prev ) ) );
	},
	5,
	1
);
