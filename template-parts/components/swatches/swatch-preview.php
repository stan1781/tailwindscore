<?php
/**
 * Hover 大图预览宿主 — 由 `swatch-image-runtime` 填充 src / 定位。
 *
 * @package TailwindScore
 */

declare(strict_types=1);

defined( 'ABSPATH' ) || exit;

?>
<div class="ts-swatch-preview" data-ts-swatch-preview hidden aria-hidden="true">
	<img class="ts-swatch-preview__img" alt="" decoding="async" loading="eager" />
</div>
