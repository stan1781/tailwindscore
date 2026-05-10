# template-parts/sections

**Page → Sections → Components → Interactions**

| Section | 文件 | 职责 |
|---------|------|------|
| Gallery | `product-gallery-section.php` | 包装 `woocommerce_before_single_product_summary`（**保留 WC 原生 gallery**） |
| Summary | `product-summary-section.php` | 包装 `woocommerce_single_product_summary` |
| Details | `product-details-section.php` | 通过 `tailwindscore/pdp/section/details` 输出 **WC tabs** |
| Related | `related-products-section.php` | 通过 `tailwindscore/pdp/section/related` 输出 **upsells + related** |

组合入口：`woocommerce/single-product/layout-default.php`（由 `woocommerce/content-single-product.php` 加载）。

类名过滤：`tailwindscore/component/section-{name}/classes`（与 `tailwindscore_component_classes()` 一致）。
