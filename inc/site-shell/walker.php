<?php
/**
 * Site shell nav walker.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

class TailwindScore_Site_Shell_Walker extends Walker_Nav_Menu {
	/**
	 * Start submenu level.
	 *
	 * @param string   $output Output buffer.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Nav args.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ): void {
		$indent = str_repeat( "\t", (int) $depth );
		$class  = 0 === (int) $depth ? 'sub-menu ts-nav__submenu' : 'sub-menu ts-nav__submenu ts-nav__submenu--nested';
		$output .= "\n{$indent}<ul class=\"" . esc_attr( $class ) . "\">\n";
	}

	/**
	 * Start menu item.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Nav args.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
		$indent        = str_repeat( "\t", (int) $depth );
		$classes       = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children  = in_array( 'menu-item-has-children', $classes, true );
		$item_classes  = array( 'menu-item', 'ts-nav__item' );
		$link_classes  = array( 'ts-nav__link' );
		$submenu_id    = 'ts-nav-submenu-' . (int) $item->ID;
		$current       = in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-ancestor', $classes, true );

		if ( $has_children ) {
			$item_classes[] = 'ts-nav__item--has-children';
		}
		if ( 0 === (int) $depth ) {
			$item_classes[] = 'ts-nav__item--top';
		}
		if ( $current ) {
			$item_classes[] = 'is-current';
		}

		$output .= $indent . '<li class="' . esc_attr( implode( ' ', array_map( 'sanitize_html_class', array_unique( $item_classes ) ) ) ) . '">';
		$output .= '<div class="ts-nav__item-row">';

		$atts = array(
			'class' => implode( ' ', $link_classes ),
			'href'  => ! empty( $item->url ) ? $item->url : '',
		);

		if ( $current ) {
			$atts['aria-current'] = 'page';
		}
		if ( $has_children ) {
			$atts['data-nav-trigger'] = $submenu_id;
		}
		if ( ! empty( $item->target ) ) {
			$atts['target'] = $item->target;
		}
		if ( ! empty( $item->xfn ) ) {
			$atts['rel'] = $item->xfn;
		}

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( '' === $value ) {
				continue;
			}
			$attributes .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= '<a' . $attributes . '>' . esc_html( $title ) . '</a>';

		if ( $has_children ) {
			$output .= '<button class="ts-nav__submenu-toggle" type="button" aria-expanded="false" aria-controls="' . esc_attr( $submenu_id ) . '" data-nav-submenu-toggle="' . esc_attr( $submenu_id ) . '">';
			$output .= '<span class="screen-reader-text">' . esc_html( sprintf( __( 'Toggle %s submenu', 'tailwindscore' ), wp_strip_all_tags( $title ) ) ) . '</span>';
			$output .= '<span class="ts-nav__submenu-icon ts-commerce-icon ts-commerce-icon--nav" aria-hidden="true">' . tailwindscore_icon( 'chevron-down', array( 'class' => 'ts-icon--nav' ) ) . '</span>';
			$output .= '</button>';
		}

		$output .= '</div>';

		if ( $has_children ) {
			$output .= '<div class="ts-nav__submenu-shell" id="' . esc_attr( $submenu_id ) . '">';
		}
	}

	/**
	 * End menu item.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Nav args.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ): void {
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $has_children ) {
			$output .= '</div>';
		}

		$output .= "</li>\n";
	}
}
