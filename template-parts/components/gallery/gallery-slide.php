<?php
/**
 * Single gallery slide (SSR). Expects WC-shaped markup from the runtime adapter.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Must include `html` string.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

$html = isset( $args['html'] ) && is_string( $args['html'] ) ? $args['html'] : '';

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC-filtered gallery HTML.
echo $html;
