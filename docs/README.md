# TailwindScore Documentation

TailwindScore documentation is now governed by active-context boundaries instead of open-ended topic sprawl.

## Start Here

- `docs/AI-ENTRY.md`
- `docs/phases/current-phase.md`
- `docs/ai/system-constitution.md`
- `docs/governance/workflow.md`
- `docs/architecture/runtime-boundaries.md`
- `docs/governance/documentation-rules.md`

## Governed Top-Level Structure

- `docs/core/`: documentation system map and ownership
- `docs/phases/`: the only active implementation phase
- `docs/governance/`: documentation rules, workflow, and health
- `docs/architecture/`: stable architecture boundaries
- `docs/ai/`: AI-only operating rules
- `docs/operations/`: lifecycle and archive procedure
- `docs/reference/`: human-first lookup boundary
- `docs/archive/`: historical docs excluded from default AI context

## Default AI Boundary

AI should not recursively read `docs/`.

AI should enter through `docs/AI-ENTRY.md`, then stay inside the six-file default context stack unless a named escalation is required.

## Transitional Legacy Boundary

Existing topical directories under `docs/` remain available as frozen human reference during consolidation.

They are not active implementation authority unless a current-phase file explicitly names them.
