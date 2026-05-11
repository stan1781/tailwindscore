# TailwindScore Lite Architecture

TailwindScore is being reduced from a commerce governance framework into a long-term maintainable premium WooCommerce theme.

This document is the active Lite architecture map. It defines what the theme is, what must remain stable, and where complexity should be reduced next.

## Core Direction

- SSR-first
- WordPress-native
- WooCommerce-native
- Progressive enhancement
- Minimal abstraction
- Lightweight runtime
- Small docs surface
- Long-term maintainability

## Priority Order

1. Commerce UX
2. Maintainability
3. SSR-first
4. WooCommerce compatibility
5. Accessibility
6. Performance
7. Simplicity

## Lite Target Shape

The target structure should converge toward:

```text
inc/
  helpers/
  theme/
  woocommerce/

template-parts/
  account/
  cart/
  checkout/
  search/

src/
  css/
  ts/
```

This is a direction, not a rewrite mandate. Reduction should happen through consolidation and boundary cleanup, not through a broad rebuild.

## Current Complexity Hotspots

- `docs/` is the largest active complexity source, especially governance and archive material.
- `inc/configuration/`, `inc/content-surfaces/`, `inc/content-moods/`, and `inc/presets/` form a governance-heavy customization stack larger than the theme needs.
- `inc/woocommerce/hooks/` and `inc/woocommerce/adapters/` split rendering ownership across too many PHP layers.
- `src/css/components/` and `template-parts/components/` have grown into broad system layers instead of narrow commerce-view layers.
- `src/ts/` uses a registry-and-entry pattern that works, but is heavier than necessary for a Lite theme.

## Reduction Boundaries

### Keep

- WooCommerce-native rendering flow
- Server-rendered templates as the primary source of truth
- Progressive enhancement for interaction layers
- Accessible focus, motion, keyboard, and live-region behavior
- Premium cart, checkout, search, account, and feedback experiences

### Reduce

- governance narrative depth
- scanner-driven architecture assumptions
- registry depth beyond tokens, presets, notices, and essential commerce copy
- duplicated runtime ownership
- duplicated message fallback chains
- adapter and hook fragmentation without clear reuse
- docs sprawl and AI-only process overhead

### Do Not Add

- new governance systems
- new scanner capability
- new AI workflow layers
- new registry families
- new lifecycle families
- new enterprise abstraction layers

Only runtime-critical, accessibility, WooCommerce compatibility, localization, or performance fixes justify exceptions.

## Phase 1 Audit Findings

### Over-engineered Runtime

Current TS bootstrapping is spread across:

- `src/ts/bootstrap.ts`
- `src/ts/module-mounts.ts`
- `src/ts/init.ts`
- multiple feature modules
- multiple page entry files

This pattern is functional, but it increases mental overhead for a theme whose JS exists to enhance SSR commerce surfaces, not to act as an application framework.

### Governance-Heavy Areas

The heaviest framework-oriented areas are:

- `inc/configuration/governance.php`
- `scripts/governance-scan.mjs`
- `governance-baseline.json`
- `docs/ai/`
- `docs/governance/`
- `docs/governance-audit/`
- `docs/phases/`

These should no longer define the center of the project.

### Duplicated Abstraction

- presets are split across runtime and metadata registries
- content copy is split across surface registry, mood registry, helpers, templates, and TS runtime literals
- WooCommerce rendering is split across hooks, adapters, template parts, and template overrides

## Safe Next Reduction Pass

Phase 2 should focus on runtime and PHP ownership reduction:

- flatten WooCommerce rendering ownership into `inc/woocommerce`, `inc/helpers`, and domain template parts
- simplify TS bootstrap and registration layers
- define the minimal registry boundary
- mark archive-only docs and narrow default documentation entry points

## Transitional Note

Most existing directories remain in place during the reduction phase. Unless a newer root-level document says otherwise, legacy topical docs and governance trees should be treated as transitional or historical, not as the preferred architecture model.

## Ownership Flattening Audit

