# Gallery runtime architecture

## Pipeline

```text
WC_Product + wc_get_gallery_image_html (+ filters)
        → tailwindscore_adapter_gallery_runtime_props()
        → template-parts/components/gallery/*.php (SSR)
        → data-ts-module="tailwindscore-product-gallery"
        → TS: gallery-slider + gallery-thumbs + gallery-lightbox + gallery-variation-sync
```

## Responsibilities

| Layer | Role |
| --- | --- |
| **Adapter** (`inc/woocommerce/adapters/gallery/runtime-props.php`) | Attachment order (featured + gallery IDs), WC-sized markup, slide metadata for JSON / PhotoSwipe. |
| **PHP gallery** | SSR markup: `.images`, `.woocommerce-product-gallery`, main Embla track, `ul.flex-control-nav` thumbs for WC variation JS compatibility. |
| **gallery-slider.ts** | Embla main carousel — drag, snap, prev/next hooks. |
| **gallery-thumbs.ts** | Embla thumbs — horizontal on small screens, vertical on `md+`. |
| **gallery-lightbox.ts** | PhotoSwipe 5 lightbox (`photoswipe/lightbox`), anchors `.ts-gallery__lightbox-link`. |
| **gallery-variation-sync.ts** | jQuery listeners only — `found_variation`, `reset_image`, `woocommerce_gallery_reset_slide_position`, `flexslider-click`. |

## SSR / CSR boundary

- Slide HTML is produced server-side via `wc_get_gallery_image_html()` so `woocommerce_gallery_*` filters stay meaningful.
- Optional `<script type="application/json" class="ts-gallery__json">` exposes attachment ids and captions for future enhancements (lightbox already reads DOM).

## WooCommerce scripts

`wc-single-product` still loads (tabs, ratings, gallery bootstrap). Theme **does not** declare `wc-product-gallery-slider`, `wc-product-gallery-lightbox`, or `wc-product-gallery-zoom`, so Flexslider, legacy PhotoSwipe, and jQuery zoom init stay off. Unused Flexslider / PhotoSwipe assets are dequeued on single product pages (`inc/woocommerce/hooks/gallery-runtime.php`).

## Unsupported / out of scope

- Replacing `wc-add-to-cart-variation` or variation REST payloads.
- Full-screen SPA gallery state — navigation reloads reset runtime (standard WooCommerce behaviour).
- Pinterest-style masonry — linear carousel only.
