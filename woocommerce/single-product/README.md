# woocommerce/single-product/

| 文件 | 说明 |
|------|------|
| `layout-default.php` | PDP 默认 **section 组合**（两栏 grid + details + related） |

## 行为开关

| Filter | 默认 | 作用 |
|--------|------|------|
| `tailwindscore/pdp/use-section-layout` | `true` | `false` 时回退为单次 `do_action( 'woocommerce_after_single_product_summary' )`，且 **不** 搬迁 WC 核心 callback（见 `inc/woocommerce/hooks/single-product-hooks.php`） |
| `tailwindscore/pdp/sticky-gallery-column` | `true` | 桌面端图库列 `position: sticky`（尊重 `prefers-reduced-motion`） |
| `tailwindscore/pdp/sticky-summary-column` | `false` | 摘要列 sticky（长页可开） |

## 与 `content-single-product.php`

- 核心 tabs/upsell/related 在 section 布局下由 `single-product-hooks.php` 挂到 `tailwindscore/pdp/section/*`。
- 页脚仍对 `woocommerce_after_single_product_summary` 调用一次，供**仅依赖该 hook 的第三方插件**使用（核心输出已迁出）。
