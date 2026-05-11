<?php
/**
 * Feature-owned cart integration.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Restore the logged-in WP user for same-origin cart REST requests when REST auth drops to user 0.
 */
function tailwindscore_cart_surface_restore_current_user(): void {
	if ( is_user_logged_in() || ! defined( 'LOGGED_IN_COOKIE' ) ) {
		return;
	}

	$cookie = $_COOKIE[ LOGGED_IN_COOKIE ] ?? '';
	if ( ! is_string( $cookie ) || '' === $cookie ) {
		return;
	}

	$user_id = wp_validate_auth_cookie( wp_unslash( $cookie ), 'logged_in' );
	if ( ! is_int( $user_id ) || $user_id < 1 ) {
		return;
	}

	wp_set_current_user( $user_id );
}

/**
 * Ensure WooCommerce cart/session are available in surface contexts such as REST requests.
 */
function tailwindscore_cart_surface_bootstrap(): void {
	if ( ! function_exists( 'WC' ) ) {
		return;
	}

	tailwindscore_cart_surface_restore_current_user();

	if ( null === WC()->session && method_exists( WC(), 'initialize_session' ) ) {
		WC()->initialize_session();
	}

	if (
		method_exists( WC(), 'initialize_cart' )
		&& (
			null === WC()->cart
			|| ! WC()->cart instanceof WC_Cart
			|| null === WC()->customer
			|| ! WC()->customer instanceof WC_Customer
		)
	) {
		WC()->initialize_cart();
	}

	if ( null !== WC()->cart && method_exists( WC()->cart, 'get_cart' ) ) {
		WC()->cart->get_cart();
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

function tailwindscore_cart_surface_endpoint_url(): string {
	return home_url( '/' . trim( rest_get_url_prefix(), '/' ) . '/tailwindscore/v1/cart-surface' );
}

function tailwindscore_cart_surface_override( string $key ): ?string {
	$setting_id = 'ts_surface_' . str_replace( '-', '_', $key );
	$value      = get_theme_mod( $setting_id, null );

	return is_string( $value ) && '' !== trim( $value ) ? $value : null;
}

function tailwindscore_cart_copy_text( string $key, string $default = '' ): string {
	$override = tailwindscore_cart_surface_override( $key );

	return null !== $override ? $override : $default;
}

function tailwindscore_cart_rest_error_message( string $key ): string {
	$messages = array(
		'cart_unavailable'   => tailwindscore_cart_copy_text( 'cart-drawer-update-error-message', __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ) ),
		'cart_item_missing'  => tailwindscore_cart_copy_text( 'cart-drawer-update-error-message', __( 'This item is no longer available in your bag. Please refresh and try again.', 'tailwindscore' ) ),
		'invalid_product'    => __( 'This item could not be added right now. Please try again.', 'tailwindscore' ),
	);

	return $messages[ $key ] ?? $messages['cart_unavailable'];
}

/**
 * Ensure cart surface responses are never reused across sessions or stale cart states.
 */
function tailwindscore_cart_surface_send_private_headers( WP_REST_Response $response ): WP_REST_Response {
	$response->header( 'Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private' );
	$response->header( 'Pragma', 'no-cache' );
	$response->header( 'Expires', 'Wed, 11 Jan 1984 05:00:00 GMT' );
	$response->header( 'Vary', 'Cookie' );

	return $response;
}

function tailwindscore_cart_surface_notice_message( string $type = 'error' ): string {
	if ( ! function_exists( 'wc_get_notices' ) ) {
		return '';
	}

	$notices  = wc_get_notices( $type );
	$messages = array();

	foreach ( (array) $notices as $notice ) {
		$message = $notice['notice'] ?? '';
		if ( is_scalar( $message ) ) {
			$message = wc_clean( wp_strip_all_tags( (string) $message ) );
		} else {
			$message = '';
		}

		if ( '' !== $message ) {
			$messages[] = $message;
		}
	}

	return implode( ' ', array_unique( $messages ) );
}

/**
 * @return array<string, string>
 */
function tailwindscore_cart_empty_state_copy(): array {
	return array(
		'eyebrow' => tailwindscore_cart_copy_text( 'empty-state-cart-eyebrow', __( 'Your bag', 'tailwindscore' ) ),
		'title'   => tailwindscore_cart_copy_text( 'empty-state-cart-title', __( 'Your cart is empty', 'tailwindscore' ) ),
		'message' => tailwindscore_cart_copy_text( 'empty-state-cart-message', __( 'Begin with a considered selection and return here when you are ready to check out.', 'tailwindscore' ) ),
		'action'  => tailwindscore_cart_copy_text( 'checkout-empty-action-label', __( 'Continue shopping', 'tailwindscore' ) ),
	);
}

function tailwindscore_cart_surface_count(): int {
	tailwindscore_cart_surface_bootstrap();

	if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
		return 0;
	}

	return (int) WC()->cart->get_cart_contents_count();
}

