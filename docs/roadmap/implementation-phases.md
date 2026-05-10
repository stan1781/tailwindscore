# 实施阶段（Implementation Phases）

长期路线：**可扩展 Commerce Framework**，分阶段降低风险。每阶段结束应有 **可演示里程碑** 与 **文档更新**。

---

## Phase 1 — 基础架构（Foundation）

**目标**：可开发的主题骨架与构建链，不改变 WC 行为。

**交付物**：

- 主题引导：`functions.php` → `inc/` 模块化加载
- Vite + TailwindCSS 4 + TypeScript 管线；生产 enqueue manifest
- 基础模板：`header` / `footer` / `index`；最小 `woocommerce/` 占位
- `resources/css` Token 骨架（`:root` 或 `@theme`）
- `resources/ts/bootstrap.ts` 挂载约定
- CI 可选：lint/format 脚本占位

**验收**：

- 商店页可加载无报错；构建产物缓存友好。

---

## Phase 2 — Design System（设计系统）

**目标**：Token、类型刻度、核心语义组件样式壳。

**交付物**：

- 完成 `design/tokens.md` 到代码映射
- 全局排版、链接、焦点环、选择文本样式
- 容器 / 栅格 utility 或组件化 wrapper
- Motion：`prefers-reduced-motion` 策略落地

**验收**：

- 任意新页面使用 Token，不出现魔法 hex/spacing。

---

## Phase 3 — Core Components（核心组件）

**目标**：跨页面复用组件库（PHP + CSS + 可选 TS）。

**交付物**：

- `buttons`、`forms`、`cards`、`modal`、`drawer` shell
- `product-card`、`product-gallery`（PHP SSR + TS 可选）、`price-block`
- `docs/components` 组件清单（inventory，可选独立文件）

**验收**：

- 归档页与单品页复用同一卡片与价格壳层；无 utility 复制。

---

## Phase 4 — WooCommerce Pages（WooCommerce 页面）

**目标**：关键 commerce 流程视觉与交互达标。

**交付物**：

- Archive：网格、排序 UI、筛选抽屉（若需要）
- Single：gallery、变体、sticky ATC（可选）
- Cart：页面 + mini cart drawer 同步
- Checkout：Classic 或 Blocks **明确选型** 后的样式与兼容性注释

**验收**：

- 手动回归：简单商品、可变商品、coupon、运费计算不起冲突。

---

## Phase 5 — Configurator System（配置器体系）

**目标**：可扩展复杂商品与配置型购买路径。

**交付物**：

- 遵循 `configurator/product-configurator.md` 的分层
- REST/AJAX 校验 endpoint；购物车 meta 持久化
- 阶梯交付：add-ons → 条件逻辑 → 动态价 → builder → uploads/services

**验收**：

- 服务端为价格与规则真相；JS 关闭时核心信息仍可读（降级策略文档化）。

---

## 横切关注点（所有阶段）

- **性能**：关键 CSS、脚本按需加载（Phase 1 起约束）
- **无障碍**：焦点与对比度随组件推进
- **文档**：每阶段更新 `docs/README.md` 地图或变更日志
