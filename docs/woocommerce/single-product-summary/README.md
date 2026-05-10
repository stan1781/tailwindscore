# Single product summary — 渐进组件化

本阶段仅替换 **`woocommerce_single_product_summary`** 内四个安全块：

1. Title（5）  
2. Rating（10）  
3. Price（10）  
4. Excerpt（20）  

**未替换**：`woocommerce_template_single_add_to_cart`（30）、meta、sharing 等。

实现：

- **Hook 策略**：`inc/woocommerce/hooks/single-product-summary.php` — `woocommerce_init` 上 `remove_action` + 同优先级 `add_action`。  
- **开关**：`tailwindscore/woocommerce/single-product-summary/use_components`  
- **适配器**：`inc/woocommerce/adapters/single-product/*.php`  
- **组件**：`template-parts/components/product-summary/*.php`  
- **样式**：`src/css/components/product-summary/`  

## 文档索引

| 块 | 文档 |
|----|------|
| Title | [title.md](./title.md) |
| Price | [price.md](./price.md) |
| Rating | [rating.md](./rating.md) |
| Excerpt | [excerpt.md](./excerpt.md) |
