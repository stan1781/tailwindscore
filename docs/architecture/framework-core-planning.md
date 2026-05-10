# TailwindScore Theme Framework — Core Planning

本文档在现有 `docs/*` 规则之上，定义 **Theme Core Foundation**：目录边界、Vite/Tailwind/TS/PHP/WooCommerce 系统层、组件与 Token/Kirki/配置器扩展路线。

**本阶段范围**：架构与契约；**不**产出首页 / 单品等完整页面 UI。

**定位**：Modern WooCommerce Starter Framework — PHP SSR First、WooCommerce Native Friendly、Token Driven、Component Driven。

**禁止**（与全局 docs 一致）：Vue/React SPA、Headless / Full API Frontend、jQuery 核心架构、巨型 CSS/JS、utility explosion、Bootstrap 式 UI。

**UI 方向**：Shopify Premium Theme 质感、Apple 式层级、Framer 式 spacing、高级 DTC；避免传统 WP 小工具风、廉价 hover、过度动效。

---

## 1. Theme Folder Structure（主题目录结构）

### 1.1 推荐完整结构（Canonical）

```plaintext
tailwindscore/                          # 主题根目录
├── docs/                               # 规范与架构（已存在）
├── src/                                # 前端源码（唯一构建入口侧）
│   ├── css/
│   │   ├── app.css                     # Tailwind 入口：@import tailwindcss + layers
│   │   ├── tokens/                     # :root / @theme 映射、预设变量桥接
│   │   ├── components/                 # @layer components（语义组件类）
│   │   └── utilities/                # 受限自定义 utility（可选，须评审）
│   ├── ts/
│   │   ├── bootstrap.ts                # 扫描挂载点、按条件启动模块
│   │   ├── components/                 # 轻量 UI 控制器（非 React/Vue）
│   │   ├── modules/                    # 领域模块：cart-drawer、gallery…
│   │   ├── utils/                      # 纯函数：dom helpers、debounce、guard
│   │   └── types/                      # 共享类型、WC DOM 契约
│   └── public/                         # 可选：仅当希望走 Vite public 机制时
├── assets/                             # 不与打包强绑定的主题静态资产
│   ├── fonts/
│   ├── images/                         # 品牌图、占位符源文件（非产品图）
│   └── svg/                            # 图标源（优化后由构建或手工同步）
├── dist/                               # Vite 构建产物（不入手写逻辑）
├── inc/                                # PHP：主题系统层（禁止臃肿 functions.php）
│   ├── setup.php                       # theme_support、menus、image sizes
│   ├── assets.php                      # enqueue、manifest 读取
│   ├── hooks.php                       # 聚合注册或分发到 features/
│   ├── template-tags.php               # 模板辅助函数（展示层）
│   ├── helpers/                        # 数据格式化、查询封装（无 echo）
│   ├── compatibility/                # WooCommerce / 插件守卫
│   └── features/                       # 按领域拆分：cart-drawer-php、schema…
├── template-parts/                     # 可复用 PHP 视图片段（组件化 SSR）
│   ├── components/                     # buttons、modal-shell、drawer-shell…
│   ├── layout/
│   └── woocommerce/                    # WC 共享片段（被 woocommerce/*.php 引用）
├── woocommerce/                      # WooCommerce 模板覆盖（最小集）
├── patterns/                           # 可选：block patterns
├── parts/                              # 可选：FSE parts（若走向 block theme）
├── style.css                           # WP 主题头信息 + 必要极简兜底
├── functions.php                       # 唯一职责：加载 inc/*.php（薄入口）
├── theme.json                          # 可选：与 WP 块主题/FSE 协作时
├── vite.config.ts
├── package.json
├── tsconfig.json
└── composer.json                       # 可选：PHPStan / PHPCS
```

### 1.2 目录职责说明

| 路径 | 职责 | 为何独立 |
|------|------|----------|
| `src/` | 可编译源码（CSS/TS） | 与 WP 运行时分隔；单一构建真相 |
| `assets/` | 字体、品牌静态图、SVG 源 | 体积大、变更慢；避免与 TS 混目录 |
| `dist/` | 构建输出（hash 文件名） | 缓存与部署清晰；禁止手改 |
| `inc/` | Hook、enqueue、特性开关、helper | 避免 `functions.php` 巨型化 |
| `template-parts/` | SSR 组件拼装 | 页面模板只做 orchestration |
| `woocommerce/` | WC 核心模板覆盖 | 与主题其余部分隔离，便于 diff 升级 |
| `docs/` | 架构与约定 | AI 与人类对齐 |

