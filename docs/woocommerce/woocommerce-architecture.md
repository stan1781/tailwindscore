# WooCommerce 架构策略（WooCommerce Architecture）

在 **不破坏 WooCommerce 原生生态** 的前提下定制前端：模板覆盖最小化、Hook 优先、购物车与结账保持 WC 真相。

## 1. Archive Strategy（商店归档 / 目录）

### 1.1 模板

- 使用 `archive-product.php`（或主题等价层次）指向通用布局。
- **循环项**：`content-product.php` 覆盖或通过 `woocommerce_product_loop_start` 控制 wrapper（谨慎）。

### 1.2 筛选与排序

- **原生**：保留 Query 与 `WOOF` 等插件兼容思路 — **不假设**特定插件。
- **主题侧抽屉筛选**：PHP 输出表单结构；TS 负责抽屉交互；提交仍为 **GET/POST 导航** 或官方 AJAX 模式（选型后在实现期固定）。

### 1.3 分页与 SEO

- SSR 输出链接；禁止 JS 偷偷替换 URL 而不更新 `canonical`（若做，必须 SEO 评审）。

## 2. Single Product Strategy（单品页）

### 2.1 模板覆盖

- 优先覆盖：`single-product.php` → `content-single-product.php`（按 WC 层次）。
- **Gallery**：PHP 输出图片列表与 `srcset`；TS 增强滑动/zoom。

### 2.2 Hook 布局

- 使用 `woocommerce_single_product_summary` 优先级调整标题、价格、表单区块位置。
- **避免**移除 `woocommerce_template_single_add_to_cart` 除非提供完整等价功能并测试变量商品。

### 2.3 Product Meta

- Tabs / Accordion：语义化 markup + Token；兼容插件追加 tab（`woocommerce_product_tabs`）。

## 3. Cart Strategy（购物车）

### 3.1 Cart 页面 vs Drawer

- **页面购物车**：保留 WC 表单语义与 coupon、`update_cart` 按钮行为。
- **Drawer（迷你购物车）**：展示片段通过 `woocommerce_mini_cart` 或自定义 endpoint **复用同一套 line item 组件**。

### 3.2 AJAX Add to cart

- 遵循 WC 事件与 fragments 机制；主题脚本监听官方事件更新抽屉。

### 3.3 禁止

- 本地伪造购物车总计不算服务端税费/运费（除非 WC 设置明确允许且文档化）。

## 4. Checkout Strategy（结账）

### 4.1 默认路径

- **优先 Classic Checkout**（短代码）或 **Blocks Checkout** 二选一的明确支持矩阵 — **禁止**在同一安装混改两套而不声明。

### 4.2 样式

- 表单字段映射到主题 `forms` 组件；**不改**字段 name/order unless 通过合法 Hook。

### 4.3 第三方支付插件

- 不覆盖插件注入的结账按钮容器选择器；使用松散父子样式限定。

## 5. Variation Strategy（可变商品）

### 5.1 PHP

- 保留 `.variations_form` 结构与 hidden input；属性下拉 **SSR 完整可选值**。

### 5.2 TS

- 监听 WC `found_variation`、`reset_data`；更新 **价格区块、图库、库存提示**。
- **禁止**：绕过 WC 用自己的价格计算逻辑。

### 5.3 图像

- 变体图切换应调用 WC 提供的数据属性或官方 JS 钩子；若替换为自定义 gallery，需对照无障碍与键盘操作。

## 6. Template Override Strategy（模板覆盖）

### 6.1 流程

1. 记录 WC 源模板版本。
2. 复制到 `woocommerce/` 对应路径。
3. 删除无关注释块；加入主题 hook `do_action( 'tailwindscore/...' )`。
4. CR 必须双人 eyeball diff。

### 6.2 升级

- WC 大版本升级：**回归清单** — 单品、变体、购物车、结账、coupon。

## 7. 与插件生态共处

- **Checkout Field Editor、网关、订阅**：禁止 CSS 选择器紧密耦合插件内部 DOM；优先官方类名或插件文档推荐 hook。
- **Caching**：购物车/结账页遵循 WC 与主机文档排除缓存。

## 8. 扩展检查表（新增功能前）

- [ ] 是否破坏 WC form POST？
- [ ] 是否与 Blocks 冲突（若声明支持）？
- [ ] 是否可在 JS 关闭下 fallback？
- [ ] 价格是否始终来自服务端？
