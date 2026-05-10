# Single product — Excerpt (short description)

## Hook

| Hook | 优先级 | WC 默认回调 | TailwindScore |
|------|--------|-------------|----------------|
| `woocommerce_single_product_summary` | **20** | `woocommerce_template_single_excerpt` | `tailwindscore_render_single_product_summary_excerpt` |

## Adapter contract

**函数**：`tailwindscore_adapter_single_product_excerpt_props( $product )`

| 输出键 | 类型 | 说明 |
|--------|------|------|
| `content_html` | `string` | `woocommerce_short_description` filter → `wc_format_content()` |
| `has_content` | `bool` | 纯文本 trim 后是否非空 |

与 WC 核心 `short-description.php` 数据路径对齐，避免重复应用 filter。

**Filter**：`tailwindscore/adapter/single-product/excerpt_props`

## Component

**文件**：`template-parts/components/product-summary/excerpt.php`

**职责**：`.ts-product-summary__excerpt.ts-prose.ts-body`，输出 `wp_kses_post( $content_html )`；无内容时不渲染外层。

## Unsupported

- 长描述 Tab、折叠全文（后续模块）。
- A/B 测试文案切换。
