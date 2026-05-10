# 编码总则（Coding Rules）

适用于 TailwindScore 仓库内 **PHP、TypeScript、Tailwind/CSS**。细则见 `tailwind-rules.md`、`typescript-rules.md`。

## 1. PHP Rules

### 1.1 风格与可维护性

- **命名**：函数 `tailwindscore_{domain}_{verb}`；类 `TailwindScore\{Namespace}`（若采用自动加载）；常量 `TAILWINDSCORE_*`。
- **单文件职责**：`inc/` 下单文件聚焦单一领域（如 `assets.php`、`woocommerce-template-hooks.php`）。
- **早期返回**：减少深层嵌套；错误路径先返回。
- **杜绝**：在模板中执行重查询、远程 HTTP、复杂业务分支。

### 1.2 WordPress / WooCommerce

- 使用 **官方 API**：`wc_get_product`、`WC()->cart`、template hooks；禁止直接 SQL 操作订单与商品（除非明确迁移脚本且隔离）。
- **模板覆盖**：仅放入 `woocommerce/`，同步标注依据的 WC 版本。
- **兼容性**：不假设单个插件存在；用 `class_exists`、`function_exists` 守卫。

### 1.3 安全

- 输出：**上下文相关 escape**。
- 表单与 AJAX：**nonce + capability**。
- 属性：布尔与枚举白名单校验后再输出。

### 1.4 国际化

- 用户可见字符串：**始终** `__('text', 'tailwindscore')` / `_e`。
- 域名统一：`tailwindscore`（与主题目录/slug 对齐）。

## 2. TypeScript Rules（摘要）

- **严格模式**：`strict: true`；避免 `any`；未知用 `unknown` 收窄。
- **DOM**：单一入口 mount；模块间不共享全局可变单例（购物车状态以 WC 为准）。
- **事件**：优先 `delegate` / 单一 listener；禁止每个 SKU 绑一个匿名 listener。
- 全文策略见 `typescript-rules.md`。

## 3. Tailwind Rules（摘要）

- **禁止**在多个模板复制同一 utility 长串；提炼为 **组件类** 或 **PHP 组件封装**。
- **响应式**：Mobile First；断点只用主题约定。
- 全文策略见 `tailwind-rules.md`。

## 4. AI Generated Code Rules

无论来自 Cursor 或其他模型，合并前必须满足：

1. **架构**：符合 `architecture/system-architecture.md`（SSR 优先、无 SPA）。
2. **设计**：颜色/间距来自 Token；禁止页面级魔法数字。
3. **组件**：新 UI 是否在 `components/component-rules.md` 所列范畴内已组件化？
4. **WooCommerce**：checkout/cart 改动是否走 Hook / 合法覆盖？
5. **可审查**：附带简短注释说明「扩展点」与「为何」，禁止废话注释。
6. **删除冗余**：AI 常引入未使用 import、重复 helper；提交前清理。

### 4.1 PR / 提交前自检（AI + 人）

- [ ] PHP：无裸 echo 用户数据；翻译域名正确。
- [ ] TS：无 `console.log` 遗留（调试除外且标注 TODO 移除）。
- [ ] CSS：无重复 utility 爆炸。
- [ ] WC：测试购物车添加、结账第一步（手动或 E2E 后续补充）。

## 5. 文件与 Git 纪律

- **不提交** `node_modules`、`dist`（除非发布分支策略另有规定）。
- **Lockfile**：`package-lock.json` / `pnpm-lock.yaml` 择一并提交。
- **大资源**：图片走压缩管道；SVG 审查内联脚本。

## 6. 与其他文档关系

| 主题 | 文档 |
|------|------|
| 命名 | `components/naming-conventions.md` |
| WC 流程 | `woocommerce/woocommerce-architecture.md` |
| AI Prompt | `ai/prompting-guide.md` |
