# Variable product UX rules

## Responsibilities

| Layer | Owns |
| --- | --- |
| **WooCommerce** | Variation matching, `found_variation`, cart POST, `wc_add_to_cart_variation_params`, template `tmpl-variation-template`. |
| **`tailwindscore-variation-runtime`** | Attribute UI affordances, visual state classes, cosmetic price transitions, gallery index sync via public gallery API. |
| **TailwindScore gallery** | Embla scroll API exposed on `.ts-gallery` for attachment index jumps. |

## Event flow

```text
WC VariationForm (jQuery)
  → found_variation | reset_data | hide_variation
      → jq-bridge.ts → document `ts:variation:changed`
          → variation-image-sync.ts → scrollGalleryToIndex(...)
      → variation-state-ui.ts → `data-ts-variation-ui` + body classes
WC DOM updates (price_html)
  → variation-price-transition.ts (MutationObserver, cosmetic only)
```

## Progressive enhancement

1. **SSR** — Optional hosts from `woocommerce_after_variations_table` (`variation-price-state.php`, `variation-stock.php`).
2. **TS** — Mounts on `[data-ts-module="tailwindscore-variation-runtime"]` wrapping only variable `form.variations_form`.
3. **Swatches** — 见 `docs/swatches-experience/`；由 **Swatches Experience** 注入，原生 `<select>` 仍为权威数据源。

## Unsupported

- Custom SKU / price engines, configurator SPAs, or replacing `wc-add-to-cart-variation.js`.
- Predicting variation availability without WC (`update_variation_values` still drives `<option disabled>`).
