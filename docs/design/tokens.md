# Design Tokens

**约束**：所有视觉决策必须 **Token 化**；禁止在 PHP/HTML 中硬编码 `#hex`、`padding: 13px`、随意 `z-index`。  
后期 **Kirki**（或同类）应通过 **Customizer → CSS Custom Properties → Tailwind `@theme`** 映射，而非平行两套命名。

## 1. Token 分层模型

```plaintext
语义 Token（Semantic）  →  组件使用（product-card, btn-primary）
        ↑ 映射
原始 Token（Primitive） →  palette / scale（gray-500, space-4）
```

- **组件与模板** 只引用 **语义 Token**（或 Tailwind 映射后的语义类）。
- **原始 Token** 仅在主题定义文件、`@theme` 扩展中出现。

## 2. Color Tokens（颜色）

### 2.1 语义色（强制面向 UI）

| Token | 用途 |
|-------|------|
| `--color-canvas` | 页面背景 |
| `--color-surface` | 卡片 / 面板背景 |
| `--color-surface-raised` | hover / 层级略高表面 |
| `--color-border-subtle` | 分割线、静止边框 |
| `--color-border-strong` | 聚焦、选中 |
| `--color-text-primary` | 主正文 |
| `--color-text-secondary` | 次要说明 |
| `--color-text-muted` | placeholder、禁用 |
| `--color-accent` | 主行动色（品牌） |
| `--color-accent-contrast` | 主按钮前景色 |
| `--color-danger` | 错误 |
| `--color-success` | 成功 |

### 2.2 WooCommerce 映射（建议）

| Token | 场景 |
|-------|------|
| `--color-price` | 现价 |
| `--color-price-alt` | 划线价 / 次要价格 |
| `--color-sale` | 促销徽章 |

### 2.3 Kirki 兼容思路

- Kirki 字段输出写入：**`:root { --color-accent: ... }`**（或 body class 限定）。
- Tailwind v4 `@theme` 引用同一变量：`--color-accent: var(--color-accent);` 或直接映射。
- **禁止**：Kirki 生成数百行独立 hex 规则覆盖组件；应只改 Token。

## 3. Spacing Tokens（间距）

### 3.1 刻度（示例命名）

采用 **4px 网格**，命名与 Tailwind spacing scale 对齐思路：

| Token | 值 |
|-------|-----|
| `--space-1` | 4px |
| `--space-2` | 8px |
| `--space-3` | 12px |
| `--space-4` | 16px |
| `--space-6` | 24px |
| `--space-8` | 32px |
| `--space-12` | 48px |
| `--space-16` | 64px |
| `--space-24` | 96px |

### 3.2 语义间距（可选层）

`--space-section-y-md`、`--space-gutter-x` 等 → 由语义引用刻度：  
`--space-section-y-md: var(--space-12);`

## 4. Typography Tokens（字体）

| Token | 说明 |
|-------|------|
| `--font-body` | 正文字体族 |
| `--font-display` | 展示标题（可等于 body） |
| `--text-display-xl` | clamp 或大台阶字号 |
| `--text-heading-lg` | h2 |
| `--text-heading-md` | h3 |
| `--text-body-md` | 正文默认 |
| `--text-body-sm` | 辅助 |
| `--text-ui-sm` | 按钮 / 徽章 |
| `--leading-heading` | 标题行高 |
| `--leading-body` | 正文行高 |
| `--tracking-ui` | 大写标签字距 |

**Fluid type**：若使用 `clamp`，封装在 Token 内，禁止组件内手写 `clamp(...)`。

**Canonical 实现（TailwindScore）**：以 `--ts-*` 为源码真相，见 `src/css/tokens/presets/default.css` — 含 `--ts-text-display`、`--ts-text-h1`–`--ts-text-h6`、`--ts-text-product-*`、`--ts-leading-*`、`--ts-tracking-*`、`--ts-prose-max`、`--ts-content-max`，以及布局节奏 `--ts-stack-gap-*`、`--ts-section-y-*`、`--ts-grid-*`。

## 5. Radius Tokens（圆角）

| Token | 建议 |
|-------|------|
| `--radius-sm` | 6px |
| `--radius-md` | 10px |
| `--radius-lg` | 16px |
| `--radius-xl` | 24px |
| `--radius-full` | pill |

产品图片容器可与卡片同级或专用 `--radius-media`。

## 6. Shadow Tokens（阴影）

| Token | 说明 |
|-------|------|
| `--shadow-xs` | 极轻 |
| `--shadow-sm` | 卡片默认 |
| `--shadow-md` | 浮动 |
| `--shadow-lg` | 抽屉 / modal |

使用 `color-mix` 或固定透明度中性色，保持跨背景可控。

## 7. Z-index Tokens（层级）

| Token | 层 |
|-------|-----|
| `--z-base` | 0 |
| `--z-dropdown` | 1000 |
| `--z-sticky` | 1100 |
| `--z-drawer` | 1200 |
| `--z-modal` | 1300 |
| `--z-toast` | 1400 |

自定义插件若需插入：必须在文档登记 **插入点**，禁止直接用裸数字。

## 8. Motion Tokens（动效）

| Token | 说明 |
|-------|------|
| `--ease-standard` | 标准缓动曲线 |
| `--ease-emphasized` | 强调（少用） |
| `--duration-fast` | 短 |
| `--duration-normal` | 常规 |

## 9. Breakpoint Tokens（断点）

与 Tailwind 默认对齐或文档化自定义：

| Token | 值 |
|-------|-----|
| `--bp-sm` | 640px |
| `--bp-md` | 768px |
| `--bp-lg` | 1024px |
| `--bp-xl` | 1280px |

若 PHP/JS 需要一致行为：从 **单一配置文件** 导出（构建期注入），避免 PHP 与 CSS 各写一套。

## 10. 实施清单（供 AI / CR 使用）

- [ ] 新样式是否可追溯到某一 Token？
- [ ] 是否在 `@theme` / `:root` 增加而非页面内联？
- [ ] Kirki 字段是否只改变量，不改组件结构？
- [ ] WC 状态色（成功/错误）是否与语义 danger/success 对齐？