/**
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

		$product_id = (int) $product->get_id();
		$permalink  = (string) ( $product->is_visible() ? $product->get_permalink( $cart_item ) : '' );
		$thumbnail  = get_the_post_thumbnail_url( $product_id, 'woocommerce_thumbnail' );
		$remove_url = wc_get_cart_remove_url( $cart_item_key );
		$category   = '';
		$terms      = taxonomy_exists( 'product_cat' ) ? get_the_terms( $product_id, 'product_cat' ) : false;
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

function tailwindscore_cart_surface_has_item_key( string $key ): bool {
	tailwindscore_cart_surface_bootstrap();

	if ( '' === $key || ! function_exists( 'WC' ) || null === WC()->cart ) {
		return false;
	}

	$cart = WC()->cart->get_cart();

	return isset( $cart[ $key ] );
}

/**
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

function tailwindscore_cart_surface_badge_html( ?int $count = null ): string {
	$count = null === $count ? tailwindscore_cart_surface_count() : $count;

	ob_start();
	?>
	<span class="ts-cart-trigger__badge" data-cart-count <?php echo $count > 0 ? '' : 'hidden'; ?>><?php echo esc_html( (string) $count ); ?></span>
	<?php
	return trim( (string) ob_get_clean() );
}

function tailwindscore_cart_surface_subtotal_html( ?array $payload = null ): string {
	$payload = is_array( $payload ) ? $payload : tailwindscore_cart_surface_payload();

	ob_start();
	?>
	<span class="ts-cart-summary__value" data-cart-subtotal><?php echo wp_kses_post( (string) ( $payload['subtotal_html'] ?? '' ) ); ?></span>
	<?php
	return trim( (string) ob_get_clean() );
}

/**
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
 * @return array<string, string>
 */
function tailwindscore_cart_surface_copy(): array {
	return array(
		'validation_title' => tailwindscore_cart_copy_text( 'cart-drawer-validation-title', __( 'Please review your bag', 'tailwindscore' ) ),
		'loading_message'  => tailwindscore_cart_copy_text( 'cart-drawer-loading-message', __( 'Updating bag', 'tailwindscore' ) ),
		'update_error'     => tailwindscore_cart_copy_text( 'cart-drawer-update-error-message', __( 'We could not update the bag just now. Please try again.', 'tailwindscore' ) ),
		'item_updated'     => tailwindscore_cart_copy_text( 'cart-drawer-item-updated-message', __( 'Bag updated', 'tailwindscore' ) ),
		'item_removed'     => tailwindscore_cart_copy_text( 'cart-drawer-item-removed-message', __( 'Removed from bag', 'tailwindscore' ) ),
	);
}

/**
 * @return array<string, string>
 */
function tailwindscore_cart_surface_feedback_overrides(): array {
	$mapping = array(
		'validation_title' => 'cart-drawer-validation-title',
		'loading_message'  => 'cart-drawer-loading-message',
		'update_error'     => 'cart-drawer-update-error-message',
		'item_updated'     => 'cart-drawer-item-updated-message',
		'item_removed'     => 'cart-drawer-item-removed-message',
	);
	$overrides = array();

	foreach ( $mapping as $slot => $surface_key ) {
		$value = tailwindscore_cart_surface_override( $surface_key );
		if ( null !== $value ) {
			$overrides[ $slot ] = $value;
		}
	}

	return $overrides;
}

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

