# Quantity module (`commerce-quantity`)

## `data-ts-module`

`commerce-quantity`

## PHP

`template-parts/components/commerce/quantity.php` — 输出 `.ts-qty.quantity` + `input.qty`。

## 职责

- `+` / `-` 按钮与 **ArrowUp / ArrowDown** 调整数量。
- 触发 **`ts:qty:change`**（`document` 冒泡）。
- 遵守 `min` / `max` / `step`。

## 不支持

- 修改购物车行项目数量（购物车页由 WC 表单处理）。
- 定价、库存校验（服务端为准）。

## 事件流

```plaintext
用户操作 → input change/input → emit(ts:qty:change)
```
