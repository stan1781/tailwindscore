# Single Product Summary

当前 single product summary 已不再使用单独的 summary hook 文件、单用途 adapter、或 product-summary 组件目录。

## 当前 ownership

- owner: `inc/woocommerce/hooks/single-product-hooks.php`
- hook 策略：在 `woocommerce_init` 上按原 WooCommerce priority 替换 title / rating / price / excerpt / add-to-cart
- 渲染方式：直接在 feature hook 中输出

## 当前目标

- 保持 WooCommerce summary priority 稳定
- 保持 SSR-first
- 避免再引入 summary wrapper / adapter / template indirection
