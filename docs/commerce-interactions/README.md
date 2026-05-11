# Commerce Interaction Layer

渐进增强交互层：SSR 输出为主，TypeScript 只增强 WooCommerce 原生表面。

## 模块索引

| 文档 | TS 文件 |
| --- | --- |
| [events.md](./events.md) | `src/ts/utils/events.ts` |
| [quantity.md](./quantity.md) | `src/ts/modules/commerce/quantity.ts` |
| [add-to-cart.md](./add-to-cart.md) | `src/ts/modules/commerce/add-to-cart.ts` |
| [variation-state.md](./variation-state.md) | `src/ts/modules/variations/index.ts` |
| [notices.md](./notices.md) | `src/ts/modules/commerce/notices.ts` |

## PHP 组件

`template-parts/components/commerce/` 目前保留真正跨表面复用的 commerce 片段。

## 挂载边界

`data-ts-module` 由 `src/ts/module-mounts.ts` 中的显式 selector 列表挂载，不再经过注册表 fan-out。
