<?php
/**
 * Archive / shop loop — layout flow hooks only (no UI markup).
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * @return array{image_id:int,url:string,alt:string,width:int|string,height:int|string}
 */
function tailwindscore_archive_product_card_media_attachment( int $image_id, string $fallback_alt = '' ): array {
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
function tailwindscore_archive_product_card_secondary_media( WC_Product $product ): array {
	$gallery_ids = array_map( 'intval', (array) $product->get_gallery_image_ids() );
	$image_id    = isset( $gallery_ids[0] ) ? (int) $gallery_ids[0] : 0;

	return tailwindscore_archive_product_card_media_attachment( $image_id, $product->get_name() );
}

function tailwindscore_archive_product_card_primary_action_html( WC_Product $product ): string {
	$is_simple = $product->is_type( 'simple' );
	$url       = $is_simple ? $product->add_to_cart_url() : get_permalink( $product->get_id() );
	$text      = $product->add_to_cart_text();
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
		'href'          => (string) $url,
		'data-quantity' => '1',
		'class'         => implode( ' ', array_filter( $classes ) ),
		'aria-label'    => is_string( $label ) && '' !== $label ? $label : $text,
		'rel'           => 'nofollow',
	);

	if ( $is_simple ) {
		$attrs['data-product_id']      = (string) $product->get_id();
		$attrs['data-product_sku']     = (string) $product->get_sku();
		$attrs['data-success_message'] = '';
	}

	return sprintf(
		'<a %1$s>%2$s</a>',
		tailwindscore_attributes_html( $attrs ),
		esc_html( (string) $text )
	);
}

/**
 * @return list<array{label:string,variant:string,size:string}>
 */
function tailwindscore_archive_product_badges( WC_Product $product ): array {
	$badges = array();

	if ( $product->is_on_sale() ) {
		$badges[] = array(
			'label'   => __( 'Sale', 'tailwindscore' ),
			'variant' => 'sale',
			'size'    => 'sm',
		);
	}

	if ( $product->is_featured() ) {
		$badges[] = array(
			'label'   => __( 'Featured', 'tailwindscore' ),
			'variant' => 'success',
			'size'    => 'sm',
		);
	}

	if ( ! $product->is_in_stock() ) {
		$badges[] = array(
			'label'   => __( 'Out of stock', 'tailwindscore' ),
			'variant' => 'neutral',
			'size'    => 'sm',
		);
	} elseif ( $product->managing_stock() && $product->get_stock_quantity() !== null ) {
		$qty = $product->get_stock_quantity();
		if ( is_numeric( $qty ) && (int) $qty > 0 && (int) $qty <= (int) apply_filters( 'tailwindscore/adapter/badge/low_stock_threshold', 3, $product ) ) {
			$badges[] = array(
				'label'   => sprintf( __( 'Only %d left', 'tailwindscore' ), (int) $qty ),
				'variant' => 'neutral',
				'size'    => 'sm',
			);
		}
	}

	$new_days = (int) apply_filters( 'tailwindscore/adapter/badge/new_product_days', 30, $product );
	if ( $new_days > 0 ) {
		$created = $product->get_date_created();
		if ( $created && $created->getTimestamp() >= time() - ( $new_days * DAY_IN_SECONDS ) ) {
			$badges[] = array(
				'label'   => __( 'New', 'tailwindscore' ),
				'variant' => 'new',
				'size'    => 'sm',
			);
		}
	}

	return $badges;
}

/**
 * @return array{mode:string,items:array<int,array<string,mixed>>,max_visible:int,more_count:int}
 */
