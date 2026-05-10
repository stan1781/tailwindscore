# Price Block（`.ts-price-block`）

## 职责

- 规范 **现价 / 划线价 / 税费提示后缀** 的基线与数字字号（tabular）。
- 包裹 WooCommerce `price_html` 输出（`woocommerce_get_price_html` filter），不在主题内重复计价逻辑。

## 边界

- **不**包含购物车小计、结账总计行（后续独立 partial）。
- **不**替换 WC Blocks 价格 DOM（若启用 Blocks，需单独矩阵说明）。

## DOM

```html
<div class="ts-price-block">
  <!-- filtered WooCommerce price HTML -->
</div>
```

## States / Variants

- `.ts-price-block__current` / `__compare` / `__suffix` 用于纯静态 SSR；若仅用 WC HTML，可省略内部 span，依赖 `ins`/`del` 映射样式。

## 样式文件

`src/css/components/commerce/price-block.css`
