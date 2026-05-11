# Accessibility Audit

## Goal
Make interaction consistency visible across focus, live feedback, keyboard paths, and reduced motion.

## Stable foundations
- Global `:focus-visible` styling exists in base layer.
- Feedback validation and toasts already use live regions and `role="alert"` / `role="status"`.
- Search, cart, checkout, and account each have TS focus modules.

## Refinements in this phase
- Increase global focus offset so keyboard state feels deliberate on premium surfaces.
- Ensure form controls share the same focus treatment for both `:focus` and `:focus-visible`.
- Add review form and review pagination into the same focus language as the rest of commerce.

## Remaining inconsistencies
- Some components still define bespoke focus styles instead of leaning more directly on global primitives.
- Review verification and purchase-required states now use shared empty-state language, but archive empties still need the same treatment.
- Live region usage is strongest in runtime feedback and weaker in passive SSR-only surfaces.

## Audit targets
- Keyboard traversal in drawers
- Search dialog focus return
- Checkout validation announcement order
- Account auth and reset flow error focus
- Review submission and rating selection
