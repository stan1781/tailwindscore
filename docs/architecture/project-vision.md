# 项目愿景（Project Vision）

## 项目名称

**TailwindScore** — Modern WooCommerce Starter Framework。

## 项目目标

1. **商业级 WooCommerce 前端框架**：可扩展、可测试、可团队协作，而非一次性主题。
2. **PHP SSR First**：SEO、首屏、无障碍与 WooCommerce 插件生态以服务端渲染为主路径。
3. **TailwindCSS First**：样式以设计 Token + 组件抽象表达，禁止 utility 在模板中无限复制。
4. **TypeScript Modules**：交互以小型、边界清晰的 ES 模块组织；构建走 Vite。
5. **Design System Driven**：间距、字号、圆角、阴影、动效体系统一，禁止「页面随手写样式」。
6. **AI Collaboration Friendly**：目录、命名、文档与约束明确，使 AI 与人类在同一套规则下增量协作。

## 核心理念

| 理念 | 含义 |
|------|------|
| Server Render First | HTML 由 PHP 模板层次输出；JS 只做增强，不做整页接管。 |
| Design System First | 任意 UI 变更优先映射到 Token / 组件，再落到页面。 |
| Component First | 页面组合组件与 WooCommerce Hook 输出，而不是复制 class 字符串。 |
| WooCommerce Compatibility First | 优先尊重 WC 模板、Hook、购物车/结账流程与插件约定；不为炫技破坏生态。 |
| Progressive Enhancement | 无 JS 仍可完成核心购买路径（在合理范围内与 WC 一致）。 |

## 明确不做什么（Out of Scope）

以下 **禁止** 作为本框架的主架构或默认路径：

- Vue / React **SPA**、Nuxt、Next.js
- **Headless Commerce**、**全站 API Driven 前端**（REST/GraphQL 驱动整页渲染）
- jQuery 作为核心架构、巨型 `custom.js`、巨型 `style.css`
- Bootstrap UI 组件体系、Elementor-first 架构
- 将 WooCommerce 结账 / 购物车流程替换为纯前端路由应用（除非单独文档化且仅为可选实验）

## 长期方向

1. **Phase 化交付**：基础架构 → 设计系统 → 核心组件 → WooCommerce 页面 → 配置器 / 复杂商品（见 `roadmap/implementation-phases.md`）。
2. **可扩展复杂商品**：套装、动态定价、附加项、服务类、客户上传、条件逻辑 —— 一律按 **PHP SSR + TS Module** 边界设计（见 `configurator/product-configurator.md`）。
3. **主题自定义器（Kirki 等）**：Token 与样式变量预留映射层，避免硬编码 scattered colors/spacing（见 `design/tokens.md`）。
4. **性能与可观测性**：以 SSR 预算、关键路径 CSS、按需 hydration 为指导（`performance/` 随实现补充）。

## 成功标准（可验收）

- 新增页面时，**不出现**大段重复的 Tailwind utility 组合。
- 新增交互时，以 **独立 TS 模块** 注册入口，而非全局匿名脚本。
- WooCommerce 核心流程可通过 **官方 Hook + 模板覆盖** 定制，而不是 Fork 核心模板全家桶。
- 设计变更可通过 **Token / 组件** 批量收敛，而非全局搜索 class。
