# commerce — PHP partials

与 **`src/ts/modules/commerce/*`** 配套：`data-ts-module` 名称一致。

| 文件 | `data-ts-module` |
|------|------------------|
| `quantity.php` | `commerce-quantity` |
| `add-to-cart-button.php` | `commerce-add-to-cart` |
| `notice.php` | `commerce-notices` |

单品可变属性：由 `woocommerce_before_add_to_cart_form` 自动输出 **`tailwindscore-variation-runtime`** 包裹（见 `inc/woocommerce/hooks/variation-experience.php`）；变体相关 PHP 片段在 `template-parts/components/variations/`。
