# Swatch guidelines

## When to use

- Prefer **native `<select>`** for accessibility and WC compatibility.
- Add **`.ts-variation-swatch`** buttons only when each button maps 1:1 to an `<option>` value on the same `name` attribute.

## SSR

主题通过 `woocommerce_dropdown_variation_attribute_options_html` **自动**在每条属性下注入 `swatches/swatch-group`；手动扩展请使用 `template-parts/components/swatches/`。旧路径 `variations/swatch-button.php` 仅转发至 `swatches/swatch-button`。

## Runtime

`variation-selector.ts` listens for clicks on `.ts-variation-swatch` and:

1. Sets the paired `<select>` value.
2. Dispatches a bubbling `change` event so WC’s variation script runs unchanged.

## Styling

- Use `swatch_kind` `text` (default) or `color` (label visually hidden; supply background via custom CSS or inline style from child theme).
- Disabled + selected classes mirror WC option state after `update_variation_values`.

## Unsupported

- Image swatches fed from arbitrary JSON without WC variation data.
- Swatches without a backing `<select>` element in the same form.
