# Sustainable Governance Baseline System

Date: 2026-05-10
Stage: Sustainable Governance Baseline System

## Intent

The baseline system exists so governance findings become durable, reviewable debt instead of endless repeated scan noise.

This phase does not add new commerce behavior, new scanner domains, or a new design layer.

It does add:

- a committed governance baseline file
- delta-first scan reporting
- documented exception handling
- debt visibility rules
- future CI direction for regression-only enforcement

## Baseline File

The baseline lives in:

- `governance-baseline.json`

Each entry must contain:

- `id`
- `surface`
- `severity`
- `file`
- `rationale`
- `status`
- `owner`
- `plannedResolutionStage`

Entries may also include:

- `matchValues`
- `allowanceType`

## Baseline Status Rules

### `accepted`

Use when:

- the finding is known
- the current phase intentionally tolerates it
- the surface is still reviewable and removable later

### `deferred`

Use when:

- the finding is known debt
- a later governance phase is already named
- the issue should stay out of delta noise until that phase begins

## Delta Scan Strategy

The scanner now treats baseline-matched findings as historical debt.

Delta output should prioritize:

- new governance leak
- severity escalation
- new trust-critical issue
- new runtime inline leakage

It should not keep re-reporting baseline debt as if it were a new regression.

## Accepted Exception Governance

Accepted exceptions are allowed only when they are:

- documented
- reviewable
- removable

Supported exception classes include:

- WooCommerce compatibility limitation
- third-party extension limitation
- temporary migration surface
- phased governance adoption

Silent bypass is not allowed.

If a surface cannot be governed yet, it must still appear in the baseline with an owner and a removal stage.

## Governance Debt Visibility

Governance status must stay legible across reports and docs.

Use these visibility buckets:

- `active issue`: current delta finding not covered by the baseline
- `accepted debt`: baseline entry intentionally tolerated in the current phase
- `deferred debt`: baseline entry scheduled for a later governance phase
- `resolved issue`: former debt that no longer appears in scan output and can be removed from the baseline

This prevents baseline debt from being confused with fresh regressions.

## Future CI Direction

Not implemented in this phase.

Future CI should block only:

- new critical leak
- new trust-critical inline copy
- governance regression
- severity escalation over baseline

Future CI should not block:

- historical accepted debt
- deferred baseline debt that is already documented

## Operational Rule

When a baseline entry disappears from scan output, it should be reviewed and removed rather than left behind indefinitely.

The baseline is a living debt register, not an archive dump.