function tailwindscore_archive_product_card_swatches( WC_Product $product ): array {
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
		$resolved  = tailwindscore_swatch_resolve_taxonomy_term_visual( $product, $chosen_attribute, $term, $term->slug );
		$image     = isset( $resolved['image'] ) && is_array( $resolved['image'] ) ? $resolved['image'] : array();
		$preview   = isset( $image['preview_url'] ) ? (string) $image['preview_url'] : '';
		$thumb     = isset( $image['thumb_url'] ) ? (string) $image['thumb_url'] : '';
		$color_1   = isset( $resolved['color_primary'] ) ? trim( (string) $resolved['color_primary'] ) : '';
		$color_2   = isset( $resolved['color_secondary'] ) ? trim( (string) $resolved['color_secondary'] ) : '';
		$kind      = '' !== $thumb || '' !== $preview ? 'image' : ( '' !== $color_1 ? 'color' : 'text' );

		$items[] = array(
			'value'              => $term->slug,
			'label'              => $term->name,
			'kind'               => $kind,
			'preview_image'      => '' !== $preview ? $preview : $thumb,
			'thumb_image'        => $thumb,
			'color_primary'      => $color_1,
			'color_secondary'    => $color_2,
			'selected'           => 0 === count( $items ),
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
 * Extension points for catalog layout (columns, sidebar, toolbar).
 * Keep markup out of this file — use filters/callbacks registered here or in features/.
 */
add_action(
	'woocommerce_before_shop_loop',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/archive/before_shop_loop' );
	},
	5
);

add_action(
	'woocommerce_after_shop_loop',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/archive/after_shop_loop' );
	},
	15
);

add_action(
	'woocommerce_init',
	static function (): void {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	},
	20
);

/**
 * Render archive result count without WooCommerce's paginated-guard short circuit.
 */
function tailwindscore_render_archive_result_count(): void {
	global $wp_query;

	$total = (int) wc_get_loop_prop( 'total' );
	if ( $total <= 0 && $wp_query instanceof WP_Query ) {
		$total = (int) $wp_query->found_posts;
	}

	if ( $total <= 0 ) {
		return;
	}

	$per_page = (int) wc_get_loop_prop( 'per_page' );
	if ( $per_page <= 0 && $wp_query instanceof WP_Query ) {
		$per_page = (int) $wp_query->get( 'posts_per_page' );
	}

	$current_page = (int) wc_get_loop_prop( 'current_page' );
	if ( $current_page <= 0 ) {
		$current_page = max( 1, (int) get_query_var( 'paged', 1 ) );
	}

	$default_orderby = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
	$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$orderby         = 'menu_order' === $orderby ? '' : (string) $orderby;

	$catalog_orderedby_options = apply_filters(
		'woocommerce_catalog_orderedby',
		array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sorted by popularity', 'woocommerce' ),
			'rating'     => __( 'Sorted by average rating', 'woocommerce' ),
			'date'       => __( 'Sorted by latest', 'woocommerce' ),
			'price'      => __( 'Sorted by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sorted by price: high to low', 'woocommerce' ),
			'relevance'  => __( 'Sorted by relevance', 'woocommerce' ),
		)
	);

	wc_get_template(
		'loop/result-count.php',
		array(
			'total'     => $total,
			'per_page'  => $per_page,
			'current'   => $current_page,
			'orderedby' => isset( $catalog_orderedby_options[ $orderby ] ) ? (string) $catalog_orderedby_options[ $orderby ] : '',
		)
	);
}

/**
 * Render archive ordering without WooCommerce's paginated-guard short circuit.
 */
function tailwindscore_render_archive_catalog_ordering(): void {
	$show_default_orderby = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );

	$catalog_orderby_options = apply_filters(
		'woocommerce_catalog_orderby',
		array(
			'menu_order' => __( 'Default sorting', 'woocommerce' ),
			'popularity' => __( 'Sort by popularity', 'woocommerce' ),
			'rating'     => __( 'Sort by average rating', 'woocommerce' ),
			'date'       => __( 'Sort by latest', 'woocommerce' ),
			'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
			'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
		)
	);

	$default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
	$orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( wc_get_loop_prop( 'is_search' ) || is_search() ) {
		$catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );
		unset( $catalog_orderby_options['menu_order'] );
	}

	if ( ! $show_default_orderby ) {
		unset( $catalog_orderby_options['menu_order'] );
	}

	if ( ! wc_review_ratings_enabled() ) {
		unset( $catalog_orderby_options['rating'] );
	}

	if ( is_array( $orderby ) ) {
		$orderby = current( array_intersect( $orderby, array_keys( $catalog_orderby_options ) ) );
	}

	$orderby = is_string( $orderby ) ? $orderby : '';

	if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
		$orderby = (string) current( array_keys( $catalog_orderby_options ) );
	}

	wc_get_template(
		'loop/orderby.php',
		array(
			'catalog_orderby_options' => $catalog_orderby_options,
			'orderby'                 => $orderby,
			'show_default_orderby'    => $show_default_orderby,
			'use_label'               => false,
		)
	);
}

