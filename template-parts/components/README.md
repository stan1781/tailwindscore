# PHP Component Runtime

## 入口

```php
tailwindscore_component( string $name, array $args = [] ): void;
```

加载 `template-parts/components/{name}.php`，`$args` 由 WP 传入模板。

## 助手（`inc/helpers/component.php`）

- `tailwindscore_attributes_html( array $attrs ): string` — 安全属性串。
- `tailwindscore_component_classes( array $classes, array $args, string $component ): string` — 合并 + `tailwindscore/component/{component}/classes`。

## KSES（`inc/helpers/kses.php`）

- `tailwindscore_kses_icon_html()` — 按钮/图标槽 SVG。
- `tailwindscore_kses_actions_slot()` — 商品卡 footer 动作槽（在默认 `post` 允许表基础上按需补 `button`）。

## 已实现组件

| 文件 | 说明 |
|------|------|
| `button.php` | [API](./api/button-api.md) |
| `input.php` | [API](./api/input-api.md) |
| `select.php` | 显式 `options` map；见源码默认键 |
| `badge.php` | variant + size |
| `price.php` | `price_html` 或 `wc_price` 结构化金额 |
| `product-card.php` | [API](./api/product-card-api.md) |

## 规则摘要

- **禁止**在 PHP 中拼接 Tailwind utility；仅 **`ts-*`** 与文档化 hooks。
- **Token**：视觉来自 CSS 变量与组件层，不在模板写死颜色/间距。
