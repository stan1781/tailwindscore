# Add To Cart Module (`commerce-add-to-cart`)

## `data-ts-module`

`commerce-add-to-cart`

## PHP

`template-parts/components/commerce/add-to-cart-button.php`

这个组件只负责输出 SSR 宿主与可选 feedback override，不再承担默认语义所有权。

## 当前职责

- 拦截 `form.cart` / `form.variations_form` 提交
- 为按钮增加 loading / busy 状态
- 直接调用 `requestCartSurface()` 走 cart surface REST 端点
- 成功后应用 cart fragments、打开 cart drawer、显示可见 success toast
- 失败后写入 validation surface，并显示可见 error toast

## 不负责

- 复用 WooCommerce jQuery `added_to_cart` 事件桥
- 维护独立 cart event bus
- 替换 WooCommerce 购物车或 variation 原生数据流
