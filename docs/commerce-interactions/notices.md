# Notices module (`commerce-notices`)

## `data-ts-module`

`commerce-notices`

## PHP

`template-parts/components/commerce/notice.php` — 空宿主容器；可将 **`data-ts-notice-dismiss`** 设在宿主上以默认自动关闭时间（毫秒，`0` = 关闭自动消失）。

## 职责

- 在宿主子树扫描 **`.woocommerce-message`** / **`.woocommerce-error`** / **`.woocommerce-info`**。
- 补充 **`role="status"`**、`aria-live="polite"`（若尚未设置）。
- 可选自动隐藏（尊重 **`prefers-reduced-motion`**）。

## 限制

- 若 WooCommerce 把通知输出在宿主 **外部**，则不会被扫描到 —— 需把宿主挂在包含通知的祖先节点或使用主题 hook 包裹 WC 通知区域。
