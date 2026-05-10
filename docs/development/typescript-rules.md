# TypeScript 规范（TypeScript Rules）

TailwindScore 的 TS **不是应用框架**，而是 **小而专注的模块集合**，用于增强 WooCommerce 与主题交互。

## 1. Module Structure（模块结构）

### 1.1 标准模块目录

```plaintext
resources/ts/modules/{feature}/
├── index.ts           # public API: mount, teardown（可选）
├── dom.ts             # 选择器、委托事件（可选）
├── state.ts           # 仅 UI 瞬时状态，不替代 WC 购物车真相
└── types.ts
```

### 1.2 public API 约定

```ts
// 推荐形状（示意）
export function mount(root: HTMLElement): void;
export function teardown(root: HTMLElement): void;
```

- **禁止**：模块在 import 副作用中自动扫全文档并匿名挂载（难以测试）；由 `bootstrap` 显式调度。

## 2. Naming（命名）

| 类型 | 规则 | 例 |
|------|------|-----|
| 文件 | kebab-case | `cart-drawer.ts` |
| 函数 | camelCase | `mountCartDrawer` |
| 类 | PascalCase（少用） | `GalleryController` |
| 常量 | SCREAMING_SNAKE | `DEFAULT_DEBOUNCE_MS` |
| 类型 | PascalCase | `VariationPayload` |

**DOM data 属性**：`data-ts="cart-drawer"` 或 `data-module="cart-drawer"`（全仓统一一种）。

## 3. Event Handling（事件）

1. **Delegate**：在 `root` 上监听 `click`、`change`，按 `selector` 分派。
2. **被动监听**：滚动高性能路径考虑 `{ passive: true }`。
3. **销毁**：`teardown` 中移除监听（保存引用，禁止匿名函数无法解绑）。
4. **禁止**：`document.body` 上堆积十个全局 listener。

## 4. State Handling（状态）

### 4.1 单一真相

- **购物车、价格、库存以 WooCommerce / 服务端为准**。
- TS 层状态仅限：**UI 开合、动画相位、当前选中还未提交的选项**（提交后立即以 WC 响应刷新）。

### 4.2 同步策略

- 使用 WC **`added_to_cart`**、`cart_updated`（或 Blocks 事件，若兼容策略允许）— 具体以集成阶段文档为准。
- **禁止**：本地维护完整购物车副本与服务端长期分叉。

## 5. DOM Strategy（DOM）

### 5.1 查询范围

- 所有查询 **限定在 `root` 内**：`root.querySelector(...)`。
- 例外：portal 类抽屉若挂到 `body`，须在模块内 **明确注释** 并封装查询。

### 5.2 HTML 契约

- PHP 输出稳定 `data-*` 与 **语义类**（来自组件层）。
- TS **禁止**依赖极易变动的 utility 长串选择器；优先 `data-hook` / `data-component`。

### 5.3 渐进增强

- `mount` 前检测特征节点；缺失则 **静默返回**（不抛未处理异常刷屏）。

## 6. 异步与错误

- `fetch` / admin-ajax / REST：**类型化响应**失败路径；用户可见错误走 WC 通知或 aria-live 区域。
- **禁止**：`alert()` 作为默认 UX。

## 7. 性能

- **按需分割**：结账页不加载 archive filters 模块。
- **防抖**：搜索、resize；**节流**：scroll。
- **避免**：在 variant change 时整页 DOM 重建；优先更新文本节点或小容器。

## 8. 测试与质量（随仓库演进）

- 单元：纯函数与状态机（若有）。
- E2E：Playwright / Cypress 候选 — 覆盖加购、变体选择（实施阶段启用）。

## 9. AI 生成约束

- 不引入 React/Vue/Pinia 等 **除非项目明确迁移**（当前 **禁止**）。
- 不生成依赖 jQuery 的模块。
- 每个新模块必须：**入口清晰、无全局污染、可 teardown**。