add_action(
	'template_redirect',
	static function (): void {
		if ( wp_doing_ajax() || is_admin() ) {
			return;
		}

		if ( 'POST' !== strtoupper( (string) $_SERVER['REQUEST_METHOD'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			return;
		}

		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		$product_id = isset( $_POST['add-to-cart'] ) ? absint( wp_unslash( (string) $_POST['add-to-cart'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $product_id < 1 ) {
			return;
		}

		if ( function_exists( 'wc_notice_count' ) && wc_notice_count( 'error' ) > 0 ) {
			return;
		}

		$permalink = get_permalink( $product_id );
		if ( ! is_string( $permalink ) || '' === $permalink ) {
			return;
		}

		wp_safe_redirect( $permalink, 303 );
		exit;
	},
	99
);

add_action(
	'woocommerce_before_cart',
	static function (): void {
		do_action( 'tailwindscore/woocommerce/cart/before_cart' );
	},
	5
);

add_action(
	'rest_api_init',
	static function (): void {
		register_rest_route(
			'tailwindscore/v1',
			'/cart-surface',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'permission_callback' => '__return_true',
					'callback'            => static function (): WP_REST_Response {
						tailwindscore_cart_surface_bootstrap();
						return tailwindscore_cart_surface_send_private_headers( new WP_REST_Response( tailwindscore_cart_surface_fragments() ) );
					},
				),
			)
		);

		register_rest_route(
			'tailwindscore/v1',
			'/cart-surface/add',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'permission_callback' => '__return_true',
					'args'                => array(
						'product_id'   => array(
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'required'          => true,
						),
						'quantity'     => array(
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'required'          => false,
						),
						'variation_id' => array(
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'required'          => false,
						),
						'variation'    => array(
							'type'     => 'object',
							'required' => false,
						),
					),
					'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
						tailwindscore_cart_surface_bootstrap();

						if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'cart_unavailable' ) ), 400 );
						}

						$product_id   = (int) $request->get_param( 'product_id' );
						$quantity     = max( 1, (int) $request->get_param( 'quantity' ) );
						$variation_id = absint( (string) $request->get_param( 'variation_id' ) );
						$variation    = $request->get_param( 'variation' );
						$variation    = is_array( $variation ) ? array_map( 'sanitize_text_field', $variation ) : array();

						if ( $product_id < 1 ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'invalid_product' ) ), 400 );
						}

						$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
						if ( false === $added ) {
							$message = tailwindscore_cart_surface_notice_message( 'error' );

							return new WP_REST_Response(
								array(
									'message' => '' !== $message ? $message : tailwindscore_cart_rest_error_message( 'invalid_product' ),
								),
								400
							);
						}

						tailwindscore_cart_surface_commit();
						return tailwindscore_cart_surface_send_private_headers( new WP_REST_Response( tailwindscore_cart_surface_fragments() ) );
					},
				),
			)
		);

		register_rest_route(
			'tailwindscore/v1',
			'/cart-surface/update',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'permission_callback' => '__return_true',
					'args'                => array(
						'key'      => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
						'quantity' => array(
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'required'          => true,
						),
					),
					'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
						tailwindscore_cart_surface_bootstrap();

						if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'cart_unavailable' ) ), 400 );
						}

						$key      = (string) $request->get_param( 'key' );
						$quantity = (int) $request->get_param( 'quantity' );

						if ( ! tailwindscore_cart_surface_has_item_key( $key ) ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'cart_item_missing' ) ), 404 );
						}

						WC()->cart->set_quantity( $key, $quantity, true );
						tailwindscore_cart_surface_commit();
						return tailwindscore_cart_surface_send_private_headers( new WP_REST_Response( tailwindscore_cart_surface_fragments() ) );
					},
				),
			)
		);

		register_rest_route(
			'tailwindscore/v1',
			'/cart-surface/remove',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'permission_callback' => '__return_true',
					'args'                => array(
						'key' => array(
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
							'required'          => true,
						),
					),
					'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
						tailwindscore_cart_surface_bootstrap();

						if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'cart_unavailable' ) ), 400 );
						}

						$key = (string) $request->get_param( 'key' );
						if ( ! tailwindscore_cart_surface_has_item_key( $key ) ) {
							return new WP_REST_Response( array( 'message' => tailwindscore_cart_rest_error_message( 'cart_item_missing' ) ), 404 );
						}

						WC()->cart->remove_cart_item( $key );
						tailwindscore_cart_surface_commit();
						return tailwindscore_cart_surface_send_private_headers( new WP_REST_Response( tailwindscore_cart_surface_fragments() ) );
					},
				),
			)
		);
	}
);
