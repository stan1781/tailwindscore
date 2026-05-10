# 命名约定（Naming Conventions）

目标：**人类可读、AI 可推断、与 WordPress / WooCommerce 生态不冲突**。

## 1. PHP Naming

| 实体 | 约定 | 示例 |
|------|------|------|
| 主题前缀 | `tailwindscore` | `tailwindscore_enqueue_assets` |
| 函数 | snake_case | `tailwindscore_get_product_card_args` |
| 类 | StudlyCase + 命名空间（可选） | `TailwindScore\Assets\Registrar` |
| 文件名 | kebab-case 或 WP 惯例 | `class-product-card.php`（若用 class-autoload） |
| Hook 自定义 | `tailwindscore/{action}` 或 `tailwindscore_{action}` | `do_action( 'tailwindscore/cart_drawer/open' )` |
| 模板部分 | `template-parts/{domain}/{component}.php` | `template-parts/woocommerce/product-card.php` |
| CSS 类（主题追加） | `ts-` 或 `tailwindscore-` **二选一全仓统一** | `tailwindscore-price-block` |

**建议**：PHP filter/tag 使用 **斜杠风格** `tailwindscore/register_sidebar`，与 modern WP 插件一致。

## 2. TypeScript Naming

见 `development/typescript-rules.md` 摘要：

- 文件：`kebab-case.ts`
- 导出函数：`camelCase`
- 类型：`PascalCase`
- 常量：`SCREAMING_SNAKE_CASE`

**模块文件夹**：与功能对齐 `cart-drawer`、`product-gallery`。

## 3. CSS Naming

| 场景 | 约定 |
|------|------|
| 组件类 | kebab-case `.product-card` |
| 状态 | `[data-state="open"]` 或 `is-active`（全仓统一） |
| 工具链生成的原子类 | Tailwind 原生，不手工改写 |

**禁止**：`.box1`、`.red-text` 等无语义命名。

## 4. Hooks Naming（WordPress）

### 4.1 主题发出（do_action）

- `tailwindscore/{context}/{event}`  
  - 例：`tailwindscore/header/before`、`tailwindscore/product/card/after_title`

### 4.2 主题过滤（apply_filters）

- `tailwindscore/{artifact}/{property}`  
  - 例：`tailwindscore/component/button/attributes`、`tailwindscore/product_card/classes`

### 4.3 WooCommerce 交互

- **优先**使用 WC 原生 hook；主题包装函数内再 `do_action` theme hook（便于子主题）。

## 5. Action Naming（用户可见）

| 类型 | 约定 |
|------|------|
| 按钮文案 | 动词优先、简短；翻译 |
| AJAX action string | `tailwindscore_{verb}_{noun}` | `tailwindscore_load_variations` |

**禁止**通用 `ajax_action = 'submit'`。

## 6. 数据属性契约（PHP ↔ TS）

全仓统一前缀：

| 属性 | 用途 |
|------|------|
| `data-component="product-gallery"` | 组件识别 |
| `data-ts-mount="cart-drawer"` | TS 挂载点 |
| `data-context="{json}"` | **慎用**：小体积 JSON；注意 HTML escape |

## 7. AI 可读性提示

- 新符号优先 **完整单词**；避免 `Mgr`、`Ctx` 除非类型文件局部。
- Hook / filter 字符串在代码附近 **单行注释用途**（非冗长描述）。
- 组件 props（PHP `$args` keys）使用 **稳定 snake_case**，勿随意改名。
