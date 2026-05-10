# Layout composition

Commerce 页面 **Section → Component → Interaction** 组合规范。

## 文档

| 文件 | 内容 |
|------|------|
| [section-rules.md](./section-rules.md) | Section / Component / Interaction 职责与边界 |
| [pdp-layout.md](./pdp-layout.md) | 单品页 hook 搬迁与布局流程 |
| [sticky-behavior.md](./sticky-behavior.md) | Sticky 列、Token、无障碍 |

## 代码位置

| 路径 | 说明 |
|------|------|
| `template-parts/sections/` | PDP section PHP |
| `woocommerce/single-product/layout-default.php` | 默认组合 |
| `src/css/components/sections/` | Section 样式（composition only） |
| `inc/woocommerce/hooks/pdp-layout.php` | Hook 搬迁 |
