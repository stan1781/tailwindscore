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

- none after Phase 1 closure

Why debt exists:

- resolved review helper debt was removed from the baseline once scanner output and regression coverage confirmed the governed path

Removal strategy:

- re-open this class only if a new review or helper surface reintroduces customer-facing inline governed copy

### Runtime copy debt

Use when a runtime-connected surface still depends on local copy for part of its behavior.

Current items:

- checkout loading and validation runtime bridges
- checkout field-level validation runtime messages
- add-to-cart runtime error fallback
- add-to-cart variation-selection message

Why debt exists:

- these messages still live in runtime code paths where the SSR bridge is incomplete or intentionally local for now
- they remain baseline-tracked so a later runtime cleanup can remove them deliberately instead of rediscovering them as scan noise

Removal strategy:

- finish the runtime fallback-removal work in checkout and cart without creating another copy surface

### Duplicate fallback debt

Use when acceptable wording exists in more than one place and has not yet been consolidated.

Current items:

- account auth registration support copy
- account address guidance fallback around the governed surface

Why debt exists:

- most stale helper and review baseline entries were removed once the scanner stopped emitting those findings and the regression test locked that behavior in
- the remaining account items are still live helper/fallback debt in the current baseline and need a later consolidation pass

Removal strategy:

- remove the remaining account helper/fallback entries during account auth and address-surface consolidation

### Temporary exception debt

Use when low-risk structural labels remain inline by decision rather than omission.

Current items:

- checkout shell labels
- account recovery, password, auth, and address structural labels
- search retry and trigger labels

Why debt exists:

- these strings are structural UI language, not trust-tonal messaging
- normalizing them now would widen scope beyond closure work
- Phase 1 intentionally closed trust and helper-copy leaks first, leaving only low-risk label exceptions in this class

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

## Phase 1 Result

Phase 1 closed the fresh governance delta across search, account, cart, and checkout.

The baseline now keeps live runtime debt, a narrow remaining account helper/fallback remainder, and structural-label exceptions that still matter to the scanner. Stale helper and review baseline entries were removed once scan output, template tests, and scanner regression coverage all agreed they were resolved.
