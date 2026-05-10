# Sticky 行为

## 策略

- **CSS 优先**：`position: sticky` + `--ts-pdp-sticky-top`
- **JS**：不在此阶段为 PDP 加 sticky polyfill；Commerce 模块保持独立
- **SSR**：不依赖 JS 显示两栏结构

## Token

`src/css/tokens/presets/default.css`：

```css
--ts-pdp-sticky-top: 1rem;
```

可通过 Kirki 覆盖同一变量调整顶距。

## 列修饰（`layout-default.php` 生成的 grid class）

| Class | Filter | 默认 |
|-------|--------|------|
| `ts-pdp__grid--sticky-gallery` | `tailwindscore/pdp/sticky-gallery-column` | `true` |
| `ts-pdp__grid--sticky-summary` | `tailwindscore/pdp/sticky-summary-column` | `false` |

样式见 `src/css/components/sections/pdp-grid.css`。

## 可访问性

- `prefers-reduced-motion: reduce`：**关闭** sticky 与 `max-height` 滚动摘要列，避免眩晕与焦点陷阱问题。

## 移动端

- 单栏堆叠，**不**使用 sticky 双栏（媒体查询内启用 sticky）。

## 不支持

- `position: fixed` 假 sticky、滚动劫持
- IntersectionObserver 驱动的复杂折叠（后续单独提案）
