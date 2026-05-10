# WooCommerce Adapter APIs

适配器将 `WC_Product` 转换为组件可用的 props 数组。

数据流：`WC_Product` → Adapter（纯数组）→ `tailwindscore_component()`

---

## Product Card Adapter

**文件**：`inc/woocommerce/adapters/product-card.php`

### Input

| 名称 | 类型 | 说明 |
|------|------|------|
| `$product` | `WC_Product` | 当前商品实例 |

Guard：未加载 WooCommerce 或非 `WC_Product` 时返回 **空数组**。

### Output（product-card 组件 `$args`）

| Key | 来源 | 说明 |
|-----|------|------|
| `permalink` | `get_permalink( $product->get_id() )` | |
| `title` | `$product->get_name()` | |
| `title_tag` | 固定 `'h3'` | 可由 filter 调整 |
| `image_url` | `woocommerce_thumbnail` | 无图则为空字符串 |
| `image_alt` | attachment alt 或商品名 | |
| `badges` | `tailwindscore_adapter_product_badges_props()` | 徽章列表 |
| `price` | `tailwindscore_adapter_price_props()` | |
| `actions_html` | 默认 `''` | `tailwindscore/adapter/product-card/actions_html` |

### Filters

- `tailwindscore/adapter/product/card_props`
- `tailwindscore/adapter/product-card/actions_html`

---

## Price Adapter

**文件**：`inc/woocommerce/adapters/price.php`

### Input

| 名称 | 类型 | 说明 |
|------|------|------|
| `$product` | `WC_Product` | |

Guard：无效商品返回 **空数组**。

### Output（`price` 组件 `$args`）

| Key | 来源 | 说明 |
|-----|------|------|
| `price_html` | `$product->get_price_html()` | 含 WC 格式化、税费展示 |
| `suffix_html` | `$product->get_price_suffix()` | 税费 / 后缀文案 |
| `unit_html` | filter | 默认空；单位价扩展点 |
| `sale_amount` / `regular_amount` | 可选 | 需 `include_structured_amounts` 为 true |

### Filters

- `tailwindscore/adapter/price/unit_html`
- `tailwindscore/adapter/price/include_structured_amounts`
- `tailwindscore/adapter/product/price_props`

---

## Badge Adapter

**文件**：`inc/woocommerce/adapters/badge.php`

### Input

| 名称 | 类型 | 说明 |
|------|------|------|
| `$product` | `WC_Product` | |

输出：**`list<array>`**，每项为 `badge` 组件的 `$args`。

### Output Mapping

| 条件 | `variant` | `label` |
|------|-----------|---------|
| `$product->is_on_sale()` | `sale` | Sale |
| `$product->is_featured()` | `success` | Featured |
| `! $product->is_in_stock()` | `neutral` | Out of stock |
| 管理库存且 ≤ low_stock_threshold | `neutral` | Only N left |
| 上架 ≤ new_product_days | `new` | New |

### Filters

- `tailwindscore/adapter/badge/new_product_days`（默认 30）
- `tailwindscore/adapter/badge/low_stock_threshold`（默认 3）
- `tailwindscore/adapter/product/badges_props`
