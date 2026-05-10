<?php
/**
 * Escaping helpers for component markup (icons, slots).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Allowed tags for inline SVG icons passed into components.
 *
 * @return array<string, array<string, bool>>
 */
function tailwindscore_kses_allowed_svg_tags(): array {
	$attrs = array(
		'class'           => true,
		'aria-hidden'     => true,
		'aria-label'      => true,
		'focusable'       => true,
		'role'            => true,
		'xmlns'           => true,
		'width'           => true,
		'height'          => true,
		'viewbox'         => true,
		'fill'            => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'd'               => true,
		'cx'              => true,
		'cy'              => true,
		'r'               => true,
		'x'               => true,
		'y'               => true,
		'points'          => true,
		'rx'              => true,
		'vector-effect'   => true,
	);

	return array(
		'svg'    => $attrs,
		'path'   => $attrs,
		'circle' => $attrs,
		'rect'   => $attrs,
		'line'   => $attrs,
		'polyline' => $attrs,
		'polygon' => $attrs,
		'g'      => $attrs,
	);
}

/**
 * Sanitize icon HTML (typically inline SVG).
 *
 * @param string $html Raw HTML.
 */
function tailwindscore_kses_icon_html( string $html ): string {
	$allowed = tailwindscore_kses_allowed_svg_tags();

	/**
	 * Filter allowed tags for component icon HTML.
	 *
	 * @param array<string, array<string, bool>> $allowed Tag map.
	 */
	$allowed = apply_filters( 'tailwindscore/component/icon_kses_allowed', $allowed );

	return wp_kses( $html, $allowed );
}

/**
 * Allow compact commerce slot markup (buttons inside card footer).
 *
 * @param string $html Raw HTML.
 */
function tailwindscore_kses_actions_slot( string $html ): string {
	$allowed = wp_kses_allowed_html( 'post' );

	if ( ! isset( $allowed['form'] ) ) {
		$allowed['form'] = array(
			'action'  => true,
			'method'  => true,
			'class'   => true,
			'id'      => true,
			'enctype' => true,
			'name'    => true,
			'data-*'  => true,
		);
	}

	if ( ! isset( $allowed['button'] ) ) {
		$allowed['button'] = array(
			'type'       => true,
			'class'      => true,
			'name'       => true,
			'value'      => true,
			'disabled'   => true,
			'form'       => true,
			'aria-label' => true,
			'data-*'     => true,
		);
	}

	if ( ! isset( $allowed['input'] ) ) {
		$allowed['input'] = array(
			'type'          => true,
			'id'            => true,
			'class'         => true,
			'name'          => true,
			'value'         => true,
			'min'           => true,
			'max'           => true,
			'step'          => true,
			'placeholder'   => true,
			'inputmode'     => true,
			'autocomplete'  => true,
			'aria-label'    => true,
			'aria-describedby' => true,
			'checked'       => true,
			'disabled'      => true,
			'readonly'      => true,
			'required'      => true,
			'size'          => true,
			'data-*'        => true,
		);
	}

	if ( ! isset( $allowed['label'] ) ) {
		$allowed['label'] = array(
			'for'     => true,
			'class'   => true,
			'id'      => true,
			'data-*'  => true,
		);
	}

	if ( ! isset( $allowed['select'] ) ) {
		$allowed['select'] = array(
			'id'              => true,
			'class'           => true,
			'name'            => true,
			'aria-label'      => true,
			'aria-describedby'=> true,
			'disabled'        => true,
			'multiple'        => true,
			'required'        => true,
			'data-*'          => true,
		);
	}

	if ( ! isset( $allowed['option'] ) ) {
		$allowed['option'] = array(
			'value'     => true,
			'selected'  => true,
			'disabled'  => true,
			'class'     => true,
			'data-*'    => true,
		);
	}

	/**
	 * Filter allowed tags for product-card actions slot.
	 *
	 * @param array<string, array<string, bool>> $allowed Tags.
	 */
	$allowed = apply_filters( 'tailwindscore/kses/actions_slot', $allowed );

	return wp_kses( $html, $allowed );
}
