# Product Card（`.ts-product-card`）

## 职责（Responsibility）

- 定义 **归档 / 推荐位** 商品卡片的 **表面、媒体比例、标题层级、元信息与底栏** 的节奏。
- 与 **Typography Token**（`.ts-product-title` / `.ts-price-block`）组合使用，避免页面级 utility 爆炸。

## 边界（Boundary）

- **不是**单品 PDP；不包含 gallery / tabs / sticky ATC。
- **不包含** AJAX Quick View；若增加，另立 TS 模块与独立 modal 契约。
- 价格 HTML **来源仍为 WooCommerce**（`woocommerce_template_loop_price` 等）；卡片内使用 `.ts-price-block` 包裹过滤后的输出。

## DOM 契约（SSR — 运行时 `product-card.php`）

```html
<article class="ts-product-card">
  <a class="ts-product-card__shell" href="...">
    <div class="ts-product-card__media">…</div>
    <div class="ts-product-card__body">
      <h3 class="ts-product-card__title">…</h3>
    </div>
  </a>
  <div class="ts-product-card__footer">
    <div class="ts-price-block">…</div>
    <div class="ts-product-card__actions">…</div>
  </div>
</article>
```

- **`.ts-product-card__shell`** 只包裹 **媒体 + 标题**，价格与可交互 **actions** 在 **footer**，避免 `<a>` 嵌套 `<button>`。

## Variants（预留）

| 钩子类 | 用途 |
|--------|------|
| `.ts-product-card--horizontal` | 列表视图（后续实现） |
| `.ts-product-card--dense` | 紧凑归档（后续实现） |

## States

- **Hover**（`.ts-product-card:hover`）：轻微上浮与阴影抬升；图片 **scale** 克制；尊重 `prefers-reduced-motion`。
- **售罄 / 缺货**：建议外层 `[data-stock='out']` + `.ts-badge--neutral`（PHP 输出），样式后续补齐。

## 关联组件

- **价格**：`price-block.md`（`.ts-price-block`）
- **徽章**：`.ts-badge*`（`commerce/badge.css`）
- **评分**：`.ts-rating` + WC `.star-rating`

## Tokens

表面与边框：`--ts-color-surface*`、`--ts-border-*`、`--ts-shadow-*`、`--ts-radius-xl`；排版：`--ts-text-product-*`。禁止在循环模板手写 HEX。
