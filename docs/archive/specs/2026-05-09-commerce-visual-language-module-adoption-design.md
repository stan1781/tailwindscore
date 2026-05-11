# Commerce Visual Language Module Adoption Design

Date: 2026-05-09
Scope: Semantic token adoption for archive card, PDP summary, gallery, and swatches
Status: Draft approved in conversation, written for implementation

## Goal

Adopt the new commerce visual language at the module layer by updating existing CSS for:

- archive card
- PDP summary and its adjacent commerce surfaces
- gallery and thumbs
- swatches

This phase should move these modules to semantic motion token consumption and tighten old visual patterns that drift away from the premium commerce direction.

## Non-Goals

This phase explicitly does not include:

- new commerce features
- markup restructuring
- new runtime behavior
- component API redesign
- marketplace density patterns
- deep cross-system refactors outside the targeted CSS files

## Design Direction

The module layer should align with the premium commerce system established in the visual language documentation:

- calm hover behavior
- lighter elevation
- disciplined radius usage
- stable ratio posture
- motion that confirms interaction without spectacle

## Adoption Strategy

### 1. Archive card

Adopt semantic motion tokens for:

- card border/background transitions
- media crossfade
- action reveal

Visual tightening:

- keep the portrait ratio as the default archive posture
- reduce decorative hover feeling
- keep border-led hierarchy over shadow-led hierarchy

### 2. Gallery and thumbs

Adopt semantic motion tokens where interactive controls or thumb states transition.

Visual tightening:

- reduce heavy rounded treatment
- reduce shadow thickness on nav controls
- align thumbs and main media framing with the premium catalog posture

### 3. Swatches

Adopt semantic motion tokens across:

- text swatches
- color swatches
- image swatches
- image stack swatches

Visual tightening:

- unify radius posture
- replace hardcoded ease and duration values
- keep selected state crisp without glowing or oversized emphasis

### 4. PDP summary adjacency

The product summary shell itself is thin, so the primary adoption surface is the surrounding commerce CSS:

- purchase flow
- product information tabs
- variation state
- variation price transitions

Visual tightening:

- calm the primary CTA hover and shadow behavior
- align tab transitions with the motion system
- replace hardcoded transition values with semantic tokens

## File Scope

Primary files:

- `src/css/components/product-archive/product-card.css`
- `src/css/components/product-archive/product-card-media.css`
- `src/css/components/product-archive/product-card-actions.css`
- `src/css/components/gallery/index.css`
- `src/css/components/swatches/swatch-base.css`
- `src/css/components/swatches/swatch-color.css`
- `src/css/components/swatches/swatch-image.css`
- `src/css/components/swatches/swatch-text.css`
- `src/css/components/swatches/swatch-stack.css`
- `src/css/components/commerce-experience/purchase-flow.css`
- `src/css/components/commerce-experience/product-information.css`
- `src/css/components/variation-experience/variation-state.css`
- `src/css/components/variation-experience/variation-price.css`

## Rules

- Prefer semantic motion tokens over raw duration and easing values.
- Tighten old hover patterns where they feel too animated, too soft, or too heavy.
- Reduce roundness when it reads as generic retail chrome instead of premium restraint.
- Reduce shadow prominence where border and spacing should carry hierarchy.
- Keep any reduced-motion handling complete.

## Verification

This phase is complete when:

- targeted modules consume semantic motion tokens where appropriate
- obvious heavy hover, heavy shadow, and radius drift are tightened
- reduced-motion support remains intact
- the CSS build passes without regressions in the pipeline
