# Trust UI

## Built-in primitives

- **`.ts-trust-label`** and **`.ts-trust-row`** (see `src/css/components/commerce/trust-label.css`) — use inside the summary or below the price for shipping, returns, or payment badges.
- **Stock messaging** — `.stock` rendered ahead of the cart form is styled inside `.ts-purchase-region` for clear in-stock / out-of-stock hierarchy.

## SSR placement

Trust chips should be output via WooCommerce hooks (e.g. `woocommerce_single_product_summary`) or a small child-plugin — the theme does **not** inject marketing copy by default.

## Rhythm with price

The **lead stack** (`.ts-commerce-summary__lead`) wraps title → rating → price so optional trust rows can sit immediately after price using priority `11`–`18` hooks without breaking the layout contract.

## Unsupported

- Automatic “trust score” widgets.
- Third-party review aggregators (styling only applies to WooCommerce core rating HTML where present).
