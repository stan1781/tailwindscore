# Commerce Archive Experience Design

Date: 2026-05-09
Scope: Premium Commerce Collection Foundation
Status: Draft approved in conversation, written for implementation review

## Goal

Establish a premium archive experience system for TailwindScore on top of the native WooCommerce product loop.

This phase delivers a stronger product card and collection experience without turning archive pages into a mini PDP or SPA.

Target direction:

- Shopify premium theme quality
- Apple-style collection restraint
- Premium DTC commerce rhythm
- WooCommerce-compatible SSR-first behavior

## Non-Goals

This phase explicitly does not include:

- React or Vue archive runtime
- Rewriting the WooCommerce product loop
- Full SPA collection behavior
- Complex quick view
- Full variation selection inside archive cards
- Custom marketplace-style card logic
- Client-side product data fetching for archive cards

## Architecture

The archive experience is a distinct system layered on top of the existing TailwindScore product card foundation.

Flow:

`WooCommerce Product Loop`
-> `tailwindscore_adapter_product_card_props()`
-> `product-card` SSR shell
-> `archive/*` SSR subcomponents
-> `archive runtime` progressive enhancement

Responsibilities:

- WooCommerce native loop
  - Owns loop structure, pagination, sorting, hooks, and compatibility.
- Adapter
  - Produces card data only.
  - Does not generate structural HTML.
  - Does not own runtime behavior.
- `product-card` shell
  - Owns the outer article, link shell, body, and footer boundaries.
  - Remains the stable archive tile container.
- Archive SSR subcomponents
  - Own media, swatches, action reveal, and badge composition.
- Archive runtime
  - Enhances SSR output with hover image behavior, preview swatches, and collection state classes.

Core rules:

- SSR first
- Progressive enhancement only
- Archive card is not a mini PDP
- PDP swatches inform archive swatches, but archive does not replicate full PDP behavior

## Component Structure

Create:

- `template-parts/components/archive/product-card-media.php`
- `template-parts/components/archive/product-card-swatches.php`
- `template-parts/components/archive/product-card-actions.php`
- `template-parts/components/archive/product-card-badges.php`

The existing `template-parts/components/product-card.php` remains the top-level shell and composes archive subcomponents internally.

### `product-card-media.php`

Responsibilities:

- Render primary media
- Render optional secondary image
- Establish image ratio container
- Expose SSR hooks for hover and swatch preview sync
- Provide stable media host markup for runtime enhancement

### `product-card-swatches.php`

Responsibilities:

- Render display-only swatch previews
- Support color preview
- Support image preview
- Show active item indication
- Limit visible items and optionally render `+N`

Out of scope:

- Variation form rendering
- Variation matching
- Archive add-to-cart state changes based on swatch selection

### `product-card-actions.php`

Responsibilities:

- Render hover-reveal action zone
- Keep SSR-first output usable without JS
- Support mobile fallback where actions remain visible or easier to access

Phase-one product-type rule:

- Simple products: direct add-to-cart action allowed
- Variable, grouped, external, and other non-simple products: do not attempt direct archive-side variation add-to-cart; route user to PDP-oriented action

### `product-card-badges.php`

Responsibilities:

- Render archive badge stack in a dedicated component
- Keep badge placement and spacing consistent with the archive media system

## Props Contract

Archive-facing card props should move from flat fields toward grouped data structures.

Recommended groups:

### `media`

- `primary`
- `secondary`
- `aspect_ratio`
- `hover_enabled`

### `swatches`

- `items`
- `mode` = `preview`
- `max_visible`
- `more_count`

### `actions`

- `primary`
- `wishlist_slot_html`
- `quick_slot_html`
- `reveal_mode`

### `badges`

- badge list

### `collection`

- `density`
- `card_style`
- `mobile_compact`

Adapter rule:

- Adapter returns structured data.
- Components define local structure.
- Runtime reads SSR state through classes and `data-*`, but does not construct card HTML.

## Runtime Structure

Create:

- `src/ts/modules/archive/index.ts`
- `src/ts/modules/archive/product-card-hover.ts`
- `src/ts/modules/archive/archive-swatches.ts`
- `src/ts/modules/archive/collection-grid.ts`

### `index.ts`

Responsibilities:

- Discover archive card and collection roots
- Mount archive card hover behavior
- Mount display-only archive swatches behavior
- Mount collection grid state classes

### `product-card-hover.ts`

