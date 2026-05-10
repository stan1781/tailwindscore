<?php
/**
 * Empty / default discovery state.
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args Args.
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

echo tailwindscore_search_render( 'default-state', (array) $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
?>
