# Governance Debt Tracking

Governance debt is tracked when a finding is known, bounded, and intentionally left outside the current enforcement pass.

## Debt Classes

### Tone leakage debt

Use when tone-bearing copy still sits outside the registry path.

Current items:

- `template-parts/cart-surface/cart-summary.php`
- `template-parts/checkout/checkout-payment.php`

Why debt exists:

- the main trust-critical path is already enforced
- the remaining lines are secondary helper copy, not active critical leakage

Removal strategy:

- move the remaining helper strings into existing cart and checkout surface families

### Localization debt

Use when customer-facing copy still relies on inline output and has only partial translation safety.

Current items:

- `woocommerce/single-product-reviews.php`

Why debt exists:

- review access messaging is known but outside the current closure scope

Removal strategy:

- add review-surface registry support before the review governance phase closes

### Runtime copy debt

Use when a runtime-connected surface still depends on local copy for part of its behavior.

Current items:

- `template-parts/cart-surface/cart-summary.php`

Why debt exists:

- cart drawer trust language is now governed, but summary note and actions are still local

Removal strategy:

- extend the current cart surface family without creating a new runtime branch

### Duplicate fallback debt

Use when acceptable wording exists in more than one place and has not yet been consolidated.

Current items:

- `template-parts/account/order-detail.php`

Why debt exists:

- fallback wording is stable enough for now, but it is not centrally governed

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
