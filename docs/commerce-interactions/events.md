# Commerce Events (`src/ts/utils/events.ts`)

主题级 `CustomEvent` 辅助，仅保留仍然有明确 owner 的事件。

## API

| 函数 | 用途 |
| --- | --- |
| `emit(target, type, detail)` | 触发目录内事件 |
| `on(target, type, handler)` | 订阅目录内事件 |
| `off(target, type, handler)` | 手动移除监听 |
| `emitRaw` / `onRaw` | 任意事件名 |
| `delegate` | DOM 事件委托 |

## 当前目录事件

| 事件 | Payload | 触发源 |
| --- | --- | --- |
| `ts:qty:change` | `{ value, input, root }` | `commerce-quantity` |
| `ts:variation:changed` | `{ variation \| null, productId, form }` | variation runtime |

## 已移除

`ts:cart:updated`

cart drawer 现在直接消费 cart surface REST 返回值，不再依赖共享 cart update 事件。
