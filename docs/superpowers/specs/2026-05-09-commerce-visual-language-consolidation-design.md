# Commerce Visual Language Consolidation Design

Date: 2026-05-09
Scope: Premium Commerce Design System Refinement
Status: Draft approved in conversation, written for implementation review

## Goal

Establish a unified commerce visual language for TailwindScore that raises the storefront toward a premium DTC standard without adding new runtime systems or expanding commercial feature scope.

This phase focuses on token refinement, interaction rhythm, and design governance so that existing commerce surfaces can converge on a consistent visual system.

Target direction:

- Shopify premium theme clarity
- Apple-style restraint
- Luxury DTC commerce polish
- Editorial catalog rhythm
- Minimal, image-led premium merchandising

## Non-Goals

This phase explicitly does not include:

- New complex commerce features
- New large runtime systems
- Marketplace behavior or marketplace density patterns
- Component-level redesign across all existing commerce modules
- 3D motion systems
- Heavy glassmorphism or decorative visual effects
- Archive or PDP feature expansion beyond minimal styling alignment

## Architecture

This phase introduces a visual governance layer rather than a new application layer.

Flow:

`preset tokens`
-> `semantic design tokens`
-> `Tailwind @theme mapping`
-> `base and component CSS consumption`
-> `design documentation as system contract`

Responsibilities:

- `src/css/tokens/presets/default.css`
  - Continues to own primitive and semantic theme variables.
- `src/css/tokens/motion.css`
  - Owns the complete motion token system and reduced motion policy.
- Existing token bridge files
  - Continue mapping `--ts-*` variables into the Tailwind theme surface.
- Existing component CSS
  - Remains structurally intact in this phase.
  - Only receives minimal compatibility alignment where needed.
- `docs/design/*`
  - Becomes the authoritative contract for visual language rules, unsupported styles, and system usage.

Core rules:

- No new complex runtime
- No feature-led expansion
- Token-first refinement
- Documentation-backed design governance
- Minimal CSS touching outside the token layer

## Deliverables

Create:

- `src/css/tokens/motion.css`
- `docs/design/commerce-visual-language.md`
- `docs/design/motion-system.md`
- `docs/design/spacing-rhythm.md`
- `docs/design/image-ratio-system.md`

Modify only as needed:

- Token import and assembly files required to load `motion.css`
- Existing token files where compatibility mappings are needed
- Existing base or component CSS only if a missing token bridge would otherwise block adoption

## Motion System

`src/css/tokens/motion.css` defines the official motion system for commerce UI.

### Motion token layers

#### 1. Primitive motion tokens

- `--ts-duration-instant`
- `--ts-duration-fast`
- `--ts-duration-normal`
- `--ts-duration-slow`
- `--ts-ease-standard`
- `--ts-ease-smooth`
- `--ts-ease-emphasis`
- `--ts-ease-exit`

These are the canonical timing and easing primitives for commerce interactions.

#### 2. Semantic motion tokens

- `--ts-motion-hover-duration`
- `--ts-motion-hover-ease`
- `--ts-motion-fade-duration`
- `--ts-motion-fade-ease`
- `--ts-motion-reveal-duration`
- `--ts-motion-reveal-ease`
- `--ts-motion-image-duration`
- `--ts-motion-image-ease`
- `--ts-motion-button-duration`
- `--ts-motion-button-ease`
- `--ts-motion-gallery-duration`
- `--ts-motion-gallery-ease`

Components should consume semantic motion tokens instead of hardcoding primitive timing values.

#### 3. Compatibility mappings

Existing motion consumers that use:

- `--ts-duration-fast`
- `--ts-duration-normal`
- `--ts-ease-standard`

must continue to work without breakage. New semantic tokens layer on top of current usage rather than replacing it abruptly.

### Motion behavior rules

The motion system must define consistent interaction rhythm for:

- hover
- fade
- reveal
- image transitions
- button states
- gallery interactions

Principles:

- Motion should clarify interaction, not advertise itself.
- Motion should feel calm, short, and premium.
- Motion should avoid theatrical behavior.
- Motion should not be required for comprehension.

### Explicitly unsupported motion

- bounce
- elastic easing
- overshoot scaling
- exaggerated zoom hover
- large parallax as a default experience
- decorative motion without interaction value

## Reduced Motion Policy

All relevant commerce interactions must fully support `prefers-reduced-motion: reduce`.

Covered areas:

- hover
- gallery
- sticky surfaces
- image transitions
- button state changes
- reveal transitions

Policy:

- Remove non-essential translation and scale.
- Replace animated movement with near-instant or opacity-only state changes where appropriate.
- Avoid gesture-like sliding as the only state signal.
- Keep the interface fully understandable in reduced motion mode.

This policy must live both in token documentation and in CSS behavior rules where needed.

## Spacing Rhythm

This phase defines a commerce spacing rhythm as a rules system first, not a broad component rewrite.

Existing spacing primitives remain the base:

