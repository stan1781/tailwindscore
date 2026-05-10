# Governance Enforcement Report

Date: 2026-05-10
Stage: Governance Closure Phase

## Scope Scanned

- `woocommerce/myaccount`
- `template-parts/search`
- `template-parts/checkout`
- `template-parts/cart-surface`
- `template-parts/feedback`
- `template-parts/account`
- review templates
- archive templates

## Status Model

- `resolved`: enforcement work completed and now routed through the governed path
- `pending`: still within active governance scope and should be fixed in a scheduled enforcement pass
- `deferred`: known governance debt outside the current stabilization scope
- `accepted-exception`: intentionally left inline because the string is structural, low-risk, and not trust-tonal

## Severity Model

### Critical

- hardcoded reassurance copy
- preset-specific inline messaging
- unsupported tone phrasing in customer-facing helper text

### Warning

- duplicated fallback language
- inconsistent helper tone
- support/discovery messaging not yet routed through registry

### Notice

- minor tone drift
- structural labels still inline but not yet normalized
- wording rhythm that is acceptable short-term but should converge later

## Resolution Tracking

| Status | Surface | Severity | Governance Owner | Rationale | Follow-up Stage |
|---|---|---|---|---|---|
| `resolved` | `woocommerce/myaccount/form-lost-password.php` | Critical | Account surfaces | Recovery reassurance and caption now consume registry-first account recovery surfaces with mood-safe fallback. | Closed in Governance Closure |
| `resolved` | `woocommerce/myaccount/form-reset-password.php` | Critical | Account surfaces | Reset-password reassurance and support guidance now flow through governed recovery surfaces instead of template-local copy. | Closed in Governance Closure |
| `resolved` | `template-parts/cart-surface/cart-drawer.php` | Critical | Cart surface runtime | Drawer loading and validation-adjacent trust copy now comes from governed surfaces and is bridged into runtime through SSR-safe data attributes. | Closed in Governance Closure |
| `pending` | `template-parts/cart-surface/cart-summary.php` | Warning | Cart surfaces | Summary note and action wording still duplicate commerce helper language outside the registry path. | Secondary Surface Planning |
| `pending` | `template-parts/checkout/checkout-payment.php` | Warning | Checkout surfaces | Secondary checkout guidance remains local even though the main reassurance path is governed. | Secondary Surface Planning |
| `deferred` | `template-parts/account/order-detail.php` | Warning | Account surfaces | Fallback wording is still mixed, but the current tone is stable and does not create trust-critical leakage. | Account consolidation follow-up |
| `deferred` | `woocommerce/single-product-reviews.php` | Warning | Review surfaces | Review access empty-state copy is known inline debt, but it is outside the current trust-critical stabilization scope. | Review governance phase |
| `deferred` | `template-parts/search/default-state.php` | Notice | Discovery surfaces | Discovery headings are IA labels rather than tonal reassurance, so they are intentionally left for later normalization. | Search IA follow-up |
| `deferred` | `template-parts/search/predictive-results.php` | Notice | Discovery surfaces | Predictive section labels remain local, but they do not bypass trust language governance. | Search IA follow-up |
| `deferred` | `template-parts/woocommerce/archive-discovery.php` | Notice | Archive discovery | Archive helper labels are structural and low-risk; no new archive governance work is opened in this phase. | Archive helper follow-up |
| `accepted-exception` | Cart drawer structural labels such as `Bag`, `Cart`, and close control labels | Notice | Cart surfaces | These strings are navigational UI labels, not reassurance or support language, so they can remain inline until label normalization is planned. | Revisit only if label governance expands |
| `accepted-exception` | Account recovery form field labels and submit controls | Notice | Account surfaces | Input labels and action verbs are structural form language and do not represent tonal helper messaging. | Revisit only if form-label governance expands |

## Closure Read

Current platform state:

- checkout: trust-critical path enforced, with secondary helper debt still pending
- account: trust-critical recovery leak closed
- search: governed discovery guidance active, with IA labels deferred
- cart surface: critical drawer trust leak closed, summary labels still pending
- reviews: deferred outside current closure scope
- archive discovery: deferred outside current closure scope

## Secondary Surface Planning

These are the next known surfaces, but they are not part of the current implementation scope:

1. cart summary labels
2. review access language
3. archive helper copy
4. discovery guidance labels
5. search IA labels
