# 系统架构（System Architecture）

本文定义 TailwindScore 的 **文件布局约定**、**渲染策略**、**PHP / TS / Vite / WooCommerce** 协作方式。实现阶段目录名可微调，但 **职责边界** 不得被破坏。

## 1. 推荐顶层文件结构（逻辑视图）

**Canonical 以** `architecture/framework-core-planning.md` **为准**（含 `src/`、`assets/`、`dist/` 拆分理由）。下方为与实现对齐的摘要：

```plaintext
theme-root/
├── docs/                      # 规范与架构（唯一真相来源之一）
├── src/                       # 可编译前端源码（Tailwind + TS）
│   ├── css/                   # app.css、tokens、@layer components
│   └── ts/                    # bootstrap、modules、utils、types
├── assets/                    # 字体、品牌静态图、SVG 源（非构建逻辑）
├── dist/                      # Vite 构建产物（勿手改）
├── woocommerce/               # WooCommerce 模板覆盖（仅覆盖必要文件）
├── template-parts/            # 可复用 PHP 片段（组件化 SSR）
├── inc/                       # PHP：setup、enqueue、hooks、helpers、features
├── patterns/                  # 若使用 block patterns（可选）
├── vite.config.ts
├── package.json
├── composer.json              # 若引入 PHP 工具链
├── style.css                  # 主题头部元数据 + 必要时极简兜底
├── functions.php              # 薄入口：仅加载 inc/
└── *.php                      # 模板层次文件（index、single-product 等）
```

**原则**：业务逻辑在 `inc/`；可复用标记在 `template-parts/`；**WC 覆盖隔离在 `woocommerce/`**；前端源码在 `src/`；静态大件在 `assets/`；构建输出在 `dist/`。

## 2. PHP Template Strategy

### 2.1 模板层次（WordPress + WooCommerce）

- **沿用** WordPress 与 WooCommerce 的模板层次；优先使用 **覆盖而非全盘替换**。
- 页面布局：`header.php`、`footer.php`、可选 `sidebar.php`；业务区块放入 `template-parts/{domain}/{name}.php`。
- **禁止**：在单个 `page-*.php` 内堆叠数百行 HTML；应拆分为 `template-parts` 并在父模板中 `get_template_part()`。

### 2.2 数据与视图

- 视图文件只做 **组装**：调用 Model 式函数（ `inc/` 内）或使用 WC 提供的 API / Loop。
- 对用户输入与 URL 参数：**sanitize → validate → escape**；输出用 `esc_html`、`esc_attr`、`wp_kses_post` 等于上下文匹配的函数。

### 2.3 Hooks Strategy（PHP）

| 类型 | 用途 |
|------|------|
| `add_action` | 注入 markup、注册 asset、与 WC 生命周期协作 |
| `add_filter` | 修改 WC/HTML 片段、属性、类名（克制，文档化） |
| `remove_action` / `remove_filter` | 谨慎使用；必须注释「为何移除」与兼容性说明 |

**约定**：

- **优先** `woocommerce_*` 与 `tailwindscore_*` 前缀的自定义 action，便于主题内协作。
- 对第三方插件：**不因主题方便而移除其核心 Hook**，除非提供等价替代并记录在 `woocommerce/` 或 changelog。

## 3. WooCommerce Override Structure

### 3.1 放置位置

- 所有 WooCommerce 模板覆盖位于：`woocommerce/` 下 **与原插件相对路径一致**。
- **最小覆盖**：只复制需要修改的文件；其余依赖 WC 默认模板升级。

### 3.2 覆盖纪律

1. 复制自 WooCommerce 的版本号注释保留或对照更新说明。
2. 模板内 **不包含** 业务 JS；仅输出 data-attribute、语义类名（来自组件层）。
3. 需要多处共享的标记：**提炼为** `template-parts/woocommerce/*.php` 并在覆盖模板中 `get_template_part()`。

## 4. Render Strategy（SSR + 局部增强）

```plaintext
[ Browser Request ]
       ↓
[ WordPress + WC PHP ]
       ↓  HTML（主体完整）
[ Critical CSS / Tailwind build ]
       ↓
[ TS modules（按需初始化）]
       ↓  局部：gallery / drawer / variation / filters / configurator
[ Alpine.js（轻交互，可选同区域共存）]
```

**规则**：

- **首屏 HTML** 不依赖客户端路由；URL 变更由浏览器导航完成。
- TS 模块通过 **单一入口或按页面入口** `import`，在 DOM Ready 后扫描 `data-ts-module` 或类似契约挂载。
- Alpine 仅用于 **局部声明式交互**（开合、tab、简易表单状态），不与 TS **双重绑定同一数据源**（选一主导）。

## 5. TS Module Strategy

### 5.1 目录与边界

```plaintext
src/ts/
├── bootstrap.ts              # 扫描与挂载（若采用）
├── components/               # 轻量 UI 控制器（非 SPA 框架）
├── utils/                    # dom、events、纯函数（无业务）
├── modules/
│   ├── cart-drawer/
│   ├── product-gallery/
│   ├── product-variations/
│   ├── archive-filters/
│   └── configurator/         # 未来
└── types/                    # 共享类型、WC 相关 DOM 契约
```

- **一个功能目录 = 一个对外契约**：导出 `mount(el: HTMLElement)` 或等价函数。
- **禁止**：10 个功能塞进一个 `app.ts` 却无分层。

### 5.2 与 PHP 的契约

- PHP 输出：`data-*`、JSON `application/json` 内嵌 script type（慎用，需 CSP 策略）、或 `wp_localize_script`（过渡期中适度）。
- **推荐**：关键配置通过 **data-attribute + JSON.parse** 或小体量 `wp_json_encode` 在合法模板位置输出，并在文档记录 CSP nonce 方案。

## 6. Vite Strategy

### 6.1 构建产物

- **开发**：`vite dev` 或 proxy 至本地 WP（具体脚本在实现期写入 `package.json`）。
- **生产**：构建至固定目录（如 `dist/`），`functions.php` 仅 **enqueue 哈希文件名** 或通过 manifest 解析。

### 6.2 入口拆分

| 入口类型 | 用途 |
|----------|------|
| `global` | 全站 TS/CSS（体量极小：字体、基础行为） |
| `woocommerce` | 商店相关模块打包（可按 archive / single / checkout 再拆） |
| `admin`（可选） | 后台扩展，与前台隔离 |

### 6.3 TailwindCSS 4

- Tailwind 通过 `@import "tailwindcss"`（v4 惯例）与 `@theme` / `@layer` 扩展 Token；源码置于 `src/css/`。
- **组件类**使用 `@layer components` 或 CSS Modules（若引入），避免在 PHP 中手写冗长 utility 链。

## 7. Asset Registration（PHP）

- 使用 `wp_enqueue_style` / `wp_enqueue_script`，`deps` 声明清晰；WC 页面有条件加载 shop 入口脚本。
- **依赖 WC 前端脚本时**：将主题脚本声明为 WC 脚本的 dependency，避免 race。

## 8. 扩展性约束（面向复杂商品）

所有新功能必须通过以下检查：

1. **能否先在 PHP 输出完整 HTML**，再叠加 TS？
2. **失败降级**：JS 禁用时是否仍具备 WC 默认可用性？
3. **状态边界**：购物车/价格是否仍以 **WC 服务端状态** 为准？

若能通过，则允许进入架构；否则退回设计。
