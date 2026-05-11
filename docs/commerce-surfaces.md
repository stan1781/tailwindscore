# TailwindScore Commerce Surfaces

This document defines the commerce surfaces that must survive architecture reduction.

The project is being simplified around a smaller theme architecture, but these surfaces remain first-class product behavior.

## Must-Preserve Surfaces

### Cart

- cart drawer
- quantity updates
- notices and feedback
- focus recovery
- no-JS fallback through native WooCommerce flow

### Checkout

- premium checkout layout
- payment guidance
- loading and validation feedback
- live-region support
- reduced-motion-safe enhancement

### Search

- search overlay
- predictive search enhancement
- default and empty states
- keyboard navigation
- usable SSR fallback when JS is unavailable

### Account

- account dashboard
- order history and detail
- address flows
- login, recovery, and reset flows
- post-purchase clarity

### Feedback System

- notice rendering
- loading states
- validation states
- empty states
- focus-safe message delivery

## Non-Negotiable Runtime Rules

- SSR-first rendering stays authoritative.
- WooCommerce-native request and form flows stay authoritative.
- No SPA conversion.
- No client-only replacement of cart, checkout, search, or account ownership.
- JavaScript should enhance, not replace, rendered HTML and WooCommerce state.

## Accessibility Rules

- preserve focus recovery
- preserve keyboard navigation
- preserve reduced motion behavior
- preserve live region support
- preserve no-JS fallback

## Performance Rules

- keep JS lightweight
- avoid duplicate runtime ownership
- minimize async refresh behavior
- avoid client-side abstractions that duplicate WooCommerce truth

## Reduction Guidance

It is acceptable to reduce layers, folders, helper count, registry depth, and documentation surface if these commerce behaviors stay intact.

When a reduction choice conflicts with framework neatness, prefer the simpler path that keeps WooCommerce compatibility and user-facing commerce UX stable.
