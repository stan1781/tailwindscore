# Cursor / AI 协作规范（Cursor Rules）

适用于在本仓库使用 Cursor（或其他 AI）生成、重构与审查代码时的 **强制约束**。

## 1. 代码生成总则

1. **架构**：PHP SSR 为主；TS 为局部增强；**禁止**引入 SPA 框架作为默认。
2. **范围**：只改任务相关文件；拒绝「顺手全局格式化」除非明确要求。
3. **设计**：颜色/间距/圆角/阴影来自 Token；禁止魔法数字与随机 z-index。
4. **组件**：按钮、表单、卡片、modal、drawer、product-card、gallery、price-block 不得页面复制。
5. **WooCommerce**：优先 Hook 与最小模板覆盖；禁止删除未知 WC Hook。
6. **安全**：sanitize、escape、nonce；不信任 `$_POST` 裸用。

## 2. 禁止事项（Hard No）

- Vue/React SPA、Nuxt、Next、Headless-first、全 API 驱动页面渲染。
- jQuery 核心架构、巨型全局 JS/CSS。
- Bootstrap 组件体系、Elementor-first。
- 任意 `eval`、拼接 SQL、暴露敏感调试。
- 结账流程「前端自定总价」。

## 3. 文件结构规则

- PHP 功能 → `inc/`；片段 → `template-parts/`；WC 覆盖 → `woocommerce/`。
- TS → `resources/ts/modules/{feature}/`。
- CSS/Tailwind → `resources/css/`。
- 构建产物不手改；enqueue 使用 manifest 或哈希策略。

## 4. AI 输出风格

### 4.1 回答结构

- **先结论** → **变更清单** → **关键代码引用**（路径明确）。
- 避免冗长前言；避免无路径的模糊代码块。

### 4.2 代码块

- 使用仓库真实路径作为 fence 标记（若引用现有文件）。
- 新增代码需标注插入位置（文件 + 锚点函数）。

### 4.3 不确定性

- WC Blocks vs Classic 冲突、第三方插件 DOM：**标注假设** 并给出回退方案。

## 5. 与仓库文档关系

生成代码前应 mentally check：

- `architecture/system-architecture.md`
- `development/tailwind-rules.md`
- `development/typescript-rules.md`
- `components/component-rules.md`
- `woocommerce/woocommerce-architecture.md`

建议在 Cursor 规则（若配置）中链接上述路径。

## 6. Commit / PR 期望（AI 辅助）

- Commit message：英文或中文一致即可，但必须 **说明意图**。
- PR 描述：截图仅限 UI 任务；架构任务附 **测试步骤**（手动）。

## 7. 反模式示例（AI 常见）

| 反模式 | 修复 |
|--------|------|
| 一页 40 个 Tailwind utility | 提取组件类 + template part |
| `document.querySelector` 全局滥用的 TS | `root` 限定 + data 属性 |
| `functions.php` 两千行 | 拆分 `inc` |
| 覆盖整个 `woocommerce` 目录 | 最小覆盖 |
