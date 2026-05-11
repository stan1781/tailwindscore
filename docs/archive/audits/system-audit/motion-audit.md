# Motion Audit

## Goal
Keep motion quiet, purposeful, and consistent with premium commerce pacing.

## Stable motion language
- Semantic tokens in `src/css/tokens/motion.css` already define hover, fade, reveal, image, button, gallery, and loading motion.
- Reduced motion support exists across cart, gallery, checkout, sticky ATC, swatches, archive cards, and shell navigation.

## Drift to correct
- Several modules define local `prefers-reduced-motion` blocks instead of fully trusting shared intent-level tokens.
- Focus and motion were visually related but not calibrated together, which made some controls feel sharper than others during keyboard use.
- Reviews had no motion layer and defaulted to whatever WooCommerce or browser behavior happened to be present.

## Actions in this phase
- Preserve token ownership in `motion.css`.
- Tighten focus outlines so interaction state feels consistent even when motion is reduced.
- Keep review and auth surfaces mostly static, with motion reserved for existing buttons and links.

## Legacy patterns to retire
- Decorative transition stacks that do not aid product, form, or navigation clarity.
- Slide/fade combinations for simple text-only state changes.

## Review flags for future work
- New motion should map to an existing semantic token before it ships.
- Sticky and drawer interactions must always be checked with reduced motion enabled.
