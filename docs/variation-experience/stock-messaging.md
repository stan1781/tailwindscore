# Stock messaging

## WooCommerce source of truth

Live availability HTML is rendered by WooCommerce into `.woocommerce-variation-availability` when a variation is found. TailwindScore does **not** replace that markup.

## Optional SSR hint

`variation-stock.php` outputs a **static** `.ts-variation-stock-hint` when the filter `tailwindscore/variation/stock_hint_message` returns non-empty text (e.g. shipping SLA). It is purely informational.

## Runtime transitions

`variation-price-transition.ts` observes mutations on `.woocommerce-variation-price` and `.woocommerce-variation-availability` to apply a short opacity transition — **no** parsing of stock numbers.

## Unsupported

- Fake scarcity timers or auto-refreshing inventory AJAX.
- Overriding WC’s out-of-stock template strings from the theme layer (use translations or WC filters instead).
