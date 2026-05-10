# Commerce Visual Language

## Goal

TailwindScore's commerce UI should read as premium catalog software for modern brands, not as a feature-stacked WooCommerce theme.

This phase consolidates the visual language around:

- Shopify premium theme restraint
- Apple-style clarity
- luxury DTC polish
- editorial catalog rhythm
- minimal, image-led merchandising

## Core Principles

### 1. Media leads

Product imagery should carry the first impression. Cards, PDP sections, recommendation rails, and swatch previews should support the media rather than compete with it.

### 2. Calm hierarchy

Hierarchy should come from spacing, typography, ratio, and contrast before it comes from decoration.

### 3. Quiet commerce

The interface should feel commercially confident without shouting. Primary actions stay clear, but the page should never feel promotion-heavy or plugin-stacked.

### 4. Premium density

Layouts should feel breathable and precise. Dense marketplace patterns are out of scope for TailwindScore's premium commerce direction.

### 5. Motion as refinement

Motion should confirm interaction and improve continuity. It should never become spectacle.

## System Relationships

This visual language document is the top-level contract.

Detailed execution rules live in:

- [motion-system.md](./motion-system.md)
- [spacing-rhythm.md](./spacing-rhythm.md)
- [image-ratio-system.md](./image-ratio-system.md)

Supporting systems already present in the token layer remain authoritative for:

- color semantics
- typography scale
- radius scale
- shadow scale
- layout spacing primitives

This phase does not replace those systems. It governs how they are used across commerce surfaces.

## Commerce Surface Rules

### PDP

- Product title leads the information stack without becoming a hero headline.
- Price hierarchy is strong but restrained.
- Meta information stays secondary.
- Sticky commerce surfaces must feel calm and compact.

### Archive

- Product cards should read as premium catalog tiles.
- Card rhythm must favor image continuity and scanning clarity.
- Swatches remain supporting signals, not the center of the card.

### Gallery

- Main gallery media should feel crisp, stable, and premium.
- Thumbs and supporting media should use a disciplined ratio system.
- Motion should reinforce continuity between images, not add theater.

### Swatches

- Swatches are product cues first.
- Radius, spacing, and hover behavior should align with the broader commerce system.
- Avoid visual noise from over-ornamented swatch treatments.

## Radius Rules

Use the existing radius scale consistently:

- `--ts-radius-sm`: small utility surfaces and compact indicators
- `--ts-radius-md`: buttons and form controls
- `--ts-radius-lg`: cards and standard media frames
- `--ts-radius-xl`: large containers and special media shells only when warranted

Rules:

- The same component family should not drift between radius values.
- Swatches, cards, inputs, and galleries should each have one default corner treatment.
- Large, soft corners are acceptable only when they reinforce premium simplicity rather than playfulness.

## Shadow Rules

TailwindScore should use very light elevation.

Rules:

- Use borders and spacing before relying on shadow.
- Default shadow should feel like air, not lift.
- Hover elevation should be subtle and brief.
- Keep shadows neutral and low-contrast.

Avoid:

- thick Bootstrap-like shadows
- colored glow
- purple hover glow
- deep floating card stacks

## Typography Hierarchy

Typography should feel editorial, not generic.

Rules:

- PDP and archive titles use the product title scale, not oversized marketing type.
- Price hierarchy should come from proportion, weight, and contrast.
- Meta text remains quiet, uppercase only when it improves labeling clarity.
- Section headings should follow a repeatable rhythm across commerce sections.

## Unsupported Styles

The following styles are not supported:

- Dribbble-style showpiece UI
- marketplace-dense layouts
- heavy glassmorphism
- complex 3D motion
- cheap hover enlargement
- arbitrary radius drift
- glow-based hover styling
- thick decorative shadows
- uncontrolled image ratio changes
- badge and chip overload
- promo blocks that overpower catalog hierarchy

## Review Criteria

Future commerce UI work should be judged against these questions:

1. Does the UI feel premium because of rhythm and restraint rather than decoration?
2. Is the image hierarchy stronger than the chrome hierarchy?
3. Are spacing, radius, shadow, and motion following the system instead of inventing local style?
4. Does the design avoid drifting toward marketplace density or plugin clutter?
