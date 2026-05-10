<?php
/**
 * Attribute term meta — Swatch 展示用元数据（不修改 WC variation 数据结构）。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * 为每个全局属性 taxonomy 注册 term meta。
 */
function tailwindscore_register_swatch_term_meta(): void {
	if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
		return;
	}

	$string_args = array(
		'type'              => 'string',
		'single'            => true,
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest'      => true,
		'auth_callback'     => static function (): bool {
			return current_user_can( 'manage_woocommerce' );
		},
	);

	$int_args = array(
		'type'              => 'integer',
		'single'            => true,
		'sanitize_callback' => 'absint',
		'show_in_rest'      => true,
		'auth_callback'     => static function (): bool {
			return current_user_can( 'manage_woocommerce' );
		},
	);

	foreach ( wc_get_attribute_taxonomies() as $row ) {
		if ( ! isset( $row->attribute_name ) || ! is_string( $row->attribute_name ) ) {
			continue;
		}
		$taxonomy = wc_attribute_taxonomy_name( $row->attribute_name );
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		/** 专用 variation swatch 图（优先级 1） */
		register_term_meta( $taxonomy, 'tailwindscore_swatch_variation_image', $int_args );
		register_term_meta( $taxonomy, 'tailwindscore_swatch_color', $string_args );
		register_term_meta( $taxonomy, 'tailwindscore_swatch_color_secondary', $string_args );
		register_term_meta( $taxonomy, 'tailwindscore_swatch_image', $int_args );
		register_term_meta( $taxonomy, 'tailwindscore_swatch_display', $string_args );
	}
}

add_action( 'init', 'tailwindscore_register_swatch_term_meta', 21 );
