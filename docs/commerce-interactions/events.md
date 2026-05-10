# Commerce event bus (`src/ts/utils/events.ts`)

主题级 **CustomEvent** 目录，用于模块间协作；**不替代** WooCommerce 购物车数据或 REST。

## API

| 函数 | 用途 |
|------|------|
| `emit(target, type, detail)` | 触发目录内事件（`ts:*`） |
| `on(target, type, handler)` | 订阅；返回 **取消订阅** 函数 |
| `off(target, type, handler)` | 手动移除（优先使用 `on` 的返回值） |
| `emitRaw` / `onRaw` | 非目录任意事件名 |
| `delegate` | 事件委托（click/change 等） |

## 目录事件（`CommerceEventMap`）

| 事件 | Payload | 触发源 |
|------|---------|--------|
| `ts:qty:change` | `{ value, input, root }` | `commerce-quantity` |
| `ts:cart:updated` | `{ source, fragments?, cartHash? }` | jQuery `added_to_cart` 桥接 |
| `ts:variation:changed` | `{ variation \| null, productId, form }` | jQuery `found_variation` / `reset_data` / `hide_variation` |

## 不支持

- 与 WC Blocks Store API 的双向同步（未接入）。
- 自定义购物车对象。
