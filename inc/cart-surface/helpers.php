<?php
/**
 * Cart surface helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Ensure WooCommerce cart/session are available in surface contexts such as REST requests.
 */
function tailwindscore_cart_surface_bootstrap(): void {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	if ( function_exists( 'wc_load_cart' ) && null === WC()->cart ) {
		wc_load_cart();
	}

	if ( null !== WC()->cart ) {
		return;
	}

	if ( null === WC()->session && method_exists( WC(), 'initialize_session' ) ) {
		WC()->initialize_session();
	}

	if ( method_exists( WC(), 'initialize_cart' ) ) {
		WC()->initialize_cart();
	}
}

/**
 * Render cart-surface template to string.
 *
 * @param string               $template Relative path inside template-parts/cart-surface without .php.
 * @param array<string, mixed> $args     Template args.
 */
function tailwindscore_cart_surface_render( string $template, array $args = array() ): string {
	$template = trim( preg_replace( '#[^a-zA-Z0-9\-/]#', '', $template ), '/' );
	if ( '' === $template || str_contains( $template, '..' ) ) {
		return '';
	}

	$file = locate_template( 'template-parts/cart-surface/' . $template . '.php', false, false );
	if ( '' === $file ) {
		return '';
	}

	ob_start();
	require $file;
	return (string) ob_get_clean();
}

/**
 * Cart endpoint base URL.
 */
function tailwindscore_cart_surface_endpoint_url(): string {
	return home_url( '/' . trim( rest_get_url_prefix(), '/' ) . '/tailwindscore/v1/cart-surface' );
}

/**
 * Get item count.
 */
function tailwindscore_cart_surface_count(): int {
	tailwindscore_cart_surface_bootstrap();

	if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
		return 0;
	}

	return (int) WC()->cart->get_cart_contents_count();
}

/**
 * Get cart items formatted for SSR surface.
 *
 * @return list<array<string, mixed>>
 */
function tailwindscore_cart_surface_items(): array {
	tailwindscore_cart_surface_bootstrap();

	if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
		return array();
	}

	$items = array();

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$product = $cart_item['data'] ?? null;
		if ( ! $product instanceof WC_Product || ! $product->exists() || $cart_item['quantity'] <= 0 ) {
			continue;
		}

		$product_id  = (int) $product->get_id();
		$permalink   = (string) ( $product->is_visible() ? $product->get_permalink( $cart_item ) : '' );
		$thumbnail   = get_the_post_thumbnail_url( $product_id, 'woocommerce_thumbnail' );
		$remove_url  = wc_get_cart_remove_url( $cart_item_key );
		$category    = '';
		$terms       = taxonomy_exists( 'product_cat' ) ? get_the_terms( $product_id, 'product_cat' ) : false;
		if ( is_array( $terms ) && ! empty( $terms ) ) {
			$first = array_shift( $terms );
			if ( $first instanceof WP_Term ) {
				$category = $first->name;
			}
		}

		$items[] = array(
			'key'        => (string) $cart_item_key,
			'product_id' => $product_id,
			'title'      => $product->get_name(),
			'url'        => $permalink,
			'image'      => $thumbnail ? (string) $thumbnail : '',
			'quantity'   => (int) $cart_item['quantity'],
			'price_html' => (string) WC()->cart->get_product_price( $product ),
			'subtotal'   => (string) WC()->cart->get_product_subtotal( $product, (int) $cart_item['quantity'] ),
			'category'   => $category,
			'remove_url' => (string) $remove_url,
			'max_qty'    => (int) $product->get_max_purchase_quantity(),
		);
	}

	return $items;
}

/**
 * Whether a cart item key exists in the current cart contents.
 */
function tailwindscore_cart_surface_has_item_key( string $key ): bool {
	tailwindscore_cart_surface_bootstrap();

	if ( '' === $key || ! function_exists( 'WC' ) || null === WC()->cart ) {
		return false;
	}

	$cart = WC()->cart->get_cart();

	return isset( $cart[ $key ] );
}

/**
 * Surface payload.
 *
 * @return array<string, mixed>
 */
