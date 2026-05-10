# Prompting 指南（Prompting Guide）

帮助你在 Cursor / ChatGPT 等工具中 **高效、符合 TailwindScore 架构** 地请求产出。

## 1. 通用 Prompt 模板

```plaintext
上下文：TailwindScore（PHP SSR + Tailwind 4 + TS modules + WC）。
任务：<具体目标>
约束：遵守 docs/architecture 与 docs/development；禁止 SPA/Headless；组件化；Token 化。
涉及页面：<single-product | archive | cart | checkout>
参考文件：<可选路径>
输出：仅必要 diff / 新文件路径清单；不要重构无关代码。
```

## 2. 如何请求「组件」

**应包含**：

- 组件名（英文 slug）：如 `product-card`
- 变体：主按钮 / 次按钮 / 状态
- Props（PHP `$args`）：字段名、类型、默认值
- 无障碍要求：焦点、aria
- 是否需要 TS：仅交互部分

**示例**：

```plaintext
创建一个 product-card 组件：
- 路径 template-parts/components/product-card.php
- CSS 类 .product-card，使用 design/tokens.md 中间距与圆角
- 参数：product (WC_Product)、show_rating (bool)、badge_html (string 已净化)
- 不包含 TS；链接到单品页
请同时更新 docs（若需 inventory）略。
```

## 3. 如何请求「WooCommerce 页面」

**应指定**：

- Classic 或 Blocks（默认声明 Classic 除非另有说明）
- 覆盖策略：Hook 还是 `woocommerce/*.php`
- 必须保留的 WC form 字段

**示例**：

```plaintext
优化单品页价格区域：
- 使用 woocommerce_get_price_html filter 包裹 .price-block 组件
- 不移除 add to cart 表单
- 变体商品兼容：监听官方 JS 事件（typescript-rules）
```

## 4. 如何请求「TS Module」

**应指定**：

- 挂载点：`data-ts-mount` 值
- 输入：从 DOM 读取哪些 `data-*`
- 输出：更新哪些区域 selector
- 与 WC 事件：`added_to_cart` 等

**示例**：

```plaintext
实现 cart-drawer 模块：
- resources/ts/modules/cart-drawer/
- mount(root) 限定查询；open/close 切换 aria；focus trap
- 使用 WooCommerce fragments 或 mini_cart 刷新策略（说明假设）
```

## 5. 如何请求「Design System」

**应指定**：

- 修改的是 semantic 还是 primitive Token
- 是否影响 Kirki 映射
- 是否需要暗色预留

**示例**：

```plaintext
新增 --color-accent-alt Token：
- 用于次要 CTA
- 更新 tailwind @theme 映射
- 在 tokens.md 登记语义表
```

## 6. 负面 Prompt（减少跑偏）

在请求末尾附加：

```plaintext
不要：React/Vue SPA；utility class 复制；全局 jQuery；随意 remove_action。
```

## 7. 大型功能拆分提问顺序

1. 架构草图（PHP 模板 + Hook 列表）
2. Token / 组件契约
3. SSR markup
4. TS 增强
5. WC 回归测试清单

## 8. 代码审查 Prompt

```plaintext
根据 docs/development/coding-rules.md 审查以下 diff：
<粘贴或引用路径>
列出：阻塞项 / 建议项 / WC 兼容性风险。
```