- `--ts-space-1`
- `--ts-space-2`
- `--ts-space-3`
- `--ts-space-4`
- `--ts-space-6`
- `--ts-space-8`
- `--ts-space-12`
- `--ts-space-16`

Existing semantic layout tokens remain valid:

- `--ts-space-section-y`
- `--ts-stack-gap-*`
- `--ts-section-y-*`
- `--ts-grid-gap`

### Rhythm scope

Documentation must define usage rules for:

- PDP spacing
- archive spacing
- section spacing
- card spacing
- mobile spacing

Principles:

- Premium rhythm comes from vertical consistency, not larger numbers alone.
- Spacing should create calm hierarchy and scanning clarity.
- Mobile rhythm should remain breathable without turning into oversized editorial gaps.
- Adjacent sections should feel intentional, never plugin-stacked.

## Radius System

This phase formalizes the usage contract of the existing radius tokens:

- `--ts-radius-sm`
- `--ts-radius-md`
- `--ts-radius-lg`
- `--ts-radius-xl`

Documentation must bind these to commerce surfaces:

- button radius
- card radius
- gallery radius
- swatch radius
- input radius

Principles:

- Radius must signal system discipline.
- The same component class should not drift between multiple corner treatments.
- Softness should feel premium, not playful.

Unsupported:

- ad hoc radius values per component
- oversized pill-like retail controls unless explicitly justified
- mixed radius styles within the same component family

## Shadow Rules

The shadow system remains intentionally restrained.

Existing tokens remain the foundation:

- `--ts-shadow-xs`
- `--ts-shadow-sm`
- `--ts-shadow-md`
- `--ts-shadow-lg`

Documentation must establish:

- extremely light default elevation
- airiness over weight
- shadow as support, not spectacle

Unsupported:

- Bootstrap-style heavy shadows
- cheap glow effects
- purple hover glow
- dense floating card stacks
- shadow-driven hierarchy when border and spacing should do the work

## Image Ratio System

This phase formalizes image ratios as a commerce system rule set.

Supported ratio families:

- portrait
- square
- landscape

Default direction:

- premium DTC portrait ratio is the default catalog posture

Documentation must cover:

- archive cards
- gallery thumbs
- swatches
- recommendation cards

Principles:

- Product image containers should feel consistent across adjacent surfaces.
- Ratio shifts should be intentional and content-led.
- Premium catalog browsing should favor calm, repeatable media framing.

## Typography Hierarchy Refinement

This phase refines hierarchy rules more than it rewrites the type scale.

Existing type tokens remain the base, including:

- `--ts-text-h1` through `--ts-text-h6`
- `--ts-text-product-title`
- `--ts-text-product-price`
- `--ts-text-product-meta`
- `--ts-leading-*`
- `--ts-tracking-*`

Documentation must define rules for:

- PDP title
- archive title
- price hierarchy
- meta hierarchy
- section heading rhythm

Principles:

- Editorial commerce hierarchy over generic theme typography
- Strong product naming without oversized hero behavior
- Price emphasis through proportion and contrast, not gimmicks
- Meta information stays quiet and supportive

## Commerce Visual Language Document

`docs/design/commerce-visual-language.md` acts as the system-level reference.

It must cover:

- design intent
- visual principles
- system relationships
- unsupported styles
- review criteria for future commerce UI work

The document should function as both:

- a high-level premium commerce design manifesto
- a practical rules handbook for implementation decisions

The second role is primary.

## Unsupported Styles

The documentation set must explicitly prohibit:

- Dribbble-style showpiece UI
- marketplace-dense layouts
- heavy glassmorphism
- complex 3D motion
- cheap hover enlargement
- thick floating shadows
- glow-based hover styling
- random radius drift
- uncontrolled image ratio drift
- badge and chip overload
- promotion blocks that overpower catalog rhythm
- motion used to hide weak hierarchy

## Minimal CSS Alignment Rules

This phase may modify existing CSS only when one of these is true:

1. A new token file must be imported into the existing CSS assembly chain.
2. Existing CSS depends on incomplete motion token coverage and needs a compatibility bridge.
3. A base rule must be adjusted so reduced-motion support is complete and consistent.

This phase should not:

- restyle all components
- expand runtime behavior
- re-architect base commerce CSS modules

## Documentation Standards

The new design documentation set must define:

- visual rules
- interaction rhythm
- unsupported styles
- default posture versus exceptions
- where future component work should conform rather than invent

## Testing Strategy

Verification should cover:

- `motion.css` is loaded by the theme CSS pipeline
- existing motion consumers keep working
- reduced motion rules are present and coherent
- documentation accurately reflects the implemented token surface
- no unrelated component regressions are introduced by the token-layer change

## Phase Deliverable

This phase delivers a premium commerce design system consolidation layer:

- formal motion token system
- documented commerce visual language
- documented spacing rhythm
- documented image ratio system
- clarified radius, shadow, and typography usage rules
- minimal compatibility alignment for existing CSS

It does not deliver new commerce features, new runtime subsystems, or marketplace expansion.
