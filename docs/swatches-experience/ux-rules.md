# Swatches Experience — UX 规则

## 职责划分

| 层级 | 职责 |
| --- | --- |
| **WooCommerce** | `wc_dropdown_variation_attribute_options` 输出 `<select>`、variation 匹配、`found_variation` / `update_variation_values`、option `disabled` 状态。 |
| **TailwindScore PHP** | 通过 `woocommerce_dropdown_variation_attribute_options_html` 包裹 **Swatch UI + 原样 select**；term meta 仅存展示用元数据，**不修改** variation 数据结构。 |
| **`mountSwatchGroups`（`swatch-sync.ts`）** | 点击 / 键盘 → 写入 `select.value` 并 `dispatchEvent('change')`；根据 `<option disabled>` 同步 `.is-unavailable`。 |
| **`swatch-keyboard.ts`** | `radiogroup` 内 roving `tabindex`、方向键、`Home`/`End`、`Enter`/`Space` 激活。 |

## 数据流（Event flow）

```text
SSR: attribute terms / options
  → swatch-group + buttons + native <select>
用户操作 Swatch
  → select.value + change 事件
  → WooCommerce Variation Core（不变）
  → update_variation_values
  → swatch-sync 刷新 disabled / selected
```

与 **Variation Runtime** 的衔接：`tailwindscore-variation-runtime` 挂载时依次调用 **`mountSwatchGroups(root)`** 与 **`mountSwatchImagePresentation(root)`**（图片 preload / hover 预览），无需单独 `data-ts-module`。

## 不支持（Unsupported）

- 自定义 variation 匹配或定价引擎。
- React/Vue Swatch 岛。
- 无 `<select>` 的纯按钮配置器（会破坏 WC 表单与无障碍回退）。
- 在主题核心代码里按属性 **名称字符串** 推断展示类型；请用 **`tailwindscore/swatches/image_attributes`** 或 **`presentation_map`**。

## 过滤器（Filters）

- `tailwindscore/swatches/enabled` — 全局开关。
- `tailwindscore/swatches/attribute_enabled` — 按属性关闭 Swatch 包裹。
- `tailwindscore/swatches/image_attributes` — **推荐**：列为「图片优先 → 无图则颜色」的属性 slug；未列入 → 文本按钮。
- `tailwindscore/swatches/presentation_map` — 单属性精细覆盖（优先于 image_attributes）。
- `tailwindscore/swatches/presentation_config` — 在合并结果上再调一次（高级）。
- `tailwindscore/swatches/dropdown_html` — 最终包裹 HTML。