### 1.3 后期扩展方式

- **新 WooCommerce 特性**：优先 `inc/features/woocommerce-*` + `template-parts/woocommerce/` + 最小 `woocommerce/` 覆盖。
- **新 TS 交互**：`src/ts/modules/{feature}/`，由 `bootstrap.ts` 按 body class / `data-ts` 条件加载。
- **多品牌 / Preset**：仅扩展 `src/css/tokens/` + Kirki 输出变量（见第 8–9 节），不复制整套 PHP。
- **配置器**：服务端 `inc/features/configurator/` + 模块 `src/ts/modules/configurator/`（第 10 节）。

---

## 2. Vite Architecture

### 2.1 `vite.config` 要点

- **根目录**：主题根；`root` 指向含 `src` 的目录（通常即主题根）。
- **输入入口（建议）**：
  - `src/ts/bootstrap.ts` — 前台主脚本。
  - `src/css/app.css` — Tailwind 主样式。
  - 可选：`src/ts/admin.ts`（与前台隔离）。
- **输出**：`dist/`，`assets/[name]-[hash][extname]`。
- **Sourcemap**：开发开启；生产可按环境关闭或隐藏 sources。
- **resolve.alias**：`@/` → `src/ts`（可选），缩短 import。

### 2.2 Asset Build Strategy

| 资产类型 | 策略 |
|----------|------|
| CSS | PostCSS + Tailwind；单入口或多入口按页面拆分（见下） |
| TS | ES modules；tree-shaking；按入口分包 |
| 字体/大图 | `assets/` 存放；`vite-plugin-static-copy` 或构建后路径常量；小 SVG 可 sprite |
| 主题截图等 | 留在主题根或 `assets/`，不走 Vite |

**分包建议（WooCommerce 友好）**：

- `global`：`app.css` + `bootstrap.ts` 中的 **仅全站必需** 逻辑（体量极小）。
- `shop`：archive / single 需要的模块（条件 enqueue）。
- `checkout`：结账页专用（与 archive 互斥加载）。

具体入口数量在实现期定为 2–4 个，避免「单 bundle 全站」与「碎片过多」两极。

### 2.3 Manifest Strategy

- 使用 `@vitejs/plugin` 默认 manifest 或 `vite-manifest-plugin` 生成 `dist/.vite/manifest.json`（路径以最终选型为准）。
- **PHP**：`inc/assets.php` 读取 manifest，将 **hash 后 URL** 传给 `wp_register_script/style`。
- **原则**：主题内 **禁止手写** `main.js?v=1`；一律以 manifest 为准。

### 2.4 Hot Reload Strategy

- **推荐**：Vite dev server + 本地 WP（`.local` / Docker）；通过 **代理** 或 **环境常量** 切换 dev server URL。
- **模式开关**：`TAILWINDSCORE_VITE_DEV`（wp-config 常量）为 true 时，enqueue `@vite/client` 与 `server.host` 指向。
- **禁止**：生产环境加载 HMR client。

### 2.5 WordPress Enqueue Strategy

- **注册时机**：`wp_enqueue_scripts`；admin 分离 `admin_enqueue_scripts`。
- **依赖**：
  - 若模块依赖 WC 前端脚本（如 `wc-add-to-cart`），在 `deps` 中声明，避免 race。
- **defer/async**：脚本默认 `true === $in_footer`；必要时 `strategy => defer`（WP 6.3+）。
- **条件加载**：
  - `is_shop() || is_product_taxonomy()` → shop bundle
  - `is_product()` → single 附加入口（若拆分）
  - `is_cart() || is_checkout()` → checkout bundle
- **inline config**：尽量少用；必要时 **nonce + 最小 JSON**，并预留 CSP nonce API。

---

## 3. Tailwind Architecture

### 3.1 `tailwind.config`（或 Tailwind v4 等价方式）

Tailwind **v4** 倾向 CSS-first：`src/css/app.css` 内 `@import "tailwindcss"`、`@theme`、`@source`。

**规划约定**：

- **配置拆分**：主题扩展集中在 `src/css/tokens/theme.css`（或 `@theme` 块），便于 Kirki 变量对齐。
- **content/source 扫描**：`*.php`、`src/ts/**/*.ts`、`template-parts/**/*.php`、`woocommerce/**/*.php`。

### 3.2 Token Integration

- **单一真相**：CSS 自定义属性 `--color-*`、`--space-*`…（详见 `design/tokens.md`）。
- **Tailwind 映射**：在 `@theme` 将 `--color-accent` 映射为 `color-accent` 等，供 utility 使用。
- **禁止**：在 PHP 模板写死 `#hex`；禁止组件外散落 `rounded-[17px]`。

