# Mobile Commerce Polish

## Goal

Refine sticky and touch commerce behavior for premium mobile browsing and buying.

## Decisions

- Mobile sticky purchase region now uses shared card radius and overlay shadow semantics.
- Safe-area handling stays intact, but the dock shadow and corner treatment are restrained.
- Purchase controls and selectors align to control radius tokens across mobile and desktop.
- Tab controls keep compact card-like affordances on mobile instead of plugin-style stacked strips.

## Replaced Patterns

- Heavy bottom dock shadow replaced with lighter overlay depth
- Mixed control radii replaced by shared control radius token
- Sticky CTA styling aligned with the broader commerce control system

## Deprecated Styles

- Thick sticky dock chrome
- Mobile-only oversized radii
- Aggressive CTA emphasis disconnected from the rest of the UI

## Unsupported Interactions

- Mobile sticky bars that dominate the viewport
- Touch targets styled as novelty controls
- Safe-area padding used as decoration instead of ergonomics
