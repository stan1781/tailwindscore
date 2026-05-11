# Canonical Sources

This file defines the single canonical source for each governed documentation domain.

## Canonical Source Map

| Knowledge Domain | Canonical Source | Authority Status | AI Context Status | Backup / On-Demand References | Notes |
| --- | --- | --- | --- | --- | --- |
| AI entry | `docs/AI-ENTRY.md` | canonical | default | `docs/README.md` | Only default AI workflow entry |
| Current phase | `docs/phases/current-phase.md` | canonical | default | `docs/phases/active-goals.md`, `docs/phases/active-boundaries.md` | Only active implementation context |
| Runtime boundaries | `docs/architecture/runtime-boundaries.md` | canonical | default | `docs/architecture/system-architecture.md` | Stable runtime ownership boundary |
| Governance workflow | `docs/governance/workflow.md` | canonical | default | `docs/governance-audit/development-workflow.md` | Workflow owner lives in `docs/governance/` |
| AI constitution | `docs/ai/system-constitution.md` | canonical | default | `docs/ai/cursor-rules.md` | `cursor-rules.md` is secondary only |
| Documentation rules | `docs/governance/documentation-rules.md` | canonical | default | `docs/core/README.md` | Rules owner for md creation, archive, readability |
| Active goals | `docs/phases/active-goals.md` | secondary reference | optional | none | Not part of default AI stack |
| Active boundaries | `docs/phases/active-boundaries.md` | secondary reference | optional | none | Used when scope needs expansion checks |
| Active debt and lifecycle status | `docs/phases/completion-status.md` | canonical | optional | `docs/governance/documentation-health.md` | Phase debt is current, health is system-wide |
| Documentation health metrics | `docs/governance/documentation-health.md` | canonical | optional | none | System-level stability metrics |
| Documentation lifecycle | `docs/operations/documentation-lifecycle.md` | canonical | optional | `docs/archive/README.md` | Lifecycle state transitions and archive procedure |
| Preset governance | `docs/presets/preset-boundaries.md` | canonical | optional | `docs/presets/preset-rules.md`, `docs/presets/preset-personality-system.md` | Boundary doc is canonical, others are reference |
| Content governance | `docs/content-surfaces/content-surface-rules.md` | canonical | optional | `docs/content-moods/mood-rules.md`, `docs/content-moods/localization-rules.md` | Surface rule is canonical, mood docs are reference |
| Governance prompt contract | `docs/ai/governance-prompt-rules.md` | canonical | optional | `docs/ai/prompting-guide.md` | Prompting guide is example-only |

## Canonicalization Rules

- one domain, one canonical source
- backup references may explain or exemplify, but may not redefine ownership
- if two docs can answer the same ownership question, one must be frozen, archived, or reduced to reference
- active AI context may include only canonical sources unless the current phase explicitly escalates

## Legacy Replacement Map

| Legacy Area | Authority Status | Frozen Status | Migration Target | Canonical Replacement | AI Default |
| --- | --- | --- | --- | --- | --- |
| `docs/configuration/` | frozen legacy | frozen | `docs/reference/` | `docs/presets/preset-boundaries.md`, `docs/content-surfaces/content-surface-rules.md` | excluded |
| `docs/content-moods/` | frozen legacy | frozen | `docs/reference/` | `docs/content-surfaces/content-surface-rules.md` for content governance | excluded |
| `docs/content-surfaces/` | mixed | partially frozen | `docs/reference/` | `docs/content-surfaces/content-surface-rules.md` | excluded except named canonical file |
| `docs/governance-audit/` | frozen legacy | frozen | `docs/reference/` and `docs/archive/` | `docs/governance/workflow.md`, `docs/governance/documentation-rules.md`, `docs/governance/documentation-health.md` | excluded |

## Status Legend

| Status | Meaning |
| --- | --- |
| canonical | the only authority for that domain |
| secondary reference | may support lookups but may not redefine authority |
| operational-only | implementation or transport reference with no governance authority |
| frozen legacy | retained for migration/reference only |
| archived | historical only, no authority |
| archive-only | read only from `docs/archive/` |
| default | part of the six-file default AI context |
| optional | read on demand only |

## Operational / Archive Index

| File | Status | Notes |
| --- | --- | --- |
| `docs/configuration/kirki-architecture.md` | operational-only | transport reference only |
| `docs/configuration/transport-rules.md` | operational-only | optional transport reference |
| `docs/content-moods/localization-tone-strategy.md` | optional reference | compact localization compatibility table |
| `docs/governance-audit/development-workflow.md` | operational-only | redirect/example only, non-canonical |
| `docs/governance-audit/governance-completion-rules.md` | optional reference | compact status map only |
| `docs/governance-audit/governance-dashboard.md` | operational-only | reporting contract only |
| `docs/governance-audit/README.md` | operational-only | directory classifier only |
| `docs/archive/` | archive-only | historical only, never canonical |
