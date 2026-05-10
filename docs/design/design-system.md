# 设计系统（Design System）

TailwindScore 的 UI 方向：**Shopify 高端主题质感 + Apple 式层级 + Framer 式间距节奏 + 现代 DTC 品牌气质**。  
一切界面决策必须先落入 **Token** 与 **组件**，再进入页面。

## 1. 设计价值观

| 要做 | 不要做 |
|------|--------|
| 大留白、透气网格 | 密集表格风、传统 WP 小工具堆砌感 |
| 大圆角（规范内分级） | 随机圆角 |
| 轻阴影、柔和 elevation | 重投影、廉价发光 |
| 高级排版（字重/行高/字距分级少而精） | 过多字号台阶 |
| 克制微动画（时长/缓动统一） | 夸张 hover、bounce 遍地 |
| 清晰层级（前景/表面/画布） | 过度玻璃拟态、炫渐变 |

## 2. Spacing System（间距）

### 2.1 基准

- **基础单位**：`4px`（逻辑单位）；所有间距为 **4 的倍数**，除非 Token 层注明例外。
- **密度模式**（实施时在 Token 命名体现）：
  - **Comfortable**：默认商店、着陆页。
  - **Compact**：结账摘要、抽屉内列表（仍保持可读性，不为紧凑牺牲触控）。

### 2.2 层级建议（语义名 → 用途）

| Token 语义 | 典型用途 |
|------------|----------|
| `space-inline-xs` | 图标与文字、chip 内边距 |
| `space-inline-sm` | 表单项标签与控件间距 |
| `space-inline-md` | 卡片标题与元信息 |
| `space-section-sm` | 同区块内分组 |
| `space-section-md` | 区块之间（移动端） |
| `space-section-lg` | 区块之间（桌面端）、hero 上下 |

**规则**：页面不得直接使用魔法数字 margin/padding；应使用映射到 Tailwind 主题的 spacing Token。

## 3. Typography System（排版）

### 3.1 字体角色

- **Display**：可选第二字体，用于 hero / 少量标题（谨慎加载，子集化）。
- **Body**：全站正文与 UI；优先系统栈或单一可变字体文件。
- **Mono**：仅标价拆解、SKU、技术规格（少用）。

### 3.2 字号台阶（示意，最终以 `tokens.md` 为准）

| 角色 | 用途 |
|------|------|
| `text-display-*` | 营销大标题（极少数） |
| `text-heading-*` | h1–h4 映射 |
| `text-body-*` | 正文 lg / md / sm |
| `text-ui-*` | 按钮、徽章、导航 |

### 3.3 行高与字距

- **标题**：较紧行高（如 `1.15–1.25`），**禁止**全局负字距滥用。
- **正文**：舒适行高（如 `1.5–1.65`）；长文段落最大宽度受限（见 Container）。
- **全大写标签**：必须伴随 `letter-spacing` Token（ui-caption）。

## 4. Radius System（圆角）

分级 **少而清晰**，建议四级：

| 级别 | 用途 |
|------|------|
| `radius-sm` | 输入框、小徽章 |
| `radius-md` | 按钮、下拉 |
| `radius-lg` | 卡片 |
| `radius-xl` | 模态、大型容器、媒体框 |

**规则**：同一组件类型在全站使用同一半径 Token；禁止页面级 `rounded-[17px]`。

## 5. Shadow System（阴影）

Elevation **克制**：

| Token | 用途 |
|-------|------|
| `shadow-xs` | 分割线感、极轻浮起 |
| `shadow-sm` | 卡片默认 |
| `shadow-md` | hover / 浮动条 |
| `shadow-lg` | 抽屉、sticky bar（慎用） |

避免彩色阴影；阴影颜色仅用中性半透明叠加。

## 6. Container（容器）

- **内容最大宽度**：分级 Token（如 `container-prose`、`container-shop`、`container-full-bleed`）。
- **栅格**：12 列思维或 CSS Grid；**gutter** 必须使用 spacing Token。
- **响应式**：移动端优先较大侧边留白（匹配「大留白」方向）。

## 7. Responsive Rules（响应式）

1. **Mobile First**：默认样式针对小屏；`md:` `lg:` 递增复杂度。
2. **断点语义**：断点是 Token，不得在组件内写任意 `min-width: 823px`。
3. **触控目标**：可点击区域最小 **44×44px**（Apple HIG）；紧凑模式也不得低于 WCAG 2.5.5 推荐实践。
4. **类型缩放**：跨断点最多 **2–3 级**字号跳变，避免「桌面 giant / 手机 tiny」极端对立。

## 8. Motion Rules（动效）

### 8.1 时长 Token

| Token | 区间（示意） |
|-------|----------------|
| `duration-instant` | 70–100ms |
| `duration-fast` | 120–180ms |
| `duration-normal` | 200–280ms |

### 8.2 缓动

- 进入：`cubic-bezier` 柔和 ease-out。
- 退出：略短，避免拖沓。
- **禁止**：默认 `linear` 用于大面积位移。

### 8.3 可访问性

- 尊重 `prefers-reduced-motion`：**缩减位移与缩放**，保留即时 opacity 变化或静态切换。
- 不打断阅读：大规模 parallax 不作为默认。

## 9. 层级（Z-index）

全局 **阶梯 Token**（详见 `tokens.md`）：背景、默认、浮动 dropdown、sticky header、drawer、modal、toast。  
**禁止**页面随机 `z-[99999]`。

## 10. 与 Kirki / Customizer 的关系

- Customizer 控制的应是 **CSS 变量 → Token** 而非散落规则。
- 设计系统文档优先描述 **语义 Token**；Customizer 面板映射到同一语义层。
