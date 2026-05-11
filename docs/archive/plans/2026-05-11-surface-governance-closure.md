# Surface Governance Closure Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Close current governance leaks across checkout, account, search, and reviews, and expose closure status in the governance scanner.

**Architecture:** Reuse existing content-surface, content-mood, and feedback registries; remove local delta leaks by routing copy through existing governed helpers; then extend the scanner to describe closure readiness and freeze-state governance.

**Tech Stack:** PHP, WordPress, WooCommerce templates, existing governance scanner, Markdown docs

---

### Task 1: Document the closure workflow and family closure contracts

**Files:**
- Create: `docs/governance-audit/surface-closure-workflow.md`
- Create: `docs/governance-audit/checkout-closure.md`
- Create: `docs/governance-audit/account-closure.md`
- Create: `docs/governance-audit/search-closure.md`
- Create: `docs/governance-audit/reviews-closure.md`

- [ ] **Step 1: Write the failing test**

Run: `rg --files docs/governance-audit`
Expected: target closure files do not exist yet

- [ ] **Step 2: Run test to verify it fails**

Run: `rg -n "surface family lifecycle|closure criteria|governance completion status|trust-critical escalation|runtime alignment rules|localization completion rules" docs/governance-audit`
Expected: no unified closure workflow document yet

- [ ] **Step 3: Write minimal implementation**

Document:

- lifecycle and closure criteria
- checkout/account/search/reviews closure scope
- freeze rule for unclosed surface families

- [ ] **Step 4: Run test to verify it passes**

Run: `rg -n "closure readiness|runtime alignment|localization readiness|freeze" docs/governance-audit`
Expected: closure workflow and family docs contain the required terms

- [ ] **Step 5: Commit**

```bash
git add docs/governance-audit/surface-closure-workflow.md docs/governance-audit/checkout-closure.md docs/governance-audit/account-closure.md docs/governance-audit/search-closure.md docs/governance-audit/reviews-closure.md
git commit -m "docs: define surface governance closure workflow"
```

### Task 2: Add focused helper regression tests for closure-targeted copy paths

**Files:**
- Create: `tests/surface-governance-helpers-test.php`

- [ ] **Step 1: Write the failing test**

Write a PHP test that loads the four helper families and asserts the closure-targeted keys resolve through governed helper arrays instead of local template-only strings.

- [ ] **Step 2: Run test to verify it fails**

Run: `php tests/surface-governance-helpers-test.php`
Expected: FAIL before helper cleanup lands

- [ ] **Step 3: Write minimal implementation**

Create stubs for:

- `tailwindscore_checkout_surface_copy()`
- `tailwindscore_account_surface_copy()`
- `tailwindscore_search_surface_copy()`
- `tailwindscore_review_surface_props()`

and assert the closure-targeted fields exist and are non-empty.

- [ ] **Step 4: Run test to verify it passes**

Run: `php tests/surface-governance-helpers-test.php`
Expected: PASS with `OK`

- [ ] **Step 5: Commit**

```bash
git add tests/surface-governance-helpers-test.php
git commit -m "test: add governed surface helper coverage"
```

### Task 3: Clear checkout and account delta findings

**Files:**
- Modify: `inc/checkout/helpers.php`
- Modify: `template-parts/checkout/checkout-empty.php`
- Modify: `template-parts/checkout/checkout-summary.php`
- Modify: `inc/account/helpers.php`
- Modify: `template-parts/account/address-card.php`
- Modify: `woocommerce/myaccount/form-edit-account.php`
- Modify: `woocommerce/myaccount/form-login.php`
- Modify: `woocommerce/myaccount/form-reset-password.php`
- Modify: `woocommerce/myaccount/view-order.php`
- Modify: `inc/content-surfaces/registry.php`
- Modify: `inc/content-moods/registry.php`

- [ ] **Step 1: Write the failing test**

Use the helper regression test plus scanner output to prove checkout/account still leak current delta strings.

- [ ] **Step 2: Run test to verify it fails**

Run: `node scripts/governance-scan.mjs --json`
Expected: checkout/account delta findings still appear

- [ ] **Step 3: Write minimal implementation**

Route the current checkout/account delta strings into governed helper keys and reuse those helper arrays in templates:

- checkout empty CTA
- checkout summary heading
- account back label and secondary labels
- account address empty state
- account edit-account intro
- account keep-signed-in label
- account reset labels and submit label

- [ ] **Step 4: Run test to verify it passes**

