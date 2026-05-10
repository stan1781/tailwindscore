# Forms（`.ts-field` / `.ts-input` + WooCommerce selectors）

## 职责（Responsibility）

- 统一 **标签、帮助、错误** 垂直节奏与可读性。
- 为 **原生控件** 与 **WooCommerce 模板中的 `.input-text` / `select` / `textarea` / `checkbox` / `radio`** 提供一致的 Token 化外观。
- 提供 **数量步进器** 容器契约（`.ts-qty`，兼容 `.quantity .qty`）。

## 边界（Boundary）

- **不修改** WooCommerce 字段 `name` / `id` / 校验逻辑。
- **不负责** 结账字段编辑器类插件的内部 DOM；若冲突，通过 **额外 wrapper class** 或 **克制 filter** 解决。
- 验证消息 **结构** 由 WC/后端决定；`.ts-error` 仅定义样式槽。

## Field 组合（SSR）

```html
<div class="ts-field">
  <label class="ts-label ts-label--required" for="email">Email</label>
  <input id="email" class="ts-input" type="email" autocomplete="email" />
  <p class="ts-help">Order updates only.</p>
  <p class="ts-error" role="alert">Invalid email.</p>
</div>

<label class="ts-choice">
  <input class="ts-checkbox ts-choice__control" type="checkbox" />
  <span class="ts-choice__label">Subscribe to updates</span>
</label>
```

## WooCommerce 原生映射

下列选择器已在 `forms.css` 中与 `.ts-*` 对齐（无需在模板重复 utility）：

- `.woocommerce form .input-text`
- `.woocommerce form select`
- `.woocommerce form textarea`
- `.woocommerce form input[type='checkbox'|'radio']`
- `.woocommerce .quantity .qty`（可与 `.ts-qty` 组合）

## Quantity（数量）

推荐包裹结构（可与 WC 默认 PHP 输出并列增强）：

```html
<div class="ts-qty quantity">
  <button type="button" class="ts-qty__btn minus">−</button>
  <input type="number" class="qty input-text" />
  <button type="button" class="ts-qty__btn plus">+</button>
</div>
```

具体加减按钮是否输出取决于 WC 模板版本；本阶段 **仅提供样式契约**。

## States

- **Focus**：内阴影 + 外环（`color-mix` 基于 accent），避免去掉原生 outline 而无替代。
- **Disabled**：降低对比 + `not-allowed`。
- **Invalid**：建议在外层 `.ts-field` 加 `[data-invalid]` 或 `.has-error`（实现期统一），当前文档预留。

## Tokens

依赖 `--ts-radius-lg`、`--ts-color-border-*`、`--ts-color-surface`、`--ts-text-*`、`--ts-shadow-xs`。Kirki 仅覆盖 `--ts-*` 变量即可全局换肤。
