# Theme Configuration Rules

## Governance Boundary Table

| Layer | Allowed Scope | Prohibited Scope | Canonical Owner |
| --- | --- | --- | --- |
| Design Tokens | colors, typography, spacing, radius, motion, density, shell spacing | component-specific styling panels | `docs/presets/preset-boundaries.md` |
| Commerce Behaviors | bounded enum/boolean behavior defaults | arbitrary layout composition, component manipulation | `docs/architecture/runtime-boundaries.md` |
| Content Surfaces | registry-backed messaging surfaces | scattered text settings, plugin-style pages | `docs/content-surfaces/content-surface-rules.md` |

## Admission Checklist

| Question | Required Answer |
| --- | --- |
| Which layer owns the setting? | token, bounded behavior, or content surface |
| Is there an existing key? | reuse before adding |
| Does it create duplicate meaning? | if yes, reject |
| Does SSR remain complete without it? | must be yes |
| Can it be documented without new authority drift? | must be yes |

## Lifecycle Table

| Concern | Status | End State |
| --- | --- | --- |
| local configuration rules | frozen secondary reference | keep compact only |
| repeated preset explanation | reduced | refer to preset boundaries |
| repeated content explanation | reduced | refer to content surface rules |
