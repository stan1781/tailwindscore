# 组件规范（Component Rules）

**原则**：页面只做 **编排（composition）**；可复用 UI **必须**组件化。组件载体可以是 **PHP template part + CSS 组件类 + 可选 TS 模块**。

## 1. 必须组件化的清单（Mandatory）

以下 **不允许**在多个页面复制 markup + class：

| 组件 | 说明 |
|------|------|
| **buttons** | 主/次/幽灵/破坏/加载态 |
| **forms** | label、help、error、input、select、checkbox/radio 组合 |
| **cards** | 通用 surface card、文章 card（若需） |
| **modal** | 对话框壳层：overlay、panel、标题区、焦点陷阱配合 |
| **drawer** | 侧滑抽屉壳层（购物车、筛选） |
| **product-card** | 归档/推荐位商品卡片 |
| **product-gallery** | 主图 + 缩略图 + zoom（PHP 输出结构 + TS 增强） |
| **price-block** | 现价、划线价、税费提示、sale badge 的区域 |

**price-block** 须与 WooCommerce 价格 Hook / 过滤器策略对齐（见 `woocommerce/woocommerce-architecture.md`）。

Foundation 阶段 CSS 语义类前缀为 **`ts-`**（例：`.ts-btn`、`.ts-product-card`），文档见 `docs/components/README.md`。

## 2. 组件三层模型

```plaintext
PHP (SSR markup) + CSS component class + TS module (optional)
```

1. **PHP**：输出无障碍结构、`data-*` 契约、初始可见内容。
2. **CSS**：`.component-name` + Token；禁止页面 inline style。
3. **TS**：仅当存在交互（swiper、variant image sync、drawer focus）。

## 3. 禁止页面复制 UI

### 3.1 Red Flag

- 同一 HTML 片段在 **2 个及以上** 模板出现 → **立即**提取 `template-parts/components/{name}.php`。
- class 组合重复 → 提取 **CSS 组件类**。

### 3.2 例外

- A/B 测试或营销活动 **临时** 着陆页：允许短期重复，**≤14 天** 内合并或删除（文档 ticket）。

## 4. 组件 API（PHP）

### 4.1 传参方式

优先 **`get_template_part()` 第三参数**（WP 5.5+）或 `set_query_var` 模式：

```php
get_template_part( 'template-parts/components/button', null, [
    'variant'  => 'primary',
    'label'    => __( 'Add to cart', 'tailwindscore' ),
    'attrs'    => [ 'type' => 'submit', 'name' => 'add-to-cart' ],
] );
```

### 4.2 约束

- `$args` **必须** `wp_parse_args` 与白名单校验。
- **禁止**信任传入 HTML unless `wp_kses_post` 明确允许标签集。

## 5. 组件与 WooCommerce

- **不向组件泄漏 WC 全局**：由调用方传入产品 ID / `WC_Product` 或已格式化的价格 HTML（视架构层而定）。
- **购物车按钮**：保持 WC form / ajax Add to cart 契约。

## 6. 无障碍（组件层必选）

- 模态/抽屉：**焦点管理**、`aria-modal`、`aria-expanded`。
- 图标按钮：`aria-label`。
- 表单错误：`aria-describedby` 关联。

## 7. 文档化要求

每个核心组件在 `docs/components/`（后续可加 `inventory.md`）登记：

- 名称、路径、props（args）、Token 依赖、TS 模块（若有）。

## 8. AI 约束

生成 UI 时：**默认创建/使用组件**；若用户给出一页 utility soup，应重构提案而非照搬。