This audit marks the shift from framework-style layered ownership to feature-owned WooCommerce theme architecture.

The immediate goal is not broad deletion. The goal is to identify where ownership is too fragmented and where reduction can happen safely without breaking commerce UX, WooCommerce flow, SSR rendering, accessibility, or performance.

### 1. Most Fragmented Ownership Directories

The most fragmented ownership is currently split across:

- `inc/woocommerce/`
- `inc/woocommerce/hooks/`
- `inc/woocommerce/adapters/`
- `inc/cart-surface/`
- `inc/search/`
- `inc/account/`
- `inc/checkout/`
- `inc/feedback/`
- `inc/content-surfaces/`

These directories spread feature behavior across hook files, helper files, template fragments, and registry-backed copy lookups. The result is ownership by layer instead of ownership by commerce feature.

### 2. Most Over-engineered Runtime

The deepest runtime indirection was in the TypeScript mount pipeline:

- `src/ts/bootstrap.ts`
- `src/ts/init.ts`
- `src/ts/module-mounts.ts`
- multiple page entry files
- multiple `data-ts-module` mount points in templates

Pass 2 removed the extra `register-*` registration fan-out and replaced registry-style module lookup with one explicit selector-based mount list in `src/ts/module-mounts.ts`. Bootstrap depth is lower than before, though `data-ts-module` contracts still remain.

### 3. Deepest Helper Indirection

The deepest helper indirection currently centers on content copy resolution:

- `inc/content-surfaces/registry.php`
- `inc/content-moods/registry.php`
- feature helpers such as `inc/account/helpers.php`, `inc/cart-surface/helpers.php`, `inc/checkout/helpers.php`, and `inc/search/helpers.php`

The common path is:

```text
feature helper
  -> content surface lookup
  -> optional mood fallback
  -> optional preset mapping
  -> sanitization
  -> template/runtime consumption
```

That is too much ownership depth for a theme whose real goal is stable SSR output.

### 4. Current Bootstrap Layering

PHP bootstrap layering is still concentrated in `inc/bootstrap.php`, but Pass 2 flattened the biggest ownership split. It now loads feature-owned WooCommerce files directly:

- `inc/woocommerce/feedback.php`
- `inc/woocommerce/cart.php`
- `inc/woocommerce/checkout.php`
- `inc/woocommerce/account.php`
- `inc/woocommerce/search.php`

The bootstrap still carries configuration and product/archive-specific files, but the previous helper/rest/hook scattering for core commerce surfaces is now physically reduced.

### 5. Duplicated Runtime Ownership

Runtime ownership is duplicated most clearly in:

- cart drawer feedback
- checkout feedback and loading
- search empty/default states
- account messaging

The same user-facing behavior is often split across:

- PHP helper defaults
- content surface registry fallbacks
- template data attributes
- TS runtime literals
- WooCommerce template overrides

This makes small changes expensive and increases drift risk.

### 6. WooCommerce Ownership Scattering

WooCommerce ownership is currently scattered across:

- `inc/woocommerce/hooks/`
- `inc/woocommerce/adapters/`
- `woocommerce/`
- `template-parts/components/`
- feature-specific helper directories under `inc/`

This is strongest around product, checkout, account, reviews, and cart-related behavior. The architecture reads as layered orchestration instead of direct feature ownership.

### 7. Registry Overuse

Registry use is currently deeper than the Lite target:

- presets
- preset metadata
- content surfaces
- content moods
- Kirki content-surface bindings
- runtime feedback copy bridges

The safe Lite boundary remains:

- tokens
- minimal presets
- notices
- essential commerce copy

Everything beyond that should justify itself with real WooCommerce or localization value.

### 8. Safe Reduction Targets

The safest ownership reductions are:

- continue flattening feature PHP ownership now that `inc/woocommerce/cart.php`, `checkout.php`, `account.php`, `search.php`, and `feedback.php` exist
- collapse thin WooCommerce adapters that only forward already-available values
- reduce `register-*` TS layers into fewer feature-owned entry points
- reduce duplicated cart and checkout feedback ownership
- move single-surface template parts closer to their owning feature instead of broad component taxonomies
- keep `template-parts/components/` only for components with real cross-feature reuse

