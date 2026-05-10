# Single product — Price

## Hook

| Hook | 优先级 | WC 默认回调 | TailwindScore |
|------|--------|-------------|----------------|
| `woocommerce_single_product_summary` | **10**（与 rating 同级，执行顺序在 rating 之后） | `woocommerce_template_single_price` | `tailwindscore_render_single_product_summary_price` |

## Adapter contract

**函数**：`tailwindscore_adapter_single_product_summary_price_props( $product )`

- 先 **`tailwindscore_adapter_price_props( $product )`**（`price_html`、`suffix_html`、`unit_html` 等）。
- 单品页兼容：
  - **促销价 / 原价**：由 WC `get_price_html()` 统一输出（含 `<del>` / `<ins>`）。
  - **可变商品区间价**：同上。
  - **含税显示 / 后缀**：随 WC 设置与 `get_price_suffix()`。
  - **税费文案**：包含在 `price_html` / suffix 中，不重复计算。

**Filter**：`tailwindscore/adapter/single-product/summary_price_props`

## Component

**文件**：`template-parts/components/product-summary/price.php`

**职责**：包裹 `.ts-product-summary__price`，内部 **`tailwindscore_component( 'price', $props )`**（`.ts-price-block`）。

## Unsupported

- 分期、订阅阶梯价、多币种切换 UI。
- 购物车改价逻辑。