### 3.3 Component Layer

- **位置**：`src/css/components/*.css`，在 `app.css` 内 `@import` + `@layer components`。
- **形态**：`.btn`、`.btn--primary`、`.drawer`、`.modal__panel` 等 **语义类**。
- **必须 component 化**（重复 ≥2 次或组合 ≥5 个显著 utility）：
  - 按钮全家、表单 field group、卡片表面、modal/drawer 壳、accordion、product-card、price-block、trust-block、gallery 容器。

### 3.4 Utility Layer

- **允许**：布局编排（`flex`、`grid`、`gap-*`）、显示隐藏、`sr-only`、响应列数。
- **谨慎**：装饰性 `shadow-*`、`rounded-*` 若重复 → 下沉组件层。
- **自定义 utility**：仅放 `src/css/utilities/`，需 PR 说明与 design review。

### 3.5 Responsive Strategy

- Mobile First；断点与 `design/design-system.md` 一致。
- **页面模板**：只能用 **layout utilities**；组件内部隐藏响应差异（由组件 CSS 处理）。

---

## 4. TypeScript Architecture

### 4.1 目录：`src/ts`

```plaintext
src/ts/
├── bootstrap.ts              # 入口：扫描 data-ts / body class，动态 import()
├── components/               # 轻量「视图控制器」：可绑定单个 DOM 区域
│   └── dialog-trap.ts      # 例：焦点陷阱纯逻辑或小组件
├── modules/                  # 业务边界清晰的功能单元
│   ├── cart-drawer/
│   ├── product-gallery/
│   ├── product-variations/
│   └── archive-filters/
├── utils/
│   ├── dom.ts
│   ├── events.ts           # delegate、once、abort-friendly listeners
│   └── guards.ts
└── types/
    └── wc.ts               # 与 WC 事件 payload 相关的最小类型
```

**说明**：`components/` 在此指 **TS 侧可复用小块**，不是 React/Vue；避免与 PHP `template-parts/components` 混淆——二者关系为：**PHP 输出骨架，TS components/modules 绑定行为**。

### 4.2 Module Boundary（模块边界）

- **一个 `modules/{name}`**：对外导出 `mount(root: HTMLElement)`（与可选 `teardown`）。
- **禁止**：模块间循环依赖；共享代码下沉 `utils/` 或 `types/`。
- **禁止**：模块直接写 `document.querySelector` 全局抓取（除 bootstrap 约定的一次性扫描）。

### 4.3 Event Handling

- **委托**：在 `root` 上监听 `click`/`change`，按目标选择器分发。
- **WC 集成**：监听官方事件（如 `added_to_cart`）而非臆造全局 bus。
- **销毁**：`teardown` 移除监听；保存 handler 引用。

### 4.4 DOM Strategy

- **契约**：PHP 输出 `data-component`、`data-ts`、`aria-*`；选择器优先稳定的 data 属性。
- **范围**：查询限制在 `root`；portal 型抽屉挂 `body` 须在模块内封装并注释。
- **渐进增强**：关键购买路径无 JS 仍可操作（与 WC 默认一致）。

### 4.5 State Strategy

- **服务端为真相**：价格、库存、购物车内容以 WC/PHP 为准。
- **客户端状态**：仅限 UI（开合、当前 slide、未提交的选项）；提交后以服务端响应刷新 DOM 或触发 WC fragments。
- **禁止**：维护与 WC 长期分叉的购物车模型。

---

## 5. PHP Theme Architecture

### 5.1 Setup Structure

- `inc/setup.php`：`add_theme_support`、导航菜单、缩略图尺寸、`html5`、title-tag、`woocommerce` 支持声明等。
- **禁止**：在 setup 内 enqueue 脚本（放到 `assets.php`）。

### 5.2 Enqueue Structure

- `inc/assets.php`：注册、manifest 解析、条件加载、inline localization（节制）。
- **辅助函数**：`tailwindscore_asset_url( $entry )`，内部读 manifest。

### 5.3 Hooks Structure

- `inc/hooks.php`：仅 `require` 各 feature 或调用注册函数；或拆分为 `hooks/front.php`、`hooks/woocommerce.php`。
- **命名**：`tailwindscore/{context}/{event}`（见 `components/naming-conventions.md`）。

### 5.4 Template Loading Strategy

