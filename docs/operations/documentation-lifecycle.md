# Documentation Lifecycle

## States

- Draft: short-lived working note
- Active: canonical and current
- Frozen Reference: human-readable lookup material outside default AI context
- Archived: historical and read-by-exception

## Promotion Rules

- draft to active: owner, destination, and lifecycle are explicit
- active to frozen reference: still useful, but no longer part of default implementation context
- active to archive: completed, superseded, or phase-closed

## Archive Procedure

1. Copy the historical file into the appropriate `docs/archive/` bucket.
2. Remove the active copy from the active tree.
3. Update the top-level map and health file if the IA changed.
4. Record the archive reason in the change summary.

## Buckets

- `docs/archive/specs/`
- `docs/archive/plans/`
- `docs/archive/audits/`
- `docs/archive/legacy/`
