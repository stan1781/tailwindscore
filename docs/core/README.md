# Documentation System

TailwindScore documentation now operates as a governed system instead of an open-ended note pile.

## Active Top-Level Areas

- `docs/core/`: documentation constitution, ownership, and system map
- `docs/phases/`: the single active implementation phase
- `docs/governance/`: workflow, rules, and health metrics
- `docs/architecture/`: stable architecture boundaries
- `docs/ai/`: AI-only operating rules
- `docs/operations/`: lifecycle and archive procedures
- `docs/reference/`: human reference indexes and controlled lookup material
- `docs/archive/`: historical material excluded from default AI context

## Ownership Model

- `core`: documentation governance owner
- `phases`: current phase owner
- `governance`: workflow and policy owner
- `architecture`: runtime boundary owner
- `ai`: AI workflow owner
- `operations`: documentation operations owner
- `reference`: domain reference owners
- `archive`: documentation governance owner

## Lifecycle Classes

- Constitution docs: long-lived, low-churn, active
- Workflow docs: process guidance, active
- Reference docs: lookup-oriented, active but not default AI context unless named
- Archive docs: historical, frozen, excluded from default AI context

## Canonical Map

- canonical source registry: `docs/core/canonical-sources.md`
- active AI stack: `docs/AI-ENTRY.md`
- legacy freeze boundary: `docs/reference/README.md`

## Transition Rule

Legacy peer directories under `docs/` remain available as human reference during consolidation, but they are frozen as default AI input until they are explicitly re-homed under the governed structure.
