<?php
/**
 * Cart surface REST routes.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

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
						return new WP_REST_Response( tailwindscore_cart_surface_fragments() );
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
						'product_id' => array(
							'type'              => 'integer',
							'sanitize_callback' => 'absint',
							'required'          => true,
						),
						'quantity'   => array(
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
							'type'              => 'object',
							'required'          => false,
						),
					),
					'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
						tailwindscore_cart_surface_bootstrap();

						if ( ! function_exists( 'WC' ) || null === WC()->cart ) {
							return new WP_REST_Response( array( 'message' => 'Cart unavailable.' ), 400 );
						}

						$product_id = (int) $request->get_param( 'product_id' );
						$quantity   = max( 1, (int) $request->get_param( 'quantity' ) );
						$variation_id = absint( (string) $request->get_param( 'variation_id' ) );
						$variation    = $request->get_param( 'variation' );
						$variation    = is_array( $variation ) ? array_map( 'sanitize_text_field', $variation ) : array();

						if ( $product_id < 1 ) {
							return new WP_REST_Response( array( 'message' => 'Invalid product.' ), 400 );
						}

						$added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
						if ( false === $added ) {
							return new WP_REST_Response(
								array(
									'message' => wc_get_notices( 'error' ),
								),
								400
							);
						}

						tailwindscore_cart_surface_commit();
						return new WP_REST_Response( tailwindscore_cart_surface_fragments() );
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
							return new WP_REST_Response( array( 'message' => 'Cart unavailable.' ), 400 );
						}

						$key      = (string) $request->get_param( 'key' );
						$quantity = (int) $request->get_param( 'quantity' );

						if ( ! tailwindscore_cart_surface_has_item_key( $key ) ) {
							return new WP_REST_Response( array( 'message' => 'Cart item not found.' ), 404 );
						}

						WC()->cart->set_quantity( $key, $quantity, true );
						tailwindscore_cart_surface_commit();
						return new WP_REST_Response( tailwindscore_cart_surface_fragments() );
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
							return new WP_REST_Response( array( 'message' => 'Cart unavailable.' ), 400 );
						}

						$key = (string) $request->get_param( 'key' );
						if ( ! tailwindscore_cart_surface_has_item_key( $key ) ) {
							return new WP_REST_Response( array( 'message' => 'Cart item not found.' ), 404 );
						}

						WC()->cart->remove_cart_item( $key );
						tailwindscore_cart_surface_commit();
						return new WP_REST_Response( tailwindscore_cart_surface_fragments() );
					},
				),
			)
		);
	}
);