Run: `node scripts/governance-scan.mjs --json`
Expected: current checkout/account delta findings are reduced or eliminated

- [ ] **Step 5: Commit**

```bash
git add inc/checkout/helpers.php template-parts/checkout/checkout-empty.php template-parts/checkout/checkout-summary.php inc/account/helpers.php template-parts/account/address-card.php woocommerce/myaccount/form-edit-account.php woocommerce/myaccount/form-login.php woocommerce/myaccount/form-reset-password.php woocommerce/myaccount/view-order.php inc/content-surfaces/registry.php inc/content-moods/registry.php
git commit -m "feat: close checkout and account governance leaks"
```

### Task 4: Clear search and reviews delta findings

**Files:**
- Modify: `inc/search/helpers.php`
- Modify: `template-parts/search/default-state.php`
- Modify: `template-parts/search/search-overlay.php`
- Modify: `inc/woocommerce/hooks/review-experience.php`
- Modify: `woocommerce/single-product-reviews.php`
- Modify: `inc/content-surfaces/registry.php`
- Modify: `inc/content-moods/registry.php`

- [ ] **Step 1: Write the failing test**

Use the helper regression test plus scanner output to prove search/reviews still leak current delta strings.

- [ ] **Step 2: Run test to verify it fails**

Run: `node scripts/governance-scan.mjs --json`
Expected: search/reviews delta findings still appear

- [ ] **Step 3: Write minimal implementation**

Route the current search/reviews delta strings through governed helpers:

- search default discovery title
- search placeholder language
- review title/intro
- review form intro
- review cookies guidance

Keep review access empty state and search unavailable action aligned with existing feedback/empty-state infrastructure.

- [ ] **Step 4: Run test to verify it passes**

Run: `node scripts/governance-scan.mjs --json`
Expected: current search/reviews delta findings are reduced or eliminated

- [ ] **Step 5: Commit**

```bash
git add inc/search/helpers.php template-parts/search/default-state.php template-parts/search/search-overlay.php inc/woocommerce/hooks/review-experience.php woocommerce/single-product-reviews.php inc/content-surfaces/registry.php inc/content-moods/registry.php
git commit -m "feat: close search and reviews governance leaks"
```

### Task 5: Add scanner completion status system

**Files:**
- Modify: `scripts/governance-scan.mjs`

- [ ] **Step 1: Write the failing test**

Run: `node scripts/governance-scan.mjs --json`
Expected: no explicit per-family governed percentage / closure readiness / unresolved critical leaks / localization readiness / preset compatibility health rollup yet

- [ ] **Step 2: Run test to verify it fails**

Run: `node scripts/governance-scan.mjs --json`
Expected: summary fields absent

- [ ] **Step 3: Write minimal implementation**

Add structured JSON summary for the four closure families and rollup metrics:

- governed percentage
- closure readiness
- unresolved critical leaks
- localization readiness
- preset compatibility health

- [ ] **Step 4: Run test to verify it passes**

Run: `node scripts/governance-scan.mjs --json`
Expected: summary fields exist and reflect reduced delta leakage

- [ ] **Step 5: Commit**

```bash
git add scripts/governance-scan.mjs
git commit -m "feat: add surface governance completion status summary"
```

### Task 6: Final verification

**Files:**
- Verify: `docs/governance-audit/surface-closure-workflow.md`
- Verify: `docs/governance-audit/checkout-closure.md`
- Verify: `docs/governance-audit/account-closure.md`
- Verify: `docs/governance-audit/search-closure.md`
- Verify: `docs/governance-audit/reviews-closure.md`
- Verify: `tests/surface-governance-helpers-test.php`
- Verify: `scripts/governance-scan.mjs`

- [ ] **Step 1: Run helper regression test**

Run: `php tests/surface-governance-helpers-test.php`
Expected: PASS with `OK`

- [ ] **Step 2: Run governance scan**

Run: `npm run governance:scan`
Expected: report prints and closure-targeted delta findings are reduced as much as feasible in this phase

- [ ] **Step 3: Run JSON scan**

Run: `node scripts/governance-scan.mjs --json`
Expected: completion status summary exists

- [ ] **Step 4: Run TypeScript verification**

Run: `npm run typecheck`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add docs/superpowers/specs/2026-05-11-surface-governance-closure-design.md docs/superpowers/plans/2026-05-11-surface-governance-closure.md
git commit -m "docs: record surface governance closure plan"
```
