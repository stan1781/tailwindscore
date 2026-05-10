# Governance Automation Foundation

Date: 2026-05-10
Stage: Governance Automation Preparation Layer

## Intent

This phase moves TailwindScore from manual governance toward semi-automated governance awareness.

It does not introduce auto-rewriting or auto-fix behavior.

It does introduce:

- governance detection
- registry coverage mapping
- trust-critical escalation rules
- runtime leakage detection
- enforcement preparation for a future CI phase

## Detection Scope

The current scan foundation covers:

- WooCommerce template overrides
- `template-parts`
- runtime TypeScript modules
- checkout, account, cart, search, reviews, and archive discovery surfaces

## Detection Types

### Hardcoded string detection

Scan for:

- inline reassurance copy
- duplicated support language
- arbitrary validation wording
- helper prose outside registry consumption
- non-governed trust messaging

Primary heuristics:

- PHP translation wrappers such as `__()`, `esc_html__()`, `esc_attr__()`, `esc_html_e()`
- runtime TypeScript string literals that look user-facing
- `data-feedback-*` bridges that inject runtime copy
- trust, support, recovery, checkout, cart, and payment vocabulary

### Registry coverage detection

Map each surface by:

- governed file usage
- governed empty-state usage
- runtime dataset bridging
- mixed usage, where governed and local copy exist in the same file family
- unguarded usage, where customer-facing copy remains outside the registry path

### Duplicate fallback detection

Compare inline copy against fallback language already declared in:

- `inc/content-surfaces/registry.php`
- `inc/content-moods/registry.php`

If a template or runtime repeats fallback prose locally, it is reported as duplicated fallback usage.

### Runtime governance detection

Flag runtime copy that appears in:

- toast messages
- validation messages
- loading copy
- runtime aria messaging
- JS inline labels

Runtime strings are treated as higher risk when they bypass registry-aware SSR data attributes.

### Trust-critical surface detection

Trust-critical escalation applies to:

- checkout
- cart
- account recovery and login
- payment
- validation feedback
- order confidence surfaces

## Severity Rules

### Critical

Use when:

- trust-critical copy is hardcoded
- runtime trust/support copy is inline
- reassurance or recovery language bypasses the governed path

### Warning

Use when:

- fallback wording is duplicated
- runtime helper copy is local but less trust-critical
- support or discovery messaging is mixed between governed and local usage

### Notice

Use when:

- helper text is local but low-risk
- wording drift is visible without directly affecting purchase confidence

### Accepted-exception

Reserved for:

- structural labels
- navigational labels
- low-risk UI language without trust, support, or tonal semantics

## Tooling Entry Point

The current detection foundation lives in:

- `scripts/governance-scan.mjs`

Run it with:

```bash
npm run governance:scan
```

JSON output:

```bash
node scripts/governance-scan.mjs --json
```

## Report Shape

The scan emits:

- severity summary
- coverage rows by surface
- findings with severity, surface, owner, file, and line
- runtime and duplicate-fallback markers

This output is designed to support a governance coverage audit without changing customer-facing copy.

## Future CI Direction

Not implemented in this phase.

Reserved architecture direction:

- pre-merge governance scan
- trust-copy blocking
- registry coverage thresholds
- trust-critical regression alerts

The baseline phase refines this direction further by shifting future CI toward delta enforcement instead of full historical debt blocking.

## Consistency Boundaries

This foundation must not:

- add new UI surfaces
- add new commerce features
- add a design system layer
- auto-rewrite template or runtime copy

The goal is governance awareness and enforcement preparation, not governance destabilization.
