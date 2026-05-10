# TailwindScore — Directory Map（骨架阶段）

完整规范见 `docs/architecture/framework-core-planning.md`。以下为仓库落地结构摘要。

## 顶层

| 路径 | 职责 | 扩展方式 |
|------|------|----------|
| `src/css/` | Tailwind 入口、Token、`base/`（含 `typography.css`）、`components/`（layout、button、forms、commerce）、`utilities/` | 新组件：先 Token → `@layer components` partial → `components/index.css` `@import` |
| `src/ts/` | Vite 编译的 TS；bootstrap + registry + modules（含 **`modules/commerce/`**） | 新业务 → `modules/{feature}/` + `modules/register.ts` 注册 |
| `inc/` | PHP 系统层（setup / enqueue / hooks / helpers / woocommerce） | 新领域 → `inc/features/*` 并在 `bootstrap.php` 引入 |
| `template-parts/` | SSR 片段；**`components/`**、**`sections/`**（PDP section 组合） | 按 domain 分子目录，禁止复制 utility |
| `woocommerce/` | WC 模板覆盖；**`single-product/layout-default.php`** 驱动 PDP section | 仅添加必要覆盖文件 |
| `assets/` | 字体/品牌静态资源 | 与打包分离 |
| `dist/` | Vite 产物（构建生成） | **勿手写**；部署前 `npm run build` |
| `docs/` | 架构与约束 | 同步更新决策 |

## `/src/css`

| 路径 | 职责 |
|------|------|
| `app.css` | 聚合入口 |
| `tokens/presets/default.css` | `--ts-*` 默认值（Kirki 覆盖同名变量） |
| `tokens/presets/theme-presets.css` | 未来多品牌 body class / preset 占位 |
| `tokens/kirki-bridge.css` | 文档化 Kirki 输出契约 |
| `tokens/theme.css` | `@theme` 映射 → Tailwind utilities |
| `base/` | `@layer base` |
| `components/` | `@layer components`（语义类） |
| `utilities/` | 受限自定义 `@layer utilities` |

## `/src/ts`

| 路径 | 职责 |
|------|------|
| `bootstrap.ts` | 导入 CSS、初始化、`mountRegisteredModules()` |
| `bootstrap-registry.ts` | `data-ts-module` → `mount()` 映射 |
| `modules/register.ts` | 统一注册入口 |
| `modules/*` | 领域模块（cart、galler、filters…） |
| `components/*` | 非框架的可复用 TS 小块（焦点陷阱等） |
| `utils/*` | DOM / 事件委托等纯辅助 |

### 边界与策略（骨架约定）

- **模块边界**：一个 `modules/{name}` 对外导出 `mount(root)`；禁止模块间隐式全局单例。
- **DOM**：优先在 `root` 内查询；契约来自 PHP 输出的 `data-component` / `data-ts-module`。
- **事件**：优先委托 `delegate()`；`teardown` 到来时再补充 AbortController 卸载。
- **状态**：持久业务状态以 WooCommerce / SSR 为准；TS 仅保留 UI 瞬时态。

## `/inc`

| 路径 | 职责 |
|------|------|
| `bootstrap.php` | 按序加载子系统 |
| `helpers/manifest.php` | Vite manifest / dev origin |
| `setup/theme-support.php` | `add_theme_support`、menus |
| `setup/cleanup.php` | 保守 head 清理 |
| `enqueue/front.php` | Vite dev/prod enqueue |
| `hooks/template-hooks.php` | 非 WC 的模板钩子 |
| `woocommerce/support.php` | `add_theme_support('woocommerce')` 等 |
| `woocommerce/hooks.php` | WC 专用钩子聚合 |

## Kirki（兼容策略）

- Kirki **只写** `:root { --ts-*: ... }`，由 `wp_add_inline_style` 附加在构建样式之后。
- 禁止从 Kirki 输出 layout / template 结构 CSS。

## 命令

```bash
npm install
npm run dev      # 配合 wp-config 常量 TAILWINDSCORE_VITE_DEV
npm run build
npm run typecheck
```

## wp-config 开发示例

```php
define( 'TAILWINDSCORE_VITE_DEV', true );
```
