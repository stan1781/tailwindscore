# Template Governance Closure Phase 1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Close the remaining governance delta across search, account, cart, and checkout templates so the theme has a stable governed baseline before any layout or visual template refactor begins.

**Architecture:** Keep the existing registry-first model. Route remaining inline and trust-critical copy through existing surface helpers and content-surface registry entries, fix the one remaining runtime string in the add-to-cart module, then reconcile scanner/baseline output so governance scan reflects actual debt instead of stale or structural noise.

**Tech Stack:** PHP, WordPress, WooCommerce templates, TypeScript, Node.js governance scanner, Markdown governance docs

---

## File Map

- `template-parts/search/default-state.php`: search default-state discovery copy
- `template-parts/search/search-overlay.php`: search overlay placeholder and retry copy
- `inc/woocommerce/search.php`: governed search helper surface copy
- `woocommerce/myaccount/form-edit-account.php`: governed edit-account intro copy
- `woocommerce/myaccount/form-login.php`: governed login checkbox label and supporting copy
- `woocommerce/myaccount/form-reset-password.php`: governed reset-password field labels and submit control
- `woocommerce/myaccount/view-order.php`: governed back-navigation label
- `template-parts/account/address-card.php`: governed empty-address fallback
- `template-parts/account/order-card.php`: governed account order meta format string
- `inc/woocommerce/account.php`: governed account helper arrays and accessors
- `template-parts/cart-surface/cart-line-item.php`: governed subtotal label
- `template-parts/checkout/checkout-layout.php`: governed checkout page title
- `template-parts/checkout/checkout-summary.php`: governed subtotal label
- `src/ts/modules/commerce/add-to-cart.ts`: governed runtime validation title
- `inc/woocommerce/cart.php`: governed cart helper arrays and runtime surface accessors
- `inc/woocommerce/checkout.php`: governed checkout helper arrays
- `inc/content-surfaces/registry.php`: new surface keys for all remaining strings
- `inc/content-moods/registry.php`: mood defaults for all new surface keys
- `governance-baseline.json`: remove or update stale baseline entries after scan output changes
- `docs/governance-audit/governance-debt-tracking.md`: reflect resolved debt and any remaining accepted exceptions
- `tests/content-surface-adapters-test.php`: add assertions for new helper keys
- `tests/surface-governance-helpers-test.php`: add required-key coverage for new surface families
- `tests/template-governance-literals-test.php`: lock template literal removal where appropriate
- `tests/governance-scan-regression-test.mjs`: preserve scanner behavior against governed fallback false positives

---

### Task 1: Close search surface delta

**Files:**
- Modify: `template-parts/search/default-state.php`
- Modify: `template-parts/search/search-overlay.php`
- Modify: `inc/woocommerce/search.php`
- Modify: `inc/content-surfaces/registry.php`
- Modify: `inc/content-moods/registry.php`
- Modify: `tests/content-surface-adapters-test.php`
- Modify: `tests/surface-governance-helpers-test.php`
- Modify: `tests/template-governance-literals-test.php`

- [ ] **Step 1: Write the failing test**

Add search expectations to the adapter and helper tests:

```php
$expected_search = array(
	'default_state_title' => '[surface:search-default-state-title|Begin with a piece, a material, or a collection]',
	'overlay_placeholder' => '[surface:search-overlay-placeholder|Search products, categories, stories]',
);
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/content-surface-adapters-test.php`
Expected: FAIL because the new search helper keys do not exist yet

- [ ] **Step 3: Write minimal implementation**

Route the remaining search copy through `tailwindscore_search_surface_copy()` and new registry keys:

