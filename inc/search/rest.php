<?php
/**
 * Predictive search REST route.
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
			'/search',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => '__return_true',
				'args'                => array(
					'q' => array(
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
						'required'          => false,
					),
				),
				'callback'            => static function ( WP_REST_Request $request ): WP_REST_Response {
					$query   = (string) $request->get_param( 'q' );
					$results = tailwindscore_get_predictive_search_results( $query );
					$html    = tailwindscore_search_render(
						'predictive-results',
						array(
							'query'   => $results['query'],
							'results' => $results,
						)
					);

					return new WP_REST_Response(
						array(
							'query'   => $results['query'],
							'results' => $results,
							'html'    => $html,
						)
					);
				},
			)
		);
	}
);