/**
 * Whether the current archive is narrowed by active discovery filters.
 */
function tailwindscore_archive_has_active_filters(): bool {
	$keys = array_keys( wp_unslash( $_GET ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	foreach ( $keys as $key ) {
		if ( in_array( $key, array( 'orderby', 'paged', 'page' ), true ) ) {
			continue;
		}

		$value = wp_unslash( $_GET[ $key ] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( is_array( $value ) ) {
			$value = implode( '', array_map( 'strval', $value ) );
		}

		if ( '' === trim( (string) $value ) ) {
			continue;
		}

		return true;
	}

	return false;
}

/**
 * Base collection URL for the current archive context.
 */
function tailwindscore_archive_base_url(): string {
	if ( is_product_taxonomy() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term ) {
			$link = get_term_link( $term );
			if ( ! is_wp_error( $link ) ) {
				return (string) $link;
			}
		}
	}

	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return (string) wc_get_page_permalink( 'shop' );
	}

	return (string) home_url( '/' );
}

/**
 * Resolve unified archive empty-state copy context.
 */
function tailwindscore_archive_empty_state_context(): string {
	$stock_filters = array(
		(string) wp_unslash( $_GET['stock_status'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		(string) wp_unslash( $_GET['filter_stock_status'] ?? '' ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	);

	foreach ( $stock_filters as $filter ) {
		if ( '' !== $filter ) {
			return 'archive-out-of-stock';
		}
	}

	if ( tailwindscore_archive_has_active_filters() ) {
		return 'archive-filtered-empty';
	}

	if ( is_product_taxonomy() ) {
		return 'archive-category-empty';
	}

	return 'archive-empty';
}

/**
 * Render archive empty discovery state.
 */
function tailwindscore_render_archive_empty_state(): void {
	$context   = tailwindscore_archive_empty_state_context();
	$copy      = tailwindscore_feedback_empty_state_copy( $context );
	$shop_url  = function_exists( 'wc_get_page_permalink' ) ? (string) wc_get_page_permalink( 'shop' ) : (string) home_url( '/' );
	$base_url  = tailwindscore_archive_base_url();
	$is_filter = 'archive-filtered-empty' === $context || 'archive-out-of-stock' === $context;

	$actions = array();

	if ( $is_filter ) {
		$actions[] = sprintf(
			'<a class="ts-btn ts-btn--secondary" href="%s">%s</a>',
			esc_url( $base_url ),
			esc_html__( 'Clear filters', 'tailwindscore' )
		);
	}

	$actions[] = sprintf(
		'<a class="ts-btn ts-btn--primary" href="%s">%s</a>',
		esc_url( $shop_url ),
		esc_html__( 'Browse all collections', 'tailwindscore' )
	);

	echo '<div class="ts-archive-discovery__empty">';
	tailwindscore_feedback_part(
		'empty-state',
		array(
			'icon_name'    => 'search',
			'eyebrow'      => $copy['eyebrow'] ?? '',
			'title'        => $copy['title'] ?? '',
			'message'      => $copy['message'] ?? '',
			'actions_html' => implode( '', $actions ),
		)
	);
	echo '</div>';
}

add_action(
	'woocommerce_init',
	static function (): void {
		remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );
		add_action( 'woocommerce_no_products_found', 'tailwindscore_render_archive_empty_state', 10 );
	},
	20
);
