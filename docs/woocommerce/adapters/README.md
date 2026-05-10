# WooCommerce adapters

PHP 位置：`inc/woocommerce/adapters/`（由 `inc/woocommerce/load-adapters.php` 加载）。

数据流：

```plaintext
WC 对象 → Adapter（纯数组）→ `tailwindscore_component()` → `template-parts/components/*.php`
```

## 适配器 API

统一文档：[adapter-apis.md](./adapter-apis.md) 包含 Product Card、Price、Badge 三个适配器的完整 API 参考。

| 适配器 | 文件 |
|--------|------|
| Product Card | `product-card.php` |
| Price | `price.php` |
| Badge | `badge.php` |

## 单品摘要（PDP summary）

`inc/woocommerce/adapters/single-product/`：title、rating、price、excerpt — 见 [single-product-summary](../single-product-summary/README.md)。

其它（仅结构，见源码注释）：

- `gallery.php` — `tailwindscore_adapter_product_gallery_props()`（附件 ID 列表）
- `review.php` — `tailwindscore_adapter_product_rating_aggregate_props()`、`tailwindscore_adapter_review_comment_props()`

## Hooks 分组

`inc/woocommerce/hooks/`：`archive-hooks.php`、`single-product-hooks.php`、**`single-product-summary.php`**、`cart-hooks.php`。

## 模板桥接

| 模板 | 职责 |
|------|------|
| `woocommerce/content-product.php` | `tailwindscore_adapter_product_card_props()` → `product-card` |
| `woocommerce/archive-product.php` | 标准 loop + `content-product` |
| `woocommerce/single-product.php` | 单层 `content-single-product` |
| `woocommerce/content-single-product.php` | hook 顺序；摘要栏 `ts-product-summary`；Title/Rating/Price/Excerpt 由组件替换 |
