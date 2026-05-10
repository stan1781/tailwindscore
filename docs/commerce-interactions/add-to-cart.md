# Add to cart module (`commerce-add-to-cart`)

## `data-ts-module`

`commerce-add-to-cart`

## PHP

`template-parts/components/commerce/add-to-cart-button.php` — 包裹可加购区域的 SSR 槽位。

## 职责

- 在 **表单提交** 时为提交按钮添加 **`ts-btn--loading`** + **`ts-atc-track`** + `aria-busy`。
- 监听 jQuery **`added_to_cart`** / **`added_to_cart_failed`**，移除 loading。
- 桥接 **`ts:cart:updated`**（payload 来自 WC fragments）。
- 12s 超时兜底清除 loading（防止异常挂死）。

## 不支持

- 自建 AJAX 加购或替换 WC `wc-add-to-cart` 脚本。
- 修改购物车 fragments 内容。
