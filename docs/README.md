# TailwindScore 文档

TailwindScore 是一个 **Modern WooCommerce Starter Framework**：以 **PHP SSR 为先**、**TailwindCSS 为先**、**TypeScript 模块**、**WooCommerce 兼容**、**设计系统驱动**，面向长期维护的商业前端框架（不是传统 WordPress 主题心智模型）。

## 文档地图

| 目录 | 说明 |
|------|------|
| [architecture](./architecture/) | 愿景、**框架核心规划**、系统架构（PHP / TS / Vite / WooCommerce） |
| [design](./design/) | 设计系统、Token（含 Kirki 兼容预期） |
| [development](./development/) | 编码规范：PHP / Tailwind / TypeScript |
| [components](./components/) | 组件化规则；[PHP API](./components/api/)（`button` / `input` / `product-card`） |
| [woocommerce](./woocommerce/) | 归档、购物车…；[Adapter](./woocommerce/adapters/) · [单品 Summary](./woocommerce/single-product-summary/) |
| [configurator](./configurator/) | 复杂商品 / 配置器方向的 SSR + TS 扩展规划 |
| [performance](./performance/) | 性能原则与测量（占位，随实现补充） |
| [ai](./ai/) | Cursor / AI 协作规范与 Prompt 指南 |
| [roadmap](./roadmap/) | 分阶段落地路线 |
| [commerce-interactions](./commerce-interactions/) | 前台 Commerce TS 模块与事件总线 |
| [layout-composition](./layout-composition/) | Section 组合、PDP 布局、Sticky |

## 架构一句话

**页面主体由 PHP SSR 输出；仅在画廊、购物车抽屉、变体、筛选抽屉、配置器等局部使用轻量响应式增强（Alpine.js + TS Modules），禁止全站 SPA / Headless 作为主架构。**

## UI 方向摘要

参考 Shopify 高端主题、Apple 式层级、Framer 式间距与现代 DTC：**大留白、大圆角、轻阴影、高级排版、克制微动效、强层级**；避免传统 WP 后台风、Bootstrap 组件感、廉价 hover 与过度玻璃拟态。

## 阅读顺序建议

1. `architecture/project-vision.md` → `architecture/framework-core-planning.md`（Theme Core Foundation）→ `architecture/system-architecture.md`
2. `design/design-system.md` → `design/tokens.md`
3. `development/coding-rules.md` → `tailwind-rules.md` / `typescript-rules.md`
4. `components/component-rules.md` → `naming-conventions.md`
5. `woocommerce/woocommerce-architecture.md`
6. `ai/cursor-rules.md` → `prompting-guide.md`
