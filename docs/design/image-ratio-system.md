# Image Ratio System

## Goal

TailwindScore should use a disciplined image ratio system so commerce surfaces feel like one premium catalog rather than a collection of unrelated modules.

## Supported Ratio Families

- portrait
- square
- landscape

## Default Premium Posture

The default TailwindScore commerce posture is portrait.

Portrait ratios support:

- premium DTC catalog browsing
- cleaner vertical product storytelling
- more stable archive rhythm
- stronger consistency across cards and recommendations

Square and landscape ratios remain supported, but they should be chosen intentionally.

## Surface Mapping

### Archive cards

Default: portrait

Rules:

- archive grids should prefer one dominant card ratio
- ratio changes inside the same row should be treated as exceptions, not the default system

### Gallery thumbs

Default: square

Rules:

- thumbs should prioritize legibility and alignment
- use square containers unless the gallery system has a strong content reason to do otherwise

### Swatches

Default: square

Rules:

- swatch image surfaces should stay compact and stable
- square containers keep preview signals consistent and predictable

### Recommendation cards

Default: portrait

Rules:

- recommendation rails should visually align with archive logic when they are product-led
- landscape should be reserved for editorial or storytelling modules, not default product merchandising

## Exception Rules

Exceptions are allowed when content clearly demands them, but they should be narrow and documented.

Appropriate exceptions:

- landscape editorial modules
- special collection banners
- brand-specific media storytelling blocks

Inappropriate exceptions:

- ratio changes introduced ad hoc by individual components
- mixing portrait and square product cards in the same default archive system
- swatch image containers that drift in shape by attribute type without a system reason

## Anti-Patterns

Avoid:

- uncontrolled ratio drift between adjacent commerce modules
- portrait archive cards with square recommendation cards by default
- using ratio changes as decoration instead of content structure
