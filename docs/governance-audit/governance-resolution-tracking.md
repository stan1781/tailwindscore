# Governance Resolution Tracking

Governance tracking is not only a list of findings. It is the lifecycle record for how a surface moves from leakage to stable enforcement.

## Required Tracking Fields

Every tracked finding should record:

- status
- surface
- severity
- governance owner
- rationale
- follow-up stage

## Allowed Statuses

### `resolved`

Use when:

- the surface now consumes registry-backed content
- mood fallback is active where required
- SSR output and runtime behavior are aligned
- trust language no longer leaks through template-local copy

### `pending`

Use when:

- the issue is accepted as active work
- the finding is in scope for the next enforcement pass
- there is no architectural reason to defer it

### `deferred`

Use when:

- the issue is known
- the issue is outside the current stabilization scope
- delaying it does not create trust-critical leakage

Deferred findings must always point to a later governance stage.

### `accepted-exception`

Use when:

- the string is structural rather than tonal
- the string does not create reassurance drift
- moving it now would expand scope without improving trust governance

Accepted exceptions must be reviewed again if the surface family later enters normalization work.

## Governance Owner Rules

Owners should be named by surface family rather than by individual file:

- checkout surfaces
- account surfaces
- cart surfaces
- cart surface runtime
- discovery surfaces
- review surfaces
- archive discovery

This keeps tracking durable even when templates move.

## Lifecycle Rule

A governance phase is not complete when findings merely exist in a report. It is complete when each finding has a clear lifecycle state and follow-up path.
