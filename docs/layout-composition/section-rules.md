# Section 规则

## Section 负责

- **布局**（grid、分栏、section 堆叠）
- **间距**（Token：`--ts-section-y-*`、`--ts-grid-gap` 等）
- **组合**（把 WC hook 或组件摆到正确区域）
- **响应式**（mobile-first、断点与 `docs/design` 一致）

## Component 负责

- **可复用 UI 块**（`template-parts/components/*`、`.ts-btn` 等）
- 具体 **SSR 标记** 与无障碍属性

## Interaction 模块负责

- **行为**（`src/ts/modules/commerce/*`、CustomEvent 目录）
- **不得** 承担配色与大面积布局

## 边界

| 层级 | 禁止 |
|------|------|
| Section | 业务条件分支、变体/价格计算、购物车规则 |
| Section | 重写 WC gallery core / 自建 slider runtime |
| Section | Page Builder 式配置 Schema |

## PDP 特化

- **Gallery**：仅 **composition wrapper**；Flexslider / Photoswipe / Zoom 保持 **WooCommerce 默认**。
- **Summary**：仅 spacing + sticky 修饰；无变体逻辑。
- **Details**：仅 tabs 外壳样式；无 reviews 拉取逻辑。
- **Related**：仅 section 与网格节奏；无 carousel、无自建 AJAX 列表。