- 顶层模板（`header.php`、`footer.php`、`index.php`）**薄**：拼装 `get_template_part('template-parts/...')`。
- WooCommerce：`woocommerce.php` 或专用 wrapper + `woocommerce_content()`。
- **查找顺序**：必要时 `locate_template()` 包裹，便于子主题覆盖。

### 5.5 Helper Strategy

- `inc/helpers/`：**无输出副作用**（不 echo）；返回数据或已转义片段。
- `inc/template-tags.php`：**可 echo** 的展示函数，内部必须 escape。
- **禁止**：helpers 内远程 HTTP、重型查询（批量移到 service / repository 层并缓存）。

### 5.6 `functions.php` 约束

```php
// 示意：唯一职责为加载 inc
array_map(
    fn ( $file ) => require_once get_theme_file_path( "inc/{$file}.php" ),
    [ 'setup', 'assets', 'hooks', 'template-tags' ]
);
```

---

## 6. WooCommerce Architecture（Native Friendly）

### 6.1 Archive Structure

- **模板**：`archive-product.php` → 循环 `content-product.php`（覆盖文件放在 `woocommerce/`）。
- **片段**：网格 shell、`orderby`、移动端筛选抽屉触发器 → `template-parts/woocommerce/archive-*`。
- **Hook**：排序、结果计数用 WC 原生 Hook；主题注入 wrapper 用 `tailwindscore/woocommerce/archive/*`。

### 6.2 Single Product Structure

- **覆盖**：`single-product.php`、`content-single-product.php` 按需最小覆盖。
- **拆分**：`product-gallery`、`product-summary`、`price-block`、`trust-block` 对应 `template-parts/components/` + `template-parts/woocommerce/`。
- **Hook**：`woocommerce_single_product_summary` 调整顺序，避免移除 `add_to_cart` 除非等价替代。

### 6.3 Cart Structure

- **Cart 页**：保留 WC form 语义；样式映射到 forms 组件。
- **Mini cart / drawer**：复用 `woocommerce_mini_cart` 或同一 partial；AJAX 事件驱动更新。

### 6.4 Checkout Structure

- **明确矩阵**：Classic Checkout **或** Checkout Block —— 须在 README/docs 声明支持哪一种为主；样式与脚本分支分离。
- **禁止**：前端改写应付总额；网关插件 DOM 谨慎耦合。

### 6.5 Override Strategy

- **最小覆盖**：仅复制需改的模板；版本升级对照 WC changelog。
- **逻辑**：PHP 不放业务 JS；仅 data 契约。

### 6.6 Hooks Strategy

- **优先** `add_filter` 追加 class、`wrap`、`woocommerce_*` 兼容参数。
- **`remove_action`**：需书面理由与回归清单。

---

## 7. Component System Planning

**模型**：PHP SSR（结构 + 无障碍） + CSS 组件类（Token） + TS modules（可选交互）。

### 7.1 基础组件（Foundation）

| 组件 | 职责 | 边界 | 拆分 |
|------|------|------|------|
| **buttons** | 行为与视觉变体（primary/secondary/ghost/link/disabled/loading） | 不含业务文案逻辑（调用方传入） | `template-parts/components/button.php` + `.btn` 系列 |
| **forms** | label、help、error、control wrapper、fieldset | 不负责 WC 结账字段 name 篡改 | field partial + `form-group` CSS |
| **drawer** | 遮罩、面板、滚动锁、`aria` | 内容与购物车/筛选解耦 | shell partial + `drawer` TS module |
| **modal** | 焦点陷阱、ESC、role=dialog | 业务表单放在 slot | shell + `modal` utils |
| **accordion** | 折叠面板、键盘导航 | 不与 WC tabs 混名 | partial + 可选轻量 TS |

### 7.2 电商组件（Commerce）

| 组件 | 职责 | 边界 | 拆分 |
|------|------|------|------|
| **product-card** | 归档卡片：媒体、标题、价格摘要、徽章位 | 不做 quick view 复杂逻辑除非单独模块 | PHP partial + `.product-card` |
| **product-gallery** | 主图、缩略图、`srcset` | TS 管滑动/zoom；PHP 管结构化数据 | `gallery` module |
| **product-summary** | 标题、评分、short description、meta 钩子区 | 不把可变逻辑硬编码 | `summary` partial + hooks |
| **price-block** | 现价、划线价、sale 文案封装 | 价格 HTML 来源 `woocommerce_get_price_html` filter | 单一 partial |
| **trust-block** | 信任图标、退换货、支付图标条 | 纯展示；数据可来自自定义器选项 | partial + Token 图标尺寸 |

---

## 8. Design Token Planning

### 8.1 分层

