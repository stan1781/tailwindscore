# AI System Constitution

## Purpose

Keep AI work inside current governance boundaries and prevent context drift.

## Non-Negotiable Rules

- phase files define the only active implementation context
- archive is excluded from default reads
- historical plans and specs are not implementation authority
- reference files are consulted only when the active context names them
- runtime architecture is read from stable boundary docs, not historical design notes

## Default Working Set

- `docs/AI-ENTRY.md`
- `docs/phases/current-phase.md`
- `docs/governance/workflow.md`
- `docs/architecture/runtime-boundaries.md`
- `docs/governance/documentation-rules.md`

## Reading Discipline

- do not sweep `docs/` recursively by default
- do not load archive material without cause
- do not treat frozen legacy directories as active governance
- keep the default AI stack at six files unless the current task explicitly needs more
- log any escalation beyond the default working set in the change summary
