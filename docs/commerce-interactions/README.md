# Commerce interaction layer

渐进式前台交互：**SSR + TS 模块**，不替换 WooCommerce 核心 JS / 变体系统 / 购物车后端。

## 模块索引

| 文档 | TS 文件 |
|------|---------|
| [events.md](./events.md) | `src/ts/utils/events.ts` |
| [quantity.md](./quantity.md) | `modules/commerce/quantity.ts` |
| [add-to-cart.md](./add-to-cart.md) | `modules/commerce/add-to-cart.ts` |
| [variation-state.md](./variation-state.md) | `modules/variations/jq-bridge.ts` + `modules/variations/index.ts` (`tailwindscore-variation-runtime`) |
| [notices.md](./notices.md) | `modules/commerce/notices.ts` |

## PHP 组件

`template-parts/components/commerce/`：`quantity.php`、`add-to-cart-button.php`、`notice.php`。

注册：`src/ts/modules/register.ts` 绑定 `data-ts-module` 名称。
