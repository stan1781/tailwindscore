# Single product — Title

## Hook

| Hook | 优先级 | WC 默认回调 | TailwindScore |
|------|--------|-------------|----------------|
| `woocommerce_single_product_summary` | **5** | `woocommerce_template_single_title` | `tailwindscore_render_single_product_summary_title` |

`woocommerce_template_single_title` 在 `woocommerce_init`（优先级 20）由主题 **`remove_action`**，同一优先级注册替换回调。

全局开关：`tailwindscore/woocommerce/single-product-summary/use_components`（`false` 时跳过替换逻辑）。

## Adapter contract

**函数**：`tailwindscore_adapter_single_product_title_props( $product )`

| 输出键 | 类型 | 说明 |
|--------|------|------|
| `title` | `string` | `$product->get_name()` |
| `heading_tag` | `string` | 默认 `'h1'` |
| `product_id` | `int` | 商品 ID |

**Filter**：`tailwindscore/adapter/single-product/title_props`

## Component

**文件**：`template-parts/components/product-summary/title.php`

**职责**：输出单列标题骨架（`.ts-product-summary__title-wrap` + `ts-heading-1`），无商务装饰。

**状态**：`heading_tag` 白名单 `h1`–`h4`。

## Unsupported

- 面包屑、副标题、品牌链（后续独立块）。
- SEO 结构化数据（由 WC / 插件负责）。
