# 产品配置器规划（Product Configurator）

面向未来：**套装（Package）**、**动态定价**、**条件逻辑**、**附加项（Add-ons）**、**服务商品**、**客户上传**、**商品构建器（Product Builder）**。  
统一模式：**PHP SSR + TS Module**；**服务端为规则与价格真相**，客户端只做呈现与渐进校验。

## 1. 设计目标

| 目标 | 说明 |
|------|------|
| SSR First | 配置器首屏 HTML 完整；SEO 与无 JS 降级可读 |
| 规则在服务端 | 条件、库存、合法组合、价格由 PHP/WC 后端判定 |
| 模块化 TS | `resources/ts/modules/configurator/` 独立演进 |
| WC 兼容 | 最终仍进入 WC Cart / Checkout；不私自造「伪订单对象」 |

## 2. 概念模型

### 2.1 配置抽象

- **Configuration**：用户对一组 **选项（Options）**、**步骤（Steps）**、**附加（Add-ons）** 的选择向量。
- **Rule Engine（服务端）**：返回 **可用下一步**、**禁用选项**、**价格明细**。
- **Presentation Layer**：PHP 渲染当前步骤；TS 处理联动 UI（不打乱 WC 价格字段）。

### 2.2 数据流（示意）

```plaintext
User selects option
       ↓
TS debounce → REST/AJAX → PHP validates + computes
       ↓
JSON: { price_html, availability, next_steps, errors }
       ↓
TS patches DOM regions (price-block, summary, warnings)
       ↓
Add to cart: WC line item meta carries configuration snapshot
```

## 3. Package Products（套装）

- **SSR**：展示套装组件列表、必选/可选分组。
- **定价**：基础价 + 组件加价；服务端合并计算；客户端显示明细表。
- **库存**：按 WC 规则（bundle 插件或自定义 meta）— **集成选型在实施阶段确定**。

## 4. Dynamic Pricing（动态定价）

- 规则来源：**meta / 选项 / 角色 / 批量** — 统一走服务端 endpoint。
- **禁止**：TS 内复制整套定价公式。
- 响应包含 **`price_breakdown`** 数组以便 UI 映射 Token 样式。

## 5. Conditional Logic（条件逻辑）

- PHP 输出 **初始可见性**；交互后用服务端校验 **防止绕过**。
- TS：根据响应切换 `hidden`、`aria-disabled`、`-invalid` 状态类。

## 6. Add-ons（附加项）

- Checkbox / Select / Text —— 映射到 WC **cart item meta**（或官方 Product Add-Ons 兼容策略）。
- UI：**forms 组件** 复用；校验消息走共享 partial。

## 7. Service Products（服务类）

- **预约/时段**：SSR 表单 + TS 日历交互；可用 REST 查询可用时段。
- **与服务插件共存**：不硬编码单一插件 DOM。

## 8. Customer Upload（客户上传）

- **表单**：`input[type=file]` SSR；进度与预览 TS。
- **安全**：服务端 MIME/大小/病毒扫描策略（主机层）；nonce + capability。
- **存储**：媒体库或隔离存储桶 — 实施期选定。

## 9. Product Builder（分步构建）

- **步骤导航**：PHP 输出步骤条；TS 切换 **仅客户端视图**；下一步仍校验服务端。
- **摘要 Sidebar**：固定 `ConfiguratorSummary` 组件区域（PHP shell + TS 更新）。

## 10. REST / AJAX 契约原则

- **Nonce**：`wp_create_nonce('tailwindscore_configurator')`。
- **权限**：与 `edit_shop_orders` 无关的公开端严格只暴露必要字段。
- **响应**：版本字段 `schema_version` 便于向后兼容。

## 11. 与购物车集成

- Add to cart payload：**结构化数组** `tailwindscore_cfg` → 保存为 cart item data → checkout 可见（合规展示）。
- **可编辑**：在购物车页允许展开编辑 → **重新校验服务端**。

## 12. 分阶段落地建议

对应 `roadmap/implementation-phases.md` Phase 5：

1. 静态 UI shell（SSR）+ mock endpoint。
2. 简单 add-ons + meta。
3. 条件逻辑 + 动态价。
4. Builder + uploads + 服务时段。

## 13. 禁止事项

- Headless-only 配置器。
- 纯前端购物车。
- 绕过 WC totals 的「自定义结账总价」。
