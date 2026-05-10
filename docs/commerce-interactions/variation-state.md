# Variation events → theme bus

## `data-ts-module`

**`tailwindscore-variation-runtime`** — wrapped automatically around variable add-to-cart forms (`inc/woocommerce/hooks/variation-experience.php`). Legacy **`commerce-variation-state`** has been removed; use this handle or subscribe to `ts:variation:changed` on `document`.

## 职责

在 **jQuery 可用** 时，由 `src/ts/modules/variations/jq-bridge.ts` 向 **`document`** 派发：

- **`ts:variation:changed`** — `found_variation` 携带 `variation` 对象。
- **`reset_data` / `hide_variation`** — `variation: null`。

其他变体 UX（选择器、状态壳、价格过渡动画、画廊索引）在 **`src/ts/modules/variations/`** 内组合加载，见 `docs/variation-experience/`。

## 不支持

- 替换 `add-to-cart-variation.js` 或 variation form DOM。
- 自行计算变体价格（仍以 WC / SSR 为准）。

## jQuery 说明

WooCommerce 变体事件为 **jQuery 插件事件**；本层仅 **订阅并转发 / 装饰**，不修改 WC 行为。