### 9. Runtime Boundaries That Must Be Preserved

The following runtime boundaries remain non-negotiable:

- WooCommerce native flow stays authoritative
- SSR output stays authoritative
- JS stays progressive enhancement only
- no SPA conversion
- no client-only ownership of cart, checkout, search, or account
- no-js compatibility stays intact
- focus recovery, keyboard navigation, reduced motion, and live regions stay intact

### 10. Reduction Risk

The main risks in this flattening phase are:

- breaking checkout or cart messaging by removing a fallback layer without replacing the SSR source
- moving WooCommerce hooks without preserving execution order
- flattening TS ownership while leaving `data-ts-module` contracts inconsistent
- collapsing helpers without preserving localization and accessibility text handling
- over-flattening shared product/archive behavior that still has legitimate reuse

## Ownership Reduction Impact

- core commerce-surface ownership is now physically flatter under `inc/woocommerce/`
- deleted helper/rest/hook wrapper layers for cart, checkout, account, search, and feedback
- removed extra TS `register-*` files and replaced registry-style fan-out with one explicit module-mount list
- folded PDP section-layout and commerce wrapper hooks into `inc/woocommerce/hooks/single-product-hooks.php`
- folded single-product summary and gallery runtime ownership into `inc/woocommerce/hooks/single-product-hooks.php`
- inlined variation SSR hosts and swatch item rendering into product-owned files, reducing single-use template layers
- localized default checkout, search, and account copy lookup to feature-owned WooCommerce files instead of routing through the deeper surface-mood fallback chain
- localized cart drawer defaults and cart empty-state copy to the cart feature, with PHP only emitting feedback overrides when a cart-specific theme setting exists
- reduced checkout remount ownership to a checkout-specific module subset instead of re-mounting the full known runtime list
- merged cart focus-trap ownership into the cart drawer runtime and removed a separate cart-only hydration module
- narrows the next reduction pass toward remaining product/archive/template indirection

## Runtime Simplification Impact

- TS mounting now flows through one explicit selector-based mount list instead of per-surface register files plus runtime lookup
- cart, checkout, account, search, and feedback ownership no longer bounce through separate helper/rest/hook directories
- add-to-cart defaults now live primarily in runtime code, with PHP only passing feature-local overrides when needed
- cart drawer defaults now follow the same pattern, and cart mutation errors can consume server responses directly without a second shared message layer
- cart error toasts can now stay visible without double-announcing when validation already owns the assertive message
- keeps SSR as the source of truth while reducing runtime-over-runtime patterns

## Preserved Contracts

The flattening phase must preserve:

- cart drawer
- premium checkout
- search overlay
- account experience
- feedback system
- WooCommerce compatibility
- SSR-first rendering
- progressive enhancement
- accessibility behavior
- lightweight runtime ownership

## Unresolved Runtime Debt

Current unresolved runtime debt includes:

- duplicated cart and checkout feedback messages across PHP and TS
- cart notices and some cart REST error strings still sit outside the fully localized cart copy path
- runtime mounts still depend on `data-ts-module`, though the registry lookup layer has been removed
- WooCommerce product runtime is flatter, but add-to-cart feedback and variation runtime still share some contracts across PHP and TS
- swatch rendering ownership is localized, but the broader `template-parts/components/` surface still contains generic commerce fragments

## Recommended Flattening Pass 2

The next bounded pass should continue in this order:

1. reduce remaining product and archive adapter indirection
2. reduce remaining `data-ts-module` breadth where feature-local mounts can stay explicit without harming hydration stability
3. reduce helper lookup depth for cart, checkout, search, and account copy paths
4. consolidate template ownership around commerce features instead of `template-parts/components/`
5. remove dead product/archive wrapper abstractions after ownership is stable
