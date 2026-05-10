# Empty State Audit

## Goal
Create one calm premium commerce language across absent-content and unavailable-flow states.

## Unified language introduced
- `cart`
- `checkout-unavailable`
- `orders`
- `downloads`
- `addresses`
- `logged-out`
- `search-results`
- `reviews`
- `variation-unavailable`
- `out-of-stock`

These now live in `tailwindscore_feedback_empty_state_copy()`.

## Current gaps
- Search default discovery state is intentionally more exploratory than the generic empty-state component.
- Variation unavailable and out-of-stock messaging still rely partly on runtime validation language rather than fully shared server-rendered surfaces.
- No-product archive empty states still depend on WooCommerce archive behavior and should be reviewed next.

## Tone guardrails
- Calm, premium, concise.
- No onboarding language.
- No reward mechanics.
- No emoji or mascot framing.

## Follow-up modules
- `woocommerce/archive-product.php`
- `template-parts/search/search-empty.php`
- variation validation messaging in `src/ts/modules/variations/variation-feedback.ts`