function tailwindscore_cart_surface_payload(): array {
	tailwindscore_cart_surface_bootstrap();

	$items = tailwindscore_cart_surface_items();
	$count = tailwindscore_cart_surface_count();

	$subtotal_html = '';
	$cart_url      = home_url( '/cart/' );
	$checkout_url  = home_url( '/checkout/' );
	$shop_url      = home_url( '/' );

	if ( function_exists( 'WC' ) && null !== WC()->cart ) {
		$subtotal_html = WC()->cart->get_cart_subtotal();
		if ( function_exists( 'wc_get_cart_url' ) ) {
			$cart_url = wc_get_cart_url();
		}
		if ( function_exists( 'wc_get_checkout_url' ) ) {
			$checkout_url = wc_get_checkout_url();
		}
		if ( function_exists( 'wc_get_page_permalink' ) ) {
			$shop = wc_get_page_permalink( 'shop' );
			if ( $shop ) {
				$shop_url = $shop;
			}
		}
	}

	return array(
		'count'         => $count,
		'items'         => $items,
		'subtotal_html' => (string) $subtotal_html,
		'cart_url'      => (string) $cart_url,
		'checkout_url'  => (string) $checkout_url,
		'shop_url'      => (string) $shop_url,
		'is_empty'      => 0 === $count,
	);
}

/**
 * Render cart badge fragment.
 */
function tailwindscore_cart_surface_badge_html( ?int $count = null ): string {
	$count = null === $count ? tailwindscore_cart_surface_count() : $count;

	ob_start();
	?>
	<span class="ts-cart-trigger__badge" data-cart-count <?php echo $count > 0 ? '' : 'hidden'; ?>><?php echo esc_html( (string) $count ); ?></span>
	<?php
	return trim( (string) ob_get_clean() );
}

/**
 * Render cart subtotal fragment.
 */
function tailwindscore_cart_surface_subtotal_html( ?array $payload = null ): string {
	$payload = is_array( $payload ) ? $payload : tailwindscore_cart_surface_payload();

	ob_start();
	?>
	<span class="ts-cart-summary__value" data-cart-subtotal><?php echo wp_kses_post( (string) ( $payload['subtotal_html'] ?? '' ) ); ?></span>
	<?php
	return trim( (string) ob_get_clean() );
}

/**
 * Unified SSR fragments for cart surface hydration.
 *
 * @return array{drawer_html:string,badge_html:string,subtotal_html:string,count:int}
 */
function tailwindscore_cart_surface_fragments(): array {
	$payload = tailwindscore_cart_surface_payload();

	return array(
		'drawer_html'   => tailwindscore_cart_surface_render( 'cart-drawer', $payload ),
		'badge_html'    => tailwindscore_cart_surface_badge_html( (int) $payload['count'] ),
		'subtotal_html' => tailwindscore_cart_surface_subtotal_html( $payload ),
		'count'         => (int) $payload['count'],
	);
}

/**
 * Governed cart drawer trust copy.
 *
 * @return array<string, string>
 */
function tailwindscore_cart_surface_copy(): array {
	return array(
		'validation_title' => tailwindscore_content_surface_text( 'cart-drawer-validation-title', __( 'Please review your bag', 'tailwindscore' ) ),
		'loading_message'  => tailwindscore_content_surface_text( 'cart-drawer-loading-message', __( 'Updating bag', 'tailwindscore' ) ),
		'update_error'     => tailwindscore_content_surface_text( 'cart-drawer-update-error-message', __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ) ),
		'item_updated'     => tailwindscore_content_surface_text( 'cart-drawer-item-updated-message', __( 'Bag updated', 'tailwindscore' ) ),
		'item_removed'     => tailwindscore_content_surface_text( 'cart-drawer-item-removed-message', __( 'Removed from bag', 'tailwindscore' ) ),
	);
}

/**
 * Persist cart/session changes after REST mutations.
 */
function tailwindscore_cart_surface_commit(): void {
	tailwindscore_cart_surface_bootstrap();

	if ( ! function_exists( 'WC' ) || null === WC()->cart || null === WC()->session ) {
		return;
	}

	if ( method_exists( WC()->cart, 'calculate_totals' ) ) {
		WC()->cart->calculate_totals();
	}

	if ( method_exists( WC()->cart, 'set_session' ) ) {
		WC()->cart->set_session();
	}

	if ( method_exists( WC()->session, 'set_customer_session_cookie' ) ) {
		WC()->session->set_customer_session_cookie( true );
	}

	if ( method_exists( WC()->session, 'save_data' ) ) {
		WC()->session->save_data();
	}

	do_action( 'woocommerce_set_cart_cookies', true );
}
