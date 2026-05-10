# Sticky add-to-cart (mobile)

## Mechanism

- Wrapper: `.ts-purchase-region` opens before `woocommerce_template_single_add_to_cart` (priority `29`) and closes immediately after (priority `31`). See `inc/woocommerce/hooks/pdp-commerce-experience.php`.
- Styling: `src/css/components/commerce-experience/mobile-sticky-atc.css` applies only below `48rem`.

## Why not a fixed duplicate bar?

Duplicating the CTA outside the `<form>` breaks variable products (quantity and attribute state live in one form). The sticky region keeps **the same DOM** WooCommerce submits.

## Scroll padding

`.single-product .ts-commerce-summary` receives extra `padding-bottom` on small screens so the last lines of the short description remain readable above the docked purchase stack.

## Desktop

Desktop uses the optional sticky **summary column** (`tailwindscore/pdp/sticky-summary-column` via `tailwindscore/pdp/commerce-sticky-summary`), not a bottom dock.

## Disabling

```php
add_filter( 'tailwindscore/pdp/commerce-experience', '__return_false' );
```

Or only disable sticky summary:

```php
add_filter( 'tailwindscore/pdp/commerce-sticky-summary', '__return_false' );
```

## Unsupported

- Pinning the CTA while browsing tabs/related sections outside the summary column (would require moving the form or cloning inputs — explicitly out of scope).
