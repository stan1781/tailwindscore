# Header Behavior

## Goal

The header should feel minimal, premium, and commerce-aware.

## Supported Modes

- minimal header
- sticky header
- transparent header
- utility layout with search, account, and cart slots

## Sticky Rules

- Sticky behavior should stay compact.
- The header should not grow or collapse dramatically.
- Scrolled state should rely on subtle surface and shadow changes.
- Sticky logic remains SSR-friendly with progressive enhancement.

## Transparent Rules

- Transparent mode is appropriate for brand-led entry views.
- Once scrolled, transparent mode resolves into the standard elevated surface.
- Transparency should never compromise navigation legibility.

## Unsupported Behavior

- oversized sticky headers
- app-like shrinking and expanding chrome
- route-aware SPA navigation shells
- toolbar density that competes with product content
