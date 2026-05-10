# Variation state (`data-ts-variation-ui`)

## jQuery sources

`variation-state-ui.ts` listens on the **same** `.variations_form` node as WooCommerce:

| Event | UI phase |
| --- | --- |
| `found_variation` | `found` — `.ts-variation-runtime--found`, `.ts-variation-wrap--active` |
| `reset_data` | `reset` |
| `hide_variation` | `hidden` |

## Custom event

`jq-bridge.ts` mirrors these into `ts:variation:changed` on `document` for cross-module listeners (gallery-adjacent features, analytics in child themes).

## Distinction: `hide_variation` vs `reset_data`

Both emit `variation: null` on the CustomEvent bus. UI classes differ: `hidden` vs `reset` for subtle styling hooks. Do not use the CustomEvent alone when you need WC’s semantic distinction — subscribe to jQuery on the form instead.

## Unsupported

- Inferring “partial selection” purely from DOM without WC’s `check_variations` lifecycle.
