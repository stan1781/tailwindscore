# woocommerce/

主题内的 WooCommerce **模板覆盖**与 **WC 插件目录路径保持一致**。

当前阶段：

- **`content-product.php`** — Loop 项：`WC_Product` → `tailwindscore_adapter_product_card_props()` → `product-card` 组件。
- **`archive-product.php`** — 标准商品归档循环（无额外商店 UI）。
- **`single-product.php`** / **`content-single-product.php`** — 加载 **`single-product/layout-default.php`** 进行 **section 组合**（图库 / 摘要 / tabs / related）；摘要区内 Title / Rating / Price / Excerpt 仍由 **`single-product-summary.php`** hooks 驱动。
- **`single-product/`** — `layout-default.php` + README（PDP 开关与 sticky filter）。

适配器与 Hook 分组见 `docs/woocommerce/adapters/README.md`、`docs/woocommerce/single-product-summary/README.md` 与 `inc/woocommerce/`。