```php
return array(
	'default_state_title' => tailwindscore_content_surface_text( 'search-default-state-title', __( 'Begin with a piece, a material, or a collection', 'tailwindscore' ) ),
	'overlay_placeholder' => tailwindscore_content_surface_text( 'search-overlay-placeholder', __( 'Search products, categories, stories', 'tailwindscore' ) ),
);
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/content-surface-adapters-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Verify scanner impact**

Run: `npm run governance:scan`
Expected: `template-parts/search/default-state.php` and `template-parts/search/search-overlay.php` disappear from delta findings

- [ ] **Step 6: Commit**

```bash
git add template-parts/search/default-state.php template-parts/search/search-overlay.php inc/woocommerce/search.php inc/content-surfaces/registry.php inc/content-moods/registry.php tests/content-surface-adapters-test.php tests/surface-governance-helpers-test.php tests/template-governance-literals-test.php
git commit -m "feat: close search governance delta"
```

### Task 2: Close account surface delta

**Files:**
- Modify: `woocommerce/myaccount/form-edit-account.php`
- Modify: `woocommerce/myaccount/form-login.php`
- Modify: `woocommerce/myaccount/form-reset-password.php`
- Modify: `woocommerce/myaccount/view-order.php`
- Modify: `template-parts/account/address-card.php`
- Modify: `template-parts/account/order-card.php`
- Modify: `inc/woocommerce/account.php`
- Modify: `inc/content-surfaces/registry.php`
- Modify: `inc/content-moods/registry.php`
- Modify: `tests/content-surface-adapters-test.php`
- Modify: `tests/surface-governance-helpers-test.php`
- Modify: `tests/template-governance-literals-test.php`

- [ ] **Step 1: Write the failing test**

Extend account helper expectations with the remaining unresolved keys:

```php
$expected_account = array(
	'edit_account_intro'        => '[surface:account-edit-account-intro|Keep your customer profile current without leaving the post-purchase flow.]',
	'login_remember_label'      => '[surface:account-login-remember-label|Keep me signed in]',
	'reset_new_password_label'  => '[surface:account-reset-new-password-label|New password]',
	'reset_confirm_label'       => '[surface:account-reset-confirm-password-label|Confirm password]',
	'reset_submit_label'        => '[surface:account-reset-submit-label|Save new password]',
	'view_order_back_label'     => '[surface:account-view-order-back-label|Back to orders]',
	'address_empty_message'     => '[surface:account-address-empty-message|No address saved yet.]',
	'order_card_meta_format'    => '[surface:account-order-card-meta-format|%1$s · %2$s · %3$s]',
);
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/surface-governance-helpers-test.php`
Expected: FAIL because one or more required account keys are missing

- [ ] **Step 3: Write minimal implementation**

Add the remaining account keys to the helper and reuse them in templates:

```php
return array(
	'edit_account_intro'       => tailwindscore_content_surface_text( 'account-edit-account-intro', __( 'Keep your customer profile current without leaving the post-purchase flow.', 'tailwindscore' ) ),
	'login_remember_label'     => tailwindscore_content_surface_text( 'account-login-remember-label', __( 'Keep me signed in', 'tailwindscore' ) ),
	'reset_new_password_label' => tailwindscore_content_surface_text( 'account-reset-new-password-label', __( 'New password', 'tailwindscore' ) ),
);
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/surface-governance-helpers-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Verify scanner impact**

Run: `npm run governance:scan`
Expected: account delta findings for edit-account, login, reset-password, view-order, address-card, and order-card are gone

- [ ] **Step 6: Commit**

```bash
git add woocommerce/myaccount/form-edit-account.php woocommerce/myaccount/form-login.php woocommerce/myaccount/form-reset-password.php woocommerce/myaccount/view-order.php template-parts/account/address-card.php template-parts/account/order-card.php inc/woocommerce/account.php inc/content-surfaces/registry.php inc/content-moods/registry.php tests/content-surface-adapters-test.php tests/surface-governance-helpers-test.php tests/template-governance-literals-test.php
git commit -m "feat: close account governance delta"
```

### Task 3: Close cart and checkout surface delta

**Files:**
- Modify: `template-parts/cart-surface/cart-line-item.php`
- Modify: `template-parts/checkout/checkout-layout.php`
- Modify: `template-parts/checkout/checkout-summary.php`
- Modify: `src/ts/modules/commerce/add-to-cart.ts`
- Modify: `inc/woocommerce/cart.php`
- Modify: `inc/woocommerce/checkout.php`
- Modify: `inc/content-surfaces/registry.php`
- Modify: `inc/content-moods/registry.php`
- Modify: `tests/content-surface-adapters-test.php`
- Modify: `tests/surface-governance-helpers-test.php`
- Modify: `tests/template-governance-literals-test.php`

- [ ] **Step 1: Write the failing test**

Add the remaining cart and checkout expectations:

```php
$expected_checkout = array(
	'layout_title'    => '[surface:checkout-layout-title|Checkout]',
	'summary_subtotal'=> '[surface:checkout-summary-subtotal-label|Subtotal]',
);

$expected_cart = array(
	'line_item_subtotal_label' => '[surface:cart-line-item-subtotal-label|Subtotal]',
	'validation_title'         => '[surface:add-to-cart-validation-title|Please review this selection]',
);
```

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/content-surface-adapters-test.php`
Expected: FAIL because these cart/checkout keys are not registered yet

- [ ] **Step 3: Write minimal implementation**

Promote the remaining strings into governed helper accessors and use a runtime bridge for the TS string:

```ts
const validationTitle =
	document.documentElement.dataset.feedbackAddToCartValidationTitle ??
	'Please review this selection';
