# PhotoSwipe 5 bridge

## Integration

- Package: `photoswipe` v5 (`photoswipe/lightbox`).
- Entry: `gallery-lightbox.ts` imports `photoswipe/style.css` once (bundled into Vite CSS).
- Selector: `a.ts-gallery__lightbox-link` inside the gallery root (`data-ts-module="tailwindscore-product-gallery"`).

Slides rendered via `wc_get_gallery_image_html()` already attach large-image metadata on `<img>` (`data-large_image`, dimensions). PhotoSwipe Lightbox reads anchor `href` (full URL) and nested image metadata — behaviour matches WC’s PhotoSwipe v4 expectations, upgraded to v5 runtime.

## WooCommerce coexistence

Legacy WooCommerce PhotoSwipe scripts/styles are dequeued on single product pages so only the bundled PhotoSwipe 5 runs.

## Caption sync

Captions follow attachment excerpts (`runtime-props` JSON includes `caption` for future use). Lightbox primarily uses WC-generated `data-caption` on images where present.

## Unsupported

- Sharing UI / deep-link history — PhotoSwipe defaults; WC’s old `photoswipe_options` filter is not mirrored (theme-level behaviour).
