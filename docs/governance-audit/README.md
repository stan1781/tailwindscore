# Governance Audit Reference

## Directory Status

| Field | Value |
| --- | --- |
| authority status | non-canonical |
| lifecycle | operational / historical reference |
| AI default | excluded |
| migration targets | `docs/governance/`, `docs/reference/`, `docs/archive/` |

## Canonical Redirect

| Concern | Canonical Owner | Governance Audit Role |
| --- | --- | --- |
| active governance workflow | `docs/governance/workflow.md` | no authority |
| documentation rules | `docs/governance/documentation-rules.md` | no authority |
| documentation health | `docs/governance/documentation-health.md` | no authority |
| AI entry boundary | `docs/AI-ENTRY.md` | no authority |
| historical evidence | `docs/archive/` | archive target |

## Reduction Map

| Legacy Cluster | Files | End State |
| --- | --- | --- |
| audit strategy | `audit-strategy.md`, `coverage-matrix.md` | optional reference |
| historical enforcement | `enforcement-report.md` | archived |
| governance debt | `governance-debt-tracking.md`, `governance-resolution-tracking.md`, `governance-completion-rules.md` | optional reference |
| detection and automation | `automation-foundation.md`, `hardcoded-string-detection.md`, `fallback-duplication-rules.md` | secondary reference |
| trust and language policy | `severity-levels.md`, `language-philosophy-enforcement.md`, `tone-leakage-prevention.md`, `trust-critical-surfaces.md` | secondary reference |
| workflow overlap | `development-workflow.md` | operational-only redirect |
| reporting | `baseline-system.md`, `governance-dashboard.md` | operational-only |

## File Classification

| File | Classification | Lifecycle Status |
| --- | --- | --- |
| `development-workflow.md` | operational-only | retained as redirect/example index |
| `governance-completion-rules.md` | optional reference | retained as compact status map |
| `governance-dashboard.md` | operational-only | retained as reporting contract |
| `baseline-system.md` | operational-only | retained as reporting support |
| `audit-strategy.md`, `coverage-matrix.md` | optional reference | retained until archive split phase |
| `enforcement-report.md` | archive-only | removed from active directory |
| remaining files | optional reference | secondary audit lookup only |

## Canonical Redirect

- workflow authority: `docs/governance/workflow.md`
- lifecycle and gate authority: `docs/governance/documentation-rules.md`
- system health authority: `docs/governance/documentation-health.md`
- canonical ownership map: `docs/core/canonical-sources.md`

## AI Warning

Do not load `docs/governance-audit/` in the default AI context.

Read this directory only when the canonical source map points to a named file for historical or secondary reference.