- **Primitive**：原始刻度（如 neutral 灰阶）。
- **Semantic**：`--color-text-primary`、`--shadow-sm`（组件只用语义层）。
- **Preset**：品牌 A/B/C 或「亮色/柔色」整套映射（多品牌）。

### 8.2 类目

| 类目 | 说明 |
|------|------|
| colors | canvas / surface / border / text / accent / status |
| spacing | 4px 网格 + section/gutter 语义 |
| radius | sm → xl + media |
| shadow | xs–lg，中性半透明 |
| typography | display / heading / body / ui + line-height / tracking |
| z-index | dropdown / sticky / drawer / modal / toast |
| animation | duration、easing；尊重 `prefers-reduced-motion` |

### 8.3 Kirki 兼容 / 多品牌 / Preset

- Kirki **只写入 CSS 变量**，不写 layout。
- **Theme Preset**：Kirki 选择 preset → 批量设置 primitive/semantic 变量默认值。
- **未来多品牌**：同一套模板 + 不同 preset JSON（或 Kirki export）切换变量表。

详情与字段表见第 9 节联动 `design/tokens.md`。

---

## 9. Kirki Integration Strategy

### 9.1 原则：**Kirki 只控制 Design Tokens**

- **允许**：颜色、间距刻度系数、圆角档位、阴影强度、字体族链接、字阶缩放系数、动效开关。
- **禁止**：控制模板路径、hook 顺序、WC 表单字段布局、组件 HTML 结构。

### 9.2 Theme Preset System

- **Preset = 命名变量包**：如 `preset=dusk` → 设置 `--color-accent`、`--radius-lg` 等。
- **实现**：Kirki `preset` 控件或自定义 control：切换时写入 `:root` 一段变量块。
- **默认**：随主题附带 `default` preset（无 Kirki 亦可运行）。

### 9.3 Dynamic CSS Variables

- **输出位置**：`wp_add_inline_style` 挂载在主题主 stylesheet 之后，或专用 handle `tailwindscore-kirki-tokens`。
- **格式**：仅 `:root { ... }`，禁止生成数百行组件级规则。

### 9.4 Token Sync Strategy

1. **源码真相**：`src/css/tokens/*.css` 定义默认值与 `@theme` 映射。
2. **Kirki**：运行时覆盖 **同名 CSS 变量**。
3. **构建**：Tailwind 引用 `var(--token)`；不重新编译即可换肤。
4. **文档**：每个 Kirki 字段对应文档表（变量名、范围、预设影响）。

---

## 10. Future Product Configurator Architecture（仅规划）

**当前不开发**，仅定义扩展轨道（与 `configurator/product-configurator.md` 一致思想）。

| 能力 | 架构要点 |
|------|----------|
| package product | PHP 输出套装步骤；服务端校验组合；TS 更新摘要区 |
| dynamic pricing | REST/AJAX 返回 `price_html` + breakdown；禁止 TS 自行计价 |
| conditional logic | 规则引擎在 PHP；TS 仅反映可用/禁用状态 |
| add-ons | 映射 cart item meta；表单复用 `forms` 组件 |
| service products | 时段/预约 REST；SSR 表单 + TS 日历交互 |

**落地时**新增：`inc/features/configurator/`、`src/ts/modules/configurator/`、`template-parts/configurator/`，与 WC cart/checkout 边界保持 **服务端真相**。

---

## 11. 与现有文档映射

| 主题 | 文档 |
|------|------|
| 愿景与禁止项 | `architecture/project-vision.md` |
| 渲染总览 | `architecture/system-architecture.md` |
| Token 细节 | `design/tokens.md`、`design/design-system.md` |
| 代码纪律 | `development/*.md` |
| 组件强制 | `components/component-rules.md` |
| WC 流程 | `woocommerce/woocommerce-architecture.md` |
| 配置器细节 | `configurator/product-configurator.md` |
| 分阶段 | `roadmap/implementation-phases.md` |

---

## 12. 本阶段验收标准（Framework Ready）

- [ ] 目录与职责按本文创建（空壳与加载链即可）。
- [ ] Vite → `dist/` + manifest + 条件 enqueue 草图可用。
- [ ] Tailwind：`app.css` → Token → components 层通路打通。
- [ ] TS：`bootstrap` + 一个 demo module 挂载模式跑通（非页面 UI）。
- [ ] PHP：`functions.php` 薄；`inc/` 可加载。
- [ ] WC：`woocommerce/` 仅占位或最小 wrapper，不追求视觉完成度。
- [ ] Kirki：字段设计文档化（可先 mock），**不**碰模板结构。
