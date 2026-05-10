# Spacing Rhythm

## Goal

TailwindScore should use a unified vertical rhythm across commerce surfaces so pages feel premium, paced, and intentional.

This system is built on the existing spacing primitives and layout tokens. The change in this phase is governance, not a broad component rewrite.

## Base Scale

TailwindScore uses a 4px-derived spacing system:

- `--ts-space-1`
- `--ts-space-2`
- `--ts-space-3`
- `--ts-space-4`
- `--ts-space-6`
- `--ts-space-8`
- `--ts-space-12`
- `--ts-space-16`

Supporting layout tokens already in use:

- `--ts-space-section-y`
- `--ts-stack-gap-sm`
- `--ts-stack-gap-md`
- `--ts-stack-gap-lg`
- `--ts-section-y-sm`
- `--ts-section-y-md`
- `--ts-section-y-lg`
- `--ts-grid-gap`

## Rhythm Principles

1. Vertical rhythm matters more than isolated spacing values.
2. Adjacent commerce sections should feel composed, not accumulated.
3. Mobile spacing should stay breathable without turning into oversized editorial gaps.
4. Card interiors should stay precise and compact enough for scanning.

## PDP Rhythm

- Product title, price, variation controls, and trust rows should stack with consistent medium rhythm.
- Summary modules should feel grouped by purpose, not separated by arbitrary gaps.
- Sticky buy areas should compress slightly without losing clarity.

Preferred posture:

- information stack uses `--ts-stack-gap-md`
- dense subgroups may use `--ts-stack-gap-sm`
- major section breaks should use the section spacing tokens rather than ad hoc margins

## Archive Rhythm

- Product cards should present a stable media-first stack.
- Title, price, swatches, and actions should have a repeatable cadence across the grid.
- Grid gutters should remain visually quiet.

Preferred posture:

- media-to-body spacing stays compact and repeatable
- metadata and swatches use smaller internal rhythm than the main title-price block
- card-to-card rhythm should come from the grid system, not custom per-card margins

## Section Rhythm

Use section spacing tokens for major commerce bands:

- `--ts-section-y-sm`
- `--ts-section-y-md`
- `--ts-section-y-lg`

Rules:

- adjacent sections should not collapse into plugin-like stacks
- section spacing should create pacing through the page
- section spacing should remain consistent across archive, PDP, and recommendation surfaces

## Card Rhythm

Cards should feel premium through internal restraint.

Rules:

- do not overfill cards with chips, badges, and stacked controls
- keep image, title, price, and supporting actions in a predictable order
- use smaller spacing for supporting metadata than for the primary identity block

## Mobile Rhythm

Mobile commerce should remain calm and usable.

Rules:

- maintain tap target clarity
- avoid cramped stacks around buttons, swatches, and sticky purchase controls
- avoid oversized gaps that break scanning continuity

## Anti-Patterns

Avoid:

- arbitrary one-off spacing values
- section spacing that changes by component author preference
- cards with equal visual weight for every row
- stacked badges and chips that consume more rhythm than the product itself