Responsibilities:

- Secondary image preload
- Hover image swap or crossfade
- Pointer and focus state alignment
- Reduced motion fallback

Rules:

- No dramatic translation or scale behavior
- No motion-dependent usability
- Focus should receive equivalent media treatment where appropriate

### `archive-swatches.ts`

Responsibilities:

- Handle inline preview-only swatch interactions
- Update current swatch selected state
- Sync media preview when a swatch carries preview media
- Preserve local card scope

Rules:

- No variation events
- No variation form ownership
- No archive add-to-cart state machine
- Pure color swatches may update selected indication without faking variation selection

### `collection-grid.ts`

Responsibilities:

- Apply responsive and input-mode state classes
- Support density and rhythm classes for CSS
- Keep behavior light and presentational

Out of scope:

- Masonry
- Client-side re-layout algorithm
- Infinite-scrolling app behavior

## UX Direction

The archive should feel like a premium collection, not a traditional WooCommerce card grid.

Rules:

- Quiet visual system
- Strong media-first hierarchy
- Generous but disciplined spacing
- Clear catalog rhythm
- Mobile-friendly scanning

Avoid:

- Cheap hover animation
- Heavy shadows
- Button clutter
- Overactive transitions

### Product Card Rhythm

- Media is the first signal
- Default ratio system: `4 / 5`
- Typography and spacing create hierarchy more than decoration does
- Hover states should be restrained
- Swatches remain supporting signals, not the center of interaction

### Secondary Image Hover

- SSR should include the optional second image when available
- Runtime controls swap behavior
- Preload secondary image
- Respect `prefers-reduced-motion: reduce`

### Inline Swatches

This phase uses display-only inline swatches.

Supported:

- Color preview
- Image preview
- Selected indication
- Preview sync with card media

Not supported:

- Full variation selection
- Swatch-driven archive-side price mutation
- Swatch-driven archive-side stock mutation

### Hover Actions

Supported:

- Add to cart for simple products
- Wishlist slot
- Quick action slot

Requirements:

- SSR first
- Mobile fallback
- No complex quick view

## CSS System

Create:

- `src/css/components/product-archive/product-card.css`
- `src/css/components/product-archive/product-card-media.css`
- `src/css/components/product-archive/product-card-swatches.css`
- `src/css/components/product-archive/product-card-actions.css`
- `src/css/components/product-archive/collection-grid.css`
- `src/css/components/product-archive/index.css`

Rationale:

Archive collection styling is now its own system and should not continue to accumulate inside the existing commerce card stylesheet.

Visual guidance:

- Reduce card heaviness
- Reduce shadow dominance
- Keep borders subtle
- Let ratio, spacing, typography, and calm transitions carry the premium feel

## Documentation

Create:

- `docs/archive-experience/collection-rules.md`
- `docs/archive-experience/product-card-ux.md`
- `docs/archive-experience/mobile-grid.md`
- `docs/archive-experience/archive-swatches.md`

Documentation must define:

- Responsibilities
- Runtime boundaries
- Unsupported behaviors
- SSR-first expectations
- Relationship to existing PDP swatches behavior

## Unsupported Behaviors

Explicitly unsupported in this phase:

- Full variation form inside archive card
- Complex quick view
- React/Vue archive runtime
- Replacement of WooCommerce native loop
- Full SPA collection behavior
- Client-driven product data orchestration
- Guaranteed bidirectional sync between archive swatches and PDP variation state

## Implementation Notes

Implementation should prefer extension of existing patterns:

- Reuse current component runtime conventions
- Reuse current swatch visual vocabulary where applicable
- Keep runtime modules narrow in ownership
- Keep mobile fallback behavior in SSR and CSS first, JS second

## Testing Strategy

Verification should cover:

- Archive card SSR output remains usable without JS
- Secondary image swap works with pointer and focus
- Reduced motion path disables motion-heavy transitions
- Display-only swatches update local preview state correctly
- Simple product action remains usable
- Variable product cards do not expose misleading direct variation add-to-cart flows
- Mobile layouts preserve rhythm and avoid overlap

## Phase Deliverable

This phase delivers the premium commerce collection foundation:

- Archive SSR component foundation
- Archive adapter props contract
- Archive runtime skeleton
- Premium collection CSS foundation
- Archive experience documentation

It does not expand into filtering, faceting, quick view systems, or marketplace behaviors.