```

```php
return array(
	'layout_title' => tailwindscore_content_surface_text( 'checkout-layout-title', __( 'Checkout', 'tailwindscore' ) ),
);
```

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/content-surface-adapters-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Verify scanner impact**

Run: `npm run governance:scan`
Expected: checkout title, checkout subtotal, cart subtotal, and add-to-cart validation title disappear from delta

- [ ] **Step 6: Commit**

```bash
git add template-parts/cart-surface/cart-line-item.php template-parts/checkout/checkout-layout.php template-parts/checkout/checkout-summary.php src/ts/modules/commerce/add-to-cart.ts inc/woocommerce/cart.php inc/woocommerce/checkout.php inc/content-surfaces/registry.php inc/content-moods/registry.php tests/content-surface-adapters-test.php tests/surface-governance-helpers-test.php tests/template-governance-literals-test.php
git commit -m "feat: close cart and checkout governance delta"
```

### Task 4: Reconcile scanner baseline and debt docs

**Files:**
- Modify: `governance-baseline.json`
- Modify: `docs/governance-audit/governance-debt-tracking.md`
- Modify: `tests/governance-scan-regression-test.mjs`

- [ ] **Step 1: Write the failing test**

Capture the expectation that resolved findings should not stay as stale baseline debt:

```js
const unexpected = report.unmatchedBaselineEntries.filter((entry) =>
	['inc/account/helpers.php', 'inc/cart-surface/helpers.php', 'inc/woocommerce/hooks/review-experience.php'].includes(entry.file),
);
```

- [ ] **Step 2: Run test to verify it fails**

Run: `node tests/governance-scan-regression-test.mjs`
Expected: FAIL once the unmatched-baseline assertion is added and stale entries still exist

- [ ] **Step 3: Write minimal implementation**

Remove or update stale baseline entries and align debt docs with the now-closed surface families:

```json
{
  "id": "remove-stale-review-helper-baseline",
  "status": "resolved"
}
```

- [ ] **Step 4: Run test to verify it passes**

Run: `node tests/governance-scan-regression-test.mjs`
Expected: PASS with `OK`

- [ ] **Step 5: Verify scanner impact**

Run: `npm run governance:scan -- --json`
Expected: `unmatchedBaselineEntries` shrinks and no resolved review/helper debt remains

- [ ] **Step 6: Commit**

```bash
git add governance-baseline.json docs/governance-audit/governance-debt-tracking.md tests/governance-scan-regression-test.mjs
git commit -m "chore: reconcile governance baseline after closure"
```

### Task 5: Final verification and closeout checkpoint

**Files:**
- Verify: `tests/content-surface-adapters-test.php`
- Verify: `tests/surface-governance-helpers-test.php`
- Verify: `tests/template-governance-literals-test.php`
- Verify: `tests/governance-scan-regression-test.mjs`
- Verify: `scripts/governance-scan.mjs`

- [ ] **Step 1: Run PHP governance tests**

Run: `php tests/content-surface-adapters-test.php`
Expected: PASS with `OK`

- [ ] **Step 2: Run helper coverage test**

Run: `php tests/surface-governance-helpers-test.php`
Expected: PASS with `OK`

- [ ] **Step 3: Run template literal test**

Run: `php tests/template-governance-literals-test.php`
Expected: PASS with `OK`

- [ ] **Step 4: Run scanner regression test**

Run: `node tests/governance-scan-regression-test.mjs`
Expected: PASS with `OK`

- [ ] **Step 5: Run governance scan**

Run: `npm run governance:scan`
Expected: reviews remains `governed`, search/account/cart/checkout deltas are reduced to only accepted exceptions or zero

- [ ] **Step 6: Run JSON governance scan**

Run: `npm run governance:scan -- --json`
Expected: delta list and unmatched baseline entries reflect the new steady state

- [ ] **Step 7: Run TypeScript verification**

Run: `npm run typecheck`
Expected: PASS

- [ ] **Step 8: Commit**

```bash
git add scripts/governance-scan.mjs tests/governance-scan-regression-test.mjs docs/governance-audit/governance-debt-tracking.md governance-baseline.json
git commit -m "test: verify template governance closure phase 1"
```

---

## Exit Criteria

- Governance scan no longer reports the current search/account/cart/checkout delta as fresh leaks.
- `reviews` remains `governed` after scanner regression coverage.
- Baseline file no longer contains stale entries pointing at resolved helper paths.
- The repository has a clean governance baseline suitable for Phase 2 template layout and presentation work.
