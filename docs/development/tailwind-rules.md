# Tailwind 使用规范（Tailwind Rules）

基于 **TailwindCSS 4** + **Design Tokens**。目标是：**少 utility 噪音、多语义组件、可维护大型 Commerce UI**。

## 1. Utility 使用边界

### 1.1 允许的「页面级」utility

仅适用于 **布局草图** 或 **一次性** 构图：

- `flex`、`grid`、`gap-*`（gap 须来自 spacing Token 映射）
- `md:grid-cols-*` 等响应列数
- `hidden` / `block` / `sr-only`
- 单次使用的营销区块 **临时** 微调（合并前应提炼）

### 1.2 必须下沉淀到组件 / Token 的情况

出现 **≥2 次** 相同组合，或组合长度 **≥5 个** 显著 utility（颜色+圆角+阴影+padding+边框），必须提炼：

- **CSS 组件类**：`.product-card`、`.btn-primary`
- 或 **PHP `get_template_part()` 组件** 包裹固定 class

### 1.3 禁止模式（Anti-patterns）

```html
<!-- BAD: utility 爆炸 + 魔法值 -->
<div class="rounded-[17px] bg-[#fafafa] p-[13px] shadow-[0_2px_8px_rgba(0,0,0,0.07)]">

<!-- GOOD: 语义组件 + Token -->
<div class="product-card">
```

```html
<!-- BAD: 在长模板里重复整段 -->
<div class="rounded-2xl bg-white p-6 shadow-sm border border-neutral-200">

<!-- GOOD -->
<div class="surface-card">
```

## 2. 组件化规则（Component Classes）

### 2.1 定义位置

- 全局：`resources/css/components/*.css` 由主入口 `@import`。
- 使用 `@layer components` 保持与 Tailwind 优先级协调。

### 2.2 命名

- **kebab-case**：`.cart-drawer-panel`、`.price-block`。
- **BEM 可选**：若团队偏好 `.product-card__title`，须全仓统一（见 `naming-conventions.md`）。

### 2.3 组件类内部

- 优先引用 **Token 映射** 的 Tailwind 主题键（如 `rounded-[var(--radius-lg)]` 或通过 `@theme` 注册 `radius-lg`）。
- **禁止**在组件类中写死品牌 hex（除 Token 默认值定义文件）。

## 3. 禁止 Utility Explosion

### 3.1 计数规则（Code Review）

单元素 `class=""` 超过 **~12 个 utility 类** → 必须复盘：是否应拆分子元素或提组件？

### 3.2 例外

- 工具类布局网格的「编排层」父容器可略多，但 **子元素应瘦身**。

## 4. Responsive Strategy

1. **默认小屏**：先满足移动端结构与可读性。
2. **顺序**：布局 → 间距 → 排版 → 装饰。
3. **禁止**：无意义的 `sm:` `md:` `lg:` 三重复（同一属性链）；合并为较大断点或 Token。

## 5. State Class Strategy（状态类）

### 5.1 交互状态

- 使用 **组状态**：`group` / `peer`（适度），或 **data-attribute**：`data-state="open"`。
- **焦点环**：`focus-visible:ring-*` 使用 Token；禁止移除 outline 而不提供替代。

### 5.2 WooCommerce 状态

- 不覆盖 WC 核心 class 语义 unless 文档化；优先 **额外类名** `tailwindscore-*` 通过 filter 追加。

### 5.3 Alpine.js 与 Tailwind

- `x-data` 区域避免与 Tailwind `@apply` 冲突；**状态样式**优先挂在 `data-*` 上，由 CSS 选择器驱动。

## 6. @apply 政策

- **允许**：在 `.btn-primary` 等 **组件类** 中小剂量使用 `@apply`。
- **避免**：在 `.btn-primary` 上 `@apply` 二十个 utility（等同于爆炸搬运）。
- **优先**：Token + 短 `@apply` + 少数原生属性。

## 7. 主题与暗色（未来）

- 若引入暗色：**仅切换 CSS 变量**，禁止维护两套 utility 页面。
- 组件类引用变量颜色：`bg-[var(--color-surface)]`。

## 8. AI 特别提示

生成 Tailwind 时：

- 默认输出 **语义组件类名** + **最少 utility**。
- 若用户要求「纯 utility」，仅允许在 **单一 demo 文件**；合并到主题须重构。
