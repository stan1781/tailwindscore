# Content Moods Reference Freeze

## Directory Status

| Field | Value |
| --- | --- |
| authority status | frozen legacy |
| lifecycle | reference-only |
| AI default | excluded |
| migration target | `docs/reference/` |

## Overlap Reduction Map

| Legacy File | Primary Concern | Canonical Replacement | End State |
| --- | --- | --- | --- |
| `mood-rules.md` | tone governance | `docs/content-surfaces/content-surface-rules.md` | reduce to reference |
| `tone-governance.md` | tone policy | `docs/content-surfaces/content-surface-rules.md` | reduce to reference |
| `supported-surfaces.md` | surface compatibility | `docs/content-surfaces/content-surface-rules.md` | reduce to reference |
| `preset-mood-mapping.md` | preset overlap | `docs/presets/preset-boundaries.md` | reduce to reference |
| `localization-rules.md` | localization governance | `docs/architecture/runtime-boundaries.md` plus existing localization references | keep secondary |
| `localization-tone-strategy.md` | localization guidance | optional only | keep secondary |
| `multilingual-governance.md` | governance narrative | `docs/governance/workflow.md` | freeze only |

## Ownership Matrix

| Concern | Canonical Owner | Content Moods Role |
| --- | --- | --- |
| content governance | `docs/content-surfaces/content-surface-rules.md` | supporting reference only |
| preset compatibility | `docs/presets/preset-boundaries.md` | supporting reference only |
| AI workflow boundary | `docs/AI-ENTRY.md` | excluded by default |
| runtime boundary | `docs/architecture/runtime-boundaries.md` | unchanged |

## Reference Index

- runtime registry: `inc/content-moods/registry.php`
- tone references: `mood-rules.md`, `tone-governance.md`
- overlap references: `supported-surfaces.md`, `preset-mood-mapping.md`
- localization references: `localization-rules.md`, `localization-tone-strategy.md`, `multilingual-governance.md`

## AI Warning

Do not load `docs/content-moods/` in the default AI context.

Use this directory only for on-demand reference after the canonical source map points here.
