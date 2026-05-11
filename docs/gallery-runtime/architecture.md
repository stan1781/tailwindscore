# Gallery Runtime Architecture

## Pipeline

```text
WC_Product + wc_get_gallery_image_html (+ filters)
        -> tailwindscore_product_gallery_runtime_props()
        -> woocommerce/single-product/product-image.php
        -> data-ts-module="tailwindscore-product-gallery"
        -> TS: gallery-slider + gallery-thumbs + gallery-lightbox + gallery-variation-sync
```

## Responsibilities

| Layer | Role |
| --- | --- |
| `inc/woocommerce/hooks/single-product-hooks.php` | 聚合 gallery attachment 顺序、slide metadata、JSON payload |
| `woocommerce/single-product/product-image.php` | SSR gallery markup |
| `src/ts/modules/gallery/*` | Embla、PhotoSwipe、variation image sync |

## Stability Notes

- SSR slide HTML 仍由 WooCommerce `wc_get_gallery_image_html()` 生成
- `wc-single-product` 仍加载
- theme 不声明 `wc-product-gallery-slider` / `lightbox` / `zoom`
- 未使用的 Flexslider / legacy PhotoSwipe 资源在 single product 页面被 dequeue
