<?php
/**
 * Front-end assets - Vite dev server or production manifest.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		wp_register_script( 'tailwindscore-config', '', array(), null, true );
		wp_enqueue_script( 'tailwindscore-config' );
		wp_add_inline_script(
			'tailwindscore-config',
			'window.tailwindscoreConfig=' . wp_json_encode(
				array(
					'restNonce' => wp_create_nonce( 'wp_rest' ),
				)
			) . ';',
			'before'
		);

		if ( tailwindscore_is_vite_dev() ) {
			tailwindscore_enqueue_vite_dev();
			return;
		}

		tailwindscore_enqueue_vite_prod();
	},
	20
);

add_filter(
	'script_loader_tag',
	static function ( string $tag, string $handle ): string {
		if ( 'tailwindscore-vite-client' !== $handle && ! str_starts_with( $handle, 'tailwindscore-entry-' ) ) {
			return $tag;
		}
		if ( str_contains( $tag, 'type="module"' ) || str_contains( $tag, "type='module'" ) ) {
			return $tag;
		}
		if ( preg_match( '/\stype=(["\'])/', $tag ) ) {
			return (string) preg_replace( '/\stype=(["\'])[^"\']*\1/', ' type="module"', $tag, 1 );
		}
		return (string) preg_replace( '/<script\b/', '<script type="module"', $tag, 1 );
	},
	10,
	3
);

/**
 * Development assets - requires `npm run dev` and wp-config constant.
 */
function tailwindscore_enqueue_vite_dev(): void {
	$origin = tailwindscore_vite_origin();

	wp_enqueue_script(
		'tailwindscore-vite-client',
		$origin . '/@vite/client',
		array(),
		null,
		true
	);
	wp_script_add_data( 'tailwindscore-vite-client', 'type', 'module' );

	$style_bundle = tailwindscore_active_style_bundle();
	$style_assets = tailwindscore_vite_bundle_assets( $style_bundle );

	if ( isset( $style_assets['style'] ) ) {
		$style_handle = 'tailwindscore-style-' . $style_bundle;

		wp_enqueue_style(
			$style_handle,
			$origin . '/' . $style_assets['style'],
			array(),
			null
		);
		tailwindscore_presets_attach_inline_style( $style_handle );
	}

	foreach ( tailwindscore_active_script_bundles() as $bundle ) {
		$assets = tailwindscore_vite_bundle_assets( $bundle );

		if ( isset( $assets['script'] ) ) {
			wp_enqueue_script(
				'tailwindscore-entry-' . $bundle,
				$origin . '/' . $assets['script'],
				array( 'tailwindscore-vite-client', 'tailwindscore-config' ),
				null,
				true
			);
			wp_script_add_data( 'tailwindscore-entry-' . $bundle, 'type', 'module' );
		}
	}
}

/**
 * Production assets - requires `npm run build`.
 */
function tailwindscore_enqueue_vite_prod(): void {
	$manifest = tailwindscore_get_vite_manifest();
	$version  = TAILWINDSCORE_VERSION;

	$style_bundle = tailwindscore_active_style_bundle();
	$style_assets = tailwindscore_vite_bundle_assets( $style_bundle );

	if ( isset( $style_assets['style'] ) ) {
		$css_file = tailwindscore_vite_asset_file( $manifest, $style_assets['style'] );
		if ( is_string( $css_file ) && '' !== $css_file ) {
			$style_handle = 'tailwindscore-style-' . $style_bundle;

			wp_enqueue_style(
				$style_handle,
				TAILWINDSCORE_URI . 'dist/' . $css_file,
				array(),
				$version
			);
			tailwindscore_presets_attach_inline_style( $style_handle );
		}
	}

	foreach ( tailwindscore_active_script_bundles() as $bundle ) {
		$assets = tailwindscore_vite_bundle_assets( $bundle );

		if ( isset( $assets['script'] ) ) {
			$js_file = tailwindscore_vite_asset_file( $manifest, $assets['script'] );
			if ( is_string( $js_file ) && '' !== $js_file ) {
				foreach ( tailwindscore_vite_asset_css_files( $manifest, $assets['script'] ) as $index => $css_file ) {
					wp_enqueue_style(
						'tailwindscore-entry-css-' . $bundle . '-' . (int) $index,
						TAILWINDSCORE_URI . 'dist/' . $css_file,
						array(),
						$version
					);
				}

				wp_enqueue_script(
					'tailwindscore-entry-' . $bundle,
					TAILWINDSCORE_URI . 'dist/' . $js_file,
					array( 'tailwindscore-config' ),
					$version,
					true
				);
				wp_script_add_data( 'tailwindscore-entry-' . $bundle, 'type', 'module' );
			}
		}
	}
}

/**
 * Bundle source map for Vite dev/prod asset resolution.
 *
 * @return array<string, array{script?:string,style?:string}>
 */
function tailwindscore_vite_bundle_assets( string $bundle ): array {
	$bundles = array(
		'base' => array(
			'script' => 'src/ts/bootstrap.ts',
			'style'  => 'src/css/entries/base.css',
		),
		'product' => array(
			'script' => 'src/ts/entries/product.ts',
			'style'  => 'src/css/entries/product.css',
		),
		'archive' => array(
			'script' => 'src/ts/entries/archive.ts',
			'style'  => 'src/css/entries/archive.css',
		),
		'checkout' => array(
			'script' => 'src/ts/entries/checkout.ts',
			'style'  => 'src/css/entries/checkout.css',
		),
		'account' => array(
			'script' => 'src/ts/entries/account.ts',
			'style'  => 'src/css/entries/account.css',
		),
	);

	return $bundles[ $bundle ] ?? array();
}

/**
 * Resolve active script bundle list for the current request.
 *
 * @return string[]
 */
function tailwindscore_active_script_bundles(): array {
	$bundles = array( 'base' );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$bundles[] = 'product';
	}

	if ( tailwindscore_is_product_archive_view() ) {
		$bundles[] = 'archive';
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$bundles[] = 'checkout';
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() && ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) ) {
		$bundles[] = 'account';
	}

	return array_values( array_unique( $bundles ) );
}

/**
 * Resolve the single CSS bundle to load for the current request.
 */
function tailwindscore_active_style_bundle(): string {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return 'checkout';
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() && ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) ) {
		return 'account';
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		return 'product';
	}

	if ( tailwindscore_is_product_archive_view() ) {
		return 'archive';
	}

	return 'base';
}

/**
 * Whether the current request is a WooCommerce archive/discovery surface.
 */
function tailwindscore_is_product_archive_view(): bool {
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return true;
	}

	if ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) {
		return true;
	}

	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		return true;
	}

	if ( function_exists( 'is_product_tag' ) && is_product_tag() ) {
		return true;
	}

	return false;
}
