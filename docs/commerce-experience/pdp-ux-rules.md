# PDP UX rules (TailwindScore)

## Conversion hierarchy

1. **Product identity** — title and lead typography establish trust and scanability.
2. **Social proof** — rating sits close to title; never below the fold on typical mobile viewports.
3. **Commercial anchor** — price is the largest typographic emphasis after the title.
4. **Education** — short description explains value; constrained line length (`--ts-prose-max`).
5. **Action** — purchase block (stock, variations, quantity, add to cart) is visually grouped and, on small screens, **sticks** to the lower viewport while the shopper is still in the summary column.

## Purchase flow rhythm

- One WooCommerce form per product type — no duplicate buttons or shadow carts.
- Quantity and primary CTA share a single row from `40rem` upward when markup allows (simple cart form).
- Variable products keep selectors full width; CTA row follows WooCommerce’s `woocommerce-variation-add-to-cart` structure.

## Responsive commerce rules

| Viewport | Intent |
| --- | --- |
| `< 48rem` | Single column; sticky purchase dock; generous bottom padding on `.ts-commerce-summary` so prose is not obscured. |
| `48rem–64rem` | Two-column PDP grid where enabled; summary `max-width` capped for readability beside gallery. |
| `≥ 64rem` | Split gallery + summary; optional sticky summary column (filter below). |

## Filters

- `tailwindscore/pdp/commerce-experience` — master switch for wrappers + default sticky summary behaviour.
- `tailwindscore/pdp/commerce-sticky-summary` — when commerce experience is on, controls sticky summary column (default `true`).

## Unsupported behaviours

- AJAX cart drawer, side cart, or checkout SPA.
- Marketing countdown timers, fake urgency, or third-party “conversion” widgets (not provided by the theme).
- Replacing WooCommerce’s variation JSON or add-to-cart POST flow.
