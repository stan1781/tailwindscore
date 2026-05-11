# Governance Completion Rules

## Completion Criteria Matrix

| Check | Complete When | Incomplete When |
| --- | --- | --- |
| trust leakage | no critical leakage remains | critical trust copy remains hardcoded |
| SSR/runtime alignment | runtime inherits governed values | runtime bypasses SSR governance |
| registry-first copy | reassurance/support copy is registry-backed | template-local copy replaces governed path |
| mood-safe fallback | tonal fallback is active where needed | preset or mood semantics are replaced locally |
| localization safety | localization-safe output is maintained | wording depends on ad hoc local phrasing |

## Lifecycle Table

| Lifecycle State | Meaning |
| --- | --- |
| in progress | one or more completion checks still fail |
| operationally complete | all checks pass for the current phase |
| deferred | known issue remains outside the current reduction scope |
| archived | historical completion record only |

## Governance Status Map

| Status | Trigger |
| --- | --- |
| complete | all completion criteria pass |
| mixed | partial governance remains |
| deferred | issue is tracked but out of current scope |
| non-complete | trust, fallback, or localization path still bypasses governance |

## Escalation Table

| Condition | Escalation Target |
| --- | --- |
| trust-critical leakage remains | canonical workflow and active phase |
| runtime bypass remains | runtime boundaries plus canonical workflow |
| localization path is ad hoc | localization clarification |
| duplicate fallback remains | debt tracking or next consolidation pass |
