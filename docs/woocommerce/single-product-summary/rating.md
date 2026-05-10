# Single product — Rating

## Hook

| Hook | 优先级 | WC 默认回调 | TailwindScore |
|------|--------|-------------|----------------|
| `woocommerce_single_product_summary` | **10**（先于 price 注册，故先执行） | `woocommerce_template_single_rating` | `tailwindscore_render_single_product_summary_rating` |

## Adapter contract

**函数**：`tailwindscore_adapter_single_product_rating_props( $product )`

| 输出键 | 类型 | 说明 |
|--------|------|------|
| `rating_html` | `string` | `wc_get_rating_html( $average, $review_count )` |
| `average` | `float` | 平均分 |
| `review_count` | `int` | 评论数 |
| `show_if_empty` | `bool` | 默认 `false`；无 HTML 且无展示需求时可隐藏整块 |

**Filter**：`tailwindscore/adapter/single-product/rating_props`  
**Filter**：`tailwindscore/adapter/single-product/rating/show_if_empty`

## Component

**文件**：`template-parts/components/product-summary/rating.php`

**职责**：容器 `.ts-product-summary__rating.ts-rating`，内部输出 **WC 原生星级 HTML**（不替换 JS / 不接管 Reviews）。

## Supported states

- 有评论 / 无评论（依 `rating_html` 与 `show_if_empty`）。

## Unsupported

- 自定义星级交互、第三方评论 App 嵌入（通过 filter 替换 HTML）。
