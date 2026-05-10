# Motion Audit

## Goal

Unify commerce interaction timing around semantic motion tokens and remove ornamental hover behavior.

## Decisions

- Buttons, tabs, inputs, archive actions, and gallery controls now use semantic motion tokens instead of primitive duration/easing references.
- Archive action reveal now uses opacity-only disclosure on fine pointers.
- Button and purchase CTA hover no longer translate vertically.
- Gallery controls and thumbs keep quiet state confirmation through border, opacity, and light shadow only.
- Reduced-motion support remains explicit on gallery, swatches, variation states, tabs, buttons, and sticky purchase regions.

## Replaced Patterns

- Primitive `--ts-duration-*` / `--ts-ease-*` usage inside interactive component CSS replaced with `--ts-motion-*` tokens where relevant.
- Product-card action reveal translation removed.
- Primary CTA lift behavior replaced by restrained shadow response.

## Deprecated Styles

- Hover lift as a default commerce affordance
- Strong motion on archive card actions
- Gallery controls that read as floating pills

## Unsupported Interactions

- Large hover scale
- Bounce / overshoot easing
- Transition-driven spectacle on sticky commerce surfaces
