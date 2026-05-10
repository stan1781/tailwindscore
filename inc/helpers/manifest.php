<?php
/**
 * Vite manifest helpers.
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

/**
 * Whether local Vite dev server should be used (wp-config.php constant).
 */
function tailwindscore_is_vite_dev(): bool {
	return defined( 'TAILWINDSCORE_VITE_DEV' ) && constant( 'TAILWINDSCORE_VITE_DEV' );
}

/**
 * Dev server origin — adjust if vite.config.ts uses another host/port.
 */
function tailwindscore_vite_origin(): string {
	/**
	 * Filter the Vite client origin.
	 *
	 * @param string $origin Full origin URL without trailing slash.
	 */
	return apply_filters( 'tailwindscore/vite/origin', 'http://127.0.0.1:5173' );
}

/**
 * Manifest path inside dist/ after `vite build`.
 */
function tailwindscore_vite_manifest_path(): string {
	return TAILWINDSCORE_PATH . 'dist/.vite/manifest.json';
}

/**
 * Parsed manifest array or empty on failure.
 *
 * @return array<string, mixed>
 */
function tailwindscore_get_vite_manifest(): array {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$path = tailwindscore_vite_manifest_path();

	if ( ! is_readable( $path ) ) {
		$cache = array();
		return $cache;
	}

	$json = file_get_contents( $path );
	if ( false === $json ) {
		$cache = array();
		return $cache;
	}

	$data = json_decode( $json, true );
	$cache = is_array( $data ) ? $data : array();

	return $cache;
}

/**
 * Resolve built entry from manifest.
 *
 * @param array<string, mixed> $manifest Manifest.
 * @param string               $key      Manifest entry key.
 * @return array{file?:string,css?:string[]}|null
 */
function tailwindscore_vite_entry( array $manifest, string $key ): ?array {
	if ( isset( $manifest[ $key ] ) && is_array( $manifest[ $key ] ) ) {
		return $manifest[ $key ];
	}

	return null;
}

/**
 * Resolve built asset file for a manifest entry.
 */
function tailwindscore_vite_asset_file( array $manifest, string $key ): ?string {
	$entry = tailwindscore_vite_entry( $manifest, $key );
	if ( null === $entry ) {
		return null;
	}

	$file = $entry['file'] ?? null;

	return is_string( $file ) && '' !== $file ? $file : null;
}

/**
 * Resolve built CSS files for a manifest entry.
 *
 * @return string[]
 */
function tailwindscore_vite_asset_css_files( array $manifest, string $key ): array {
	$entry = tailwindscore_vite_entry( $manifest, $key );
	if ( null === $entry ) {
		return array();
	}

	$css_files = $entry['css'] ?? null;
	if ( ! is_array( $css_files ) ) {
		return array();
	}

	return array_values(
		array_filter(
			$css_files,
			static fn ( $value ): bool => is_string( $value ) && '' !== $value
		)
	);
}
