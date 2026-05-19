# Template Copy Audit Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Produce a complete audit of customer-visible copy emitted by the template layer, classifying each item as `keep`, `remove`, or `lift`, without changing template behavior in this pass.

**Architecture:** The work stays documentation-first. First inventory all template-layer files in scope, then inspect customer-visible output and trace helper-backed copy only when needed, and finally write a grouped audit report plus a concise implementation handoff for the later removal pass.

**Tech Stack:** PHP templates, WordPress/WooCommerce theme helpers, PowerShell, `rg`, Markdown documentation

---

### Task 1: Establish The Audit Workspace

**Files:**
- Create: `docs/superpowers/audits/2026-05-19-template-copy-audit.md`
- Modify: `docs/superpowers/plans/2026-05-19-template-copy-audit-plan.md`
- Reference: `docs/superpowers/specs/2026-05-19-template-copy-audit-design.md`

- [ ] **Step 1: Create the audit output directory**

Run:

```powershell
New-Item -ItemType Directory -Force docs/superpowers/audits
```

Expected: PowerShell prints the created or reused `docs/superpowers/audits` directory.

- [ ] **Step 2: Create the audit report skeleton**

Write this exact file to `docs/superpowers/audits/2026-05-19-template-copy-audit.md`:

```md
# Template Copy Audit

Date: 2026-05-19
Scope: Template-layer customer-visible copy only
Standard: Strict base-template ownership

## Classification Legend

- `keep`: required for function, accessibility, or minimum comprehension
- `remove`: non-essential copy that should not live in the base template layer
- `lift`: copy that may still be useful, but should move out of the base template layer

## Surfaces

### Account

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Search

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Cart

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Checkout

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Archive

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Reviews

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |

### Cross-Surface Components

| File | Context | Current text or token | Classification | Reason |
| --- | --- | --- | --- | --- |
```

- [ ] **Step 3: Verify the report skeleton exists**

Run:

```powershell
Get-Content docs/superpowers/audits/2026-05-19-template-copy-audit.md -TotalCount 80
```

Expected: The file prints the audit title, classification legend, all surface sections, and empty tables.

- [ ] **Step 4: Commit the workspace setup**

Run:

```bash
git add docs/superpowers/audits/2026-05-19-template-copy-audit.md docs/superpowers/plans/2026-05-19-template-copy-audit-plan.md
git commit -m "docs: scaffold template copy audit workspace"
```

Expected: Git creates a commit containing the plan and the audit skeleton.

### Task 2: Inventory Template-Layer Files In Scope

**Files:**
- Modify: `docs/superpowers/audits/2026-05-19-template-copy-audit.md`
- Reference: `template-parts/**`
- Reference: `woocommerce/**`
- Reference: `inc/woocommerce/account.php`
- Reference: `inc/woocommerce/search.php`

- [ ] **Step 1: Capture the template-layer file inventory**

Run:

```powershell
rg --files template-parts woocommerce inc/woocommerce
```

Expected: A file list that includes account, search, cart, checkout, archive, review, shared component, and site template files.

- [ ] **Step 2: Split the inventory into audit surfaces**

Use this exact surface map while reviewing files:

```text
account:
  template-parts/account/**
  woocommerce/myaccount/**
  inc/woocommerce/account.php

search:
  template-parts/search/**
  inc/woocommerce/search.php

cart:
  template-parts/cart-surface/**

checkout:
  template-parts/checkout/**
  woocommerce/checkout/**

archive:
  template-parts/woocommerce/archive-discovery.php
  woocommerce/archive-product.php
  woocommerce/content-product.php

reviews:
  woocommerce/single-product-reviews.php

cross-surface components:
  template-parts/components/**
  template-parts/feedback/**
  template-parts/site/**
  template-parts/sections/**
  woocommerce/content-single-product.php
  woocommerce/single-product.php
  woocommerce/single-product/*
  inc/woocommerce/feedback.php
```

Expected: Every in-scope file has a home surface before content review starts.

- [ ] **Step 3: Exclude non-audit files before content inspection**

Apply these exclusions during the inventory pass:

```text
Skip README files
Skip icon-only files with no customer-visible copy
Skip swatch/image resolver helpers unless a template finding requires them
Skip runtime TypeScript files
Skip admin/configuration files outside the scoped helpers
```

Expected: The remaining review set only contains files capable of emitting customer-visible template copy.

- [ ] **Step 4: Record the scoped file families at the top of the audit report**

Add this section under `Standard` in `docs/superpowers/audits/2026-05-19-template-copy-audit.md`:

