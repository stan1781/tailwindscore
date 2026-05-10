# Mobile commerce patterns

## Sticky purchase region

On viewports under `48rem`, `.ts-purchase-region` uses `position: sticky` with `bottom: max(space, safe-area-inset-bottom)` so the add-to-cart stack remains reachable while scrolling the summary **until** the shopper leaves the summary column (native sticky unmount).

## Safe areas

Horizontal padding respects `env(safe-area-inset-left|right)`; bottom padding respects `env(safe-area-inset-bottom)` on the dock and on the summary column’s extra `padding-bottom` reserve.

## Reduced motion

When `prefers-reduced-motion: reduce` is set, the sticky dock styles are disabled (static layout, no elevated shadow) to avoid disorienting motion cues tied to scroll.

## JavaScript

No dedicated mobile ATC script is required — behaviour is CSS-first. Existing `commerce-add-to-cart` module continues to handle loading states on submit only.

## Unsupported

- A second, cloned CTA bar that mirrors the form (would desync quantity / variations).
- Intersection-based “always visible” fixed footers that overlap WooCommerce notices or admin bars without coordination.
