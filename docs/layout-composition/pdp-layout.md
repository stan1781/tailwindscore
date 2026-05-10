# PDP 布局（Composition Runtime）

## 数据流

```plaintext
content-single-product.php
  → layout-default.php
      → product-gallery-section   → do_action( woocommerce_before_single_product_summary )
      → product-summary-section   → do_action( woocommerce_single_product_summary )
      → product-details-section     → do_action( tailwindscore/pdp/section/details )
      → related-products-section   → do_action( tailwindscore/pdp/section/related )
  → [可选] do_action( woocommerce_after_single_product_summary )  // 插件兼容
```

## Hook 搬迁（`inc/woocommerce/hooks/pdp-layout.php`）

自 `woocommerce_after_single_product_summary` **移除**并挂到：

| 原 WC 回调 | 新 hook |
|------------|---------|
| `woocommerce_output_product_data_tabs` (10) | `tailwindscore/pdp/section/details` |
| `woocommerce_upsell_display` (15) | `tailwindscore/pdp/section/related` (priority 5) |
| `woocommerce_output_related_products` (20) | `tailwindscore/pdp/section/related` (priority 10) |

关闭 section 布局：`add_filter( 'tailwindscore/pdp/use-section-layout', '__return_false' );` — 搬迁不执行，layout 回退为单次 `after_single_product_summary`。

## 响应式

| 区间 | 行为 |
|------|------|
| `< 64rem` | 单栏：图库在上、摘要在下（grid 单列） |
| `≥ 64rem` | `ts-pdp__grid--split` 双栏；可选 sticky 列 |

## 不支持

- 自定义 PDP 路由 / SPA
- 替换 WC `single-product` 表单与变体 JS
- 营销向全屏动画与 Page Builder 编排
