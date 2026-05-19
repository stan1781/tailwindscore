# Governance Debt Tracking

Governance debt is tracked when a finding is known, bounded, and intentionally left outside the current enforcement pass.

With the baseline system in place, debt should live in `governance-baseline.json` as well as in narrative docs.

## Debt Classes

### Tone leakage debt

Use when tone-bearing copy still sits outside the registry path.

Current items:

- none in the current checkout/cart pass

Removal strategy:

- move the remaining helper strings into existing cart and checkout surface families

### Localization debt

Use when customer-facing copy still relies on inline output and has only partial translation safety.

Current items:

- none in the current reviews pass

Why debt exists:

- review access and form messaging now resolve through the registry path; revisit only if a new review helper surface reintroduces inline governed copy

Removal strategy:

- add review-surface registry support before the review governance phase closes

### Runtime copy debt

Use when a runtime-connected surface still depends on local copy for part of its behavior.

Current items:

- none in the current checkout/cart pass

Why debt exists:

- cart drawer trust language is now governed; revisit this class only when another runtime-connected local helper surface appears

Removal strategy:

- extend the current cart surface family without creating a new runtime branch

### Duplicate fallback debt

Use when acceptable wording exists in more than one place and has not yet been consolidated.

Current items:

- none in the current account order-detail pass

Why debt exists:

- fallback wording for this surface is now centrally governed; revisit this class only when another account helper fallback remains outside the registry path

Removal strategy:

- converge order-detail helper copy into the account surface registry during the next account consolidation pass

### Temporary exception debt

Use when low-risk structural labels remain inline by decision rather than omission.

Current items:

- cart drawer structural labels
- account recovery form labels and submit controls
- search IA labels
- archive helper labels

Why debt exists:

- these strings are structural UI language, not trust-tonal messaging
- normalizing them now would widen scope beyond closure work

Removal strategy:

- revisit only if label normalization becomes an explicit governance phase

## Debt Rule

Debt is allowed only when:

- the reason is documented
- the deferral does not leave a critical trust leak open
- a removal strategy exists
- the next review point is named

## Visibility Rule

Debt must be distinguishable from active delta findings.

Use these working meanings:

- `active issue`: not present in baseline, must surface in delta output
- `accepted debt`: present in baseline with `accepted` status
- `deferred debt`: present in baseline with `deferred` status
- `resolved issue`: baseline candidate no longer appearing in scan output and ready for removal
