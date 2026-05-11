# Spacing Audit

## Goal
Unify vertical rhythm across commerce surfaces so forms, cards, drawers, and empty states read as one premium system.

## Stable rhythm to keep
- Section shells already lean on `--ts-space-*` tokens and `clamp()` for macro spacing.
- Checkout, account, cart, and PDP sections generally land in the `space-4` to `space-8` range, which feels calm and editorial.

## Drift to correct
- Search empty/default states use a chip-driven rhythm that feels tighter and more utility-led than cart/account empty states.
- Account auth headers and account form bodies currently use similar spacing but different emotional pacing; login needed more breathing room than field-edit surfaces.
- Review surfaces had no dedicated spacing system and were still inheriting WooCommerce defaults.

## Actions in this phase
- Promote the global empty-state component to a stronger content rhythm with eyebrow, title, body, and action spacing.
- Normalize auth rows to explicit grid gaps instead of inherited WooCommerce margins.
- Establish review card spacing and review form spacing as first-class system primitives.

## Modules to revisit
- `template-parts/search/search-empty.php`
- `template-parts/search/predictive-results.php`
- `woocommerce/myaccount/*.php`
- `woocommerce/single-product-reviews.php`

## Unsupported density
- Dense dashboard-like cards with nested utility rows should not be introduced.
- Mobile drawers should avoid mixed compact and spacious row heights in the same viewport.
