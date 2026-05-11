# Runtime Boundaries

This file records the stable runtime boundary for documentation work.

## Stable Rules

- documentation consolidation does not change WooCommerce runtime behavior
- documentation consolidation does not add runtime families
- documentation consolidation does not expand scanner capability unless required for a documentation blocker
- documentation consolidation does not refactor preset architecture

## Ownership Boundary

- runtime behavior remains owned by existing architecture and implementation files
- documentation work may describe runtime boundaries but must not widen them

## Escalation Rule

Only blocker-class fixes may cross this boundary:

- governance critical fix
- accessibility fix
- localization fix
- documentation blocker fix
