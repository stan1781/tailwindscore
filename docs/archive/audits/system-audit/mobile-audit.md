# Mobile Audit

## Goal
Refine thumb reach, safe pacing, and drawer clarity across the core commerce journey.

## Areas already aligned
- Search surface uses a full-panel modal with dedicated close affordance.
- Cart drawer and mobile navigation already treat motion and focus as first-order concerns.
- Checkout has dedicated mobile CSS.

## Current concerns
- Account auth actions and remember-me rows needed stronger stacked behavior on narrow screens.
- Product reviews previously had no mobile-first layout and would have inherited cramped classic WooCommerce flow.
- Search result empty states and discovery groups still feel denser than cart/account mobile rhythms.

## Actions in this phase
- Keep account actions full-width on mobile.
- Stack review list and review form into a single-column flow below desktop breakpoint.
- Preserve sticky behavior only where it materially supports scanning on large screens.

## Thumb reach checkpoints
- Drawer close buttons
- Checkout primary submit
- Account sign-in / reset actions
- Sticky commerce CTAs

## Safe-area follow-up
- Recheck drawer footers and sticky purchase controls against iOS safe-area insets during visual QA.
