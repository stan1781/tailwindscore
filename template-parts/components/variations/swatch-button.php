<?php
/**
 * 兼容入口 — 请改用 `swatches/swatch-button`。
 *
 * @package TailwindScore
 *
 * @var array<string, mixed> $args
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

tailwindscore_component( 'swatches/swatch-button', (array) ( $args ?? array() ) );