```md
## Scoped File Families

- `template-parts/account/**`
- `template-parts/search/**`
- `template-parts/cart-surface/**`
- `template-parts/checkout/**`
- `template-parts/woocommerce/archive-discovery.php`
- `template-parts/components/**`
- `template-parts/feedback/**`
- `template-parts/site/**`
- `template-parts/sections/**`
- `woocommerce/myaccount/**`
- `woocommerce/checkout/**`
- `woocommerce/archive-product.php`
- `woocommerce/content-product.php`
- `woocommerce/content-single-product.php`
- `woocommerce/single-product.php`
- `woocommerce/single-product/*`
- `woocommerce/single-product-reviews.php`
- `inc/woocommerce/account.php`
- `inc/woocommerce/search.php`
- `inc/woocommerce/feedback.php`
```

- [ ] **Step 5: Commit the scoped inventory**

Run:

```bash
git add docs/superpowers/audits/2026-05-19-template-copy-audit.md
git commit -m "docs: define template copy audit scope"
```

Expected: Git creates a commit that adds the scoped file-family section to the audit document.

### Task 3: Classify Customer-Visible Copy By Surface

**Files:**
- Modify: `docs/superpowers/audits/2026-05-19-template-copy-audit.md`
- Reference: `template-parts/**`
- Reference: `woocommerce/**`
- Reference: `inc/woocommerce/account.php`
- Reference: `inc/woocommerce/search.php`

- [ ] **Step 1: Review template files for directly emitted text**

Run:

```powershell
rg -n "(__\(|_e\(|esc_html__|esc_html_e|esc_attr__|esc_attr_e|tailwindscore_.*copy|title|intro|eyebrow|message|guidance|placeholder|label)" template-parts woocommerce inc/woocommerce/account.php inc/woocommerce/search.php inc/woocommerce/feedback.php
```

Expected: A review queue of template-visible strings and helper-fed copy tokens, grouped enough to inspect file by file.

- [ ] **Step 2: Classify each surfaced copy item using the strict ownership rules**

Apply this decision rule for every finding:

```text
keep:
  field labels
  action labels
  screen-reader text
  task-critical empty-state text
  minimum labels required to understand a control or status

remove:
  eyebrow text
  intros
  reassurance/support copy
  decorative headings
  duplicate explanation

lift:
  optional explanatory text
  merchandising/discovery framing
  brand- or mood-dependent copy that may still exist above the template layer
```

Expected: Every customer-visible template copy item found in scope maps to exactly one classification.

- [ ] **Step 3: Populate the audit tables with concrete findings**

For each row added to `docs/superpowers/audits/2026-05-19-template-copy-audit.md`, use this exact row shape:

```md
| `woocommerce/myaccount/form-edit-account.php` | header intro paragraph | `$account_copy['edit_account_intro']` | `remove` | Form fields and save action remain understandable without a supporting intro paragraph. |
```

Expected: The audit tables contain file path, context, current text or token, classification, and a one-sentence reason.

- [ ] **Step 4: Trace helper-backed copy only when a template row needs it**

When a template emits a helper token instead of a literal, inspect only the helper needed to explain it:

```text
Account template copy -> inc/woocommerce/account.php
Search surface copy -> inc/woocommerce/search.php
Feedback empty/loading copy -> inc/woocommerce/feedback.php
```

Expected: The report names the token or helper source cleanly without expanding this audit into a full helper-registry rewrite.

- [ ] **Step 5: Commit the completed classification tables**

Run:

```bash
git add docs/superpowers/audits/2026-05-19-template-copy-audit.md
git commit -m "docs: classify template-layer customer copy"
```

Expected: Git creates a commit with the surface-by-surface audit tables populated.

### Task 4: Derive The Removal Handoff And Verify The Audit

**Files:**
- Modify: `docs/superpowers/audits/2026-05-19-template-copy-audit.md`
- Reference: `docs/superpowers/specs/2026-05-19-template-copy-audit-design.md`

- [ ] **Step 1: Summarize the first removal wave**

Append this section at the bottom of the audit file and fill it with actual findings:

```md
## Recommended First Removal Wave

1. `account`
   - `woocommerce/myaccount/form-edit-account.php` -> `$account_copy['edit_account_intro']`
   - `woocommerce/myaccount/form-login.php` -> registration support paragraph token

2. `search`
   - `template-parts/search/search-overlay.php` -> `$copy['eyebrow']`
   - `template-parts/search/default-state.php` -> `$copy['recent_searches_guidance']`

3. `cross-surface components`
   - list only concrete `remove` items discovered during the completed audit
```

Expected: The summary points to the lowest-risk `remove` items that should disappear before any `lift` work.

- [ ] **Step 2: Summarize candidate lift-outs separately**

Append this section immediately after the removal wave:

```md
## Candidate Lift-Outs

- `surface`: `search`
  - `file`: `template-parts/search/default-state.php`
  - `token`: `$copy['default_state_title']`
  - `why`: discovery framing may still be useful, but it should be owned by a higher content layer if retained
```

Expected: Any text that should move instead of disappear is isolated from pure removal work.

- [ ] **Step 3: Run a self-review against the spec**

Run:

```powershell
Get-Content docs/superpowers/specs/2026-05-19-template-copy-audit-design.md
Get-Content docs/superpowers/audits/2026-05-19-template-copy-audit.md
```

Check for:

```text
Every scoped surface has at least a reviewed section
Every listed copy item has keep/remove/lift
No runtime JS or admin-only copy slipped in
The first removal wave only contains remove items
Lift candidates are separated from remove items
```

Expected: The audit matches the approved design without scope drift.

- [ ] **Step 4: Verify working tree state for handoff**

Run:

```powershell
git status --short docs/superpowers/audits/2026-05-19-template-copy-audit.md docs/superpowers/plans/2026-05-19-template-copy-audit-plan.md
```

Expected: Git shows the audit and plan files as tracked modifications or committed cleanly, ready for the next implementation plan.

- [ ] **Step 5: Commit the audited handoff**

Run:

```bash
git add docs/superpowers/audits/2026-05-19-template-copy-audit.md
git commit -m "docs: finalize template copy audit handoff"
```

Expected: Git creates a commit with the first removal wave and lift-out summary included.
