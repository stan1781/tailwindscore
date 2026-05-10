# PHP Component APIs

- **Button** — `template-parts/components/button.php`
- **Input** — `template-parts/components/input.php`
- **Product Card** — `template-parts/components/product-card.php`

渲染入口统一使用：`tailwindscore_component( '{name}', $args )` 或 `get_template_part( 'template-parts/components/{name}', null, $args )`。

---

## Button

输出 **单一主按钮或链接**，使用 `.ts-btn` 体系（primary / secondary / ghost），处理 loading / disabled / 图标槽位。

**不支持**：Dropdown / split button / toggle group；业务 AJAX 仅视觉状态 `loading`。

### Props

| Key | Type | Default | 说明 |
|-----|------|---------|------|
| `variant` | `string` | `'primary'` | `primary` \| `secondary` \| `ghost` |
| `size` | `string` | `'md'` | `sm` \| `md` \| `lg` |
| `label` | `string` | `''` | 可见文案；纯图标时必须配合 `aria_label` |
| `href` | `string` | `''` | 非空则渲染 `<a>`（否则 `<button>`） |
| `type` | `string` | `'button'` | `button` \| `submit` \| `reset` |
| `name` | `string` | `''` | `name`（表单） |
| `value` | `string` | `''` | `value` |
| `icon_html` | `string` | `''` | 内联 SVG（经 `tailwindscore_kses_icon_html()`） |
| `icon_position` | `string` | `'start'` | `start` \| `end` |
| `loading` | `bool` | `false` | `.ts-btn--loading`、`aria-busy` |
| `disabled` | `bool` | `false` | |
| `aria_label` | `string` | `''` | 纯图标时建议传入 |
| `attributes` | `array` | `[]` | 额外 HTML 属性 |

### Example

```php
tailwindscore_component(
    'button',
    array(
        'variant' => 'primary',
        'size'    => 'lg',
        'label'   => __( 'Add to cart', 'tailwindscore' ),
        'type'    => 'submit',
        'name'    => 'add-to-cart',
    )
);
```

---

## Input

输出 **标签 + 文本类控件 + help/error**，类名锁定为 `.ts-field` / `.ts-label` / `.ts-input`，与 WooCommerce 常用的 `input-text` 并存。

**不支持**：`textarea`、`select`（各有独立组件）；服务端校验逻辑。

### Props

| Key | Type | Default | 说明 |
|-----|------|---------|------|
| `id` | `string` | `''` | 缺省自 `name` 生成 |
| `name` | `string` | `''` | `name` |
| `type` | `string` | `'text'` | `text` \| `email` \| `url` \| `tel` \| `number` \| `password` \| `search` \| `date` \| `datetime-local` \| `time` |
| `value` | `string` | `''` | |
| `label` | `string` | `''` | |
| `placeholder` | `string` | `''` | |
| `help` | `string` | `''` | `.ts-help` |
| `error` | `string` | `''` | `.ts-error`、关联 `aria-describedby` |
| `required` | `bool` | `false` | 标签星号 + `required` |
| `disabled` | `bool` | `false` | |
| `autocomplete` | `string` | `''` | |
| `inputmode` | `string` | `''` | |
| `wrapper_class` | `string` | `''` | 仅允许 `ts-*` 前缀 |
| `attributes` | `array` | `[]` | 透传到 `<input>` |

### Example

```php
tailwindscore_component(
    'input',
    array(
        'name'        => 'billing_first_name',
        'label'       => __( 'First name', 'tailwindscore' ),
        'autocomplete'=> 'given-name',
        'required'    => true,
    )
);
```

---

## Product Card

**SSR 商品卡外壳**：媒体区、标题区、页脚（价格 + 可选 actions）。可选 `permalink` 时以 `.ts-product-card__shell` 包裹媒体与标题。

**不支持**：Carousel、Quick view、Wishlist、变体预览。

### Props

| Key | Type | Default | 说明 |
|-----|------|---------|------|
| `permalink` | `string` | `''` | 非空则渲染 `a.ts-product-card__shell` |
| `title` | `string` | `''` | |
| `title_tag` | `string` | `'h3'` | `h2` \| `h3` \| `h4` \| `p` |
| `title_attributes` | `array` | `[]` | 附加到标题元素 |
| `media_html` | `string` | `''` | 整块媒体 HTML |
| `image_url` | `string` | `''` | 与 `media_html` 二选一 |
| `image_alt` | `string` | `''` | |
| `image_width` / `image_height` | `int\|string` | `''` | |
| `badges` | `array` | `[]` | 多枚徽章：每项传给 `badge` 组件 |
| `badge` | `array\|null` | `null` | 单徽章兼容入口 |
| `price` | `array` | `[]` | 传给 `price` 组件 |
| `actions_html` | `string` | `''` | 页脚动作槽 |

### Example

```php
tailwindscore_component(
    'product-card',
    array(
        'permalink' => get_permalink( $product_id ),
        'title'     => get_the_title( $product_id ),
        'image_url' => get_the_post_thumbnail_url( $product_id, 'woocommerce_thumbnail' ),
        'badge'     => array( 'label' => __( 'Sale', 'tailwindscore' ), 'variant' => 'sale', 'size' => 'sm' ),
        'price'     => array( 'price_html' => $product->get_price_html() ),
    )
);
```

**WooCommerce 集成**：商店循环中由 `tailwindscore_adapter_product_card_props()` 生成 props（见 `docs/woocommerce/adapters/README.md`）。
