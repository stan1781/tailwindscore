# Surface Governance Closure Design

## Goal

Close the current governance gaps across the checkout, account, search, and reviews surface families so TailwindScore can enter a Governed Commerce Stability Phase without adding commerce features, runtime capability, configuration surface area, or architectural forks.

## Scope

This phase covers:

- surface family closure workflow documentation
- closure docs for checkout, account, search, and reviews
- first-pass code cleanup for the current scanner delta findings in those four families
- governance scanner completion status summary
- freeze rules for unclosed surface families

This phase does not cover:

- new commerce modules
- new runtime branches
- new theme configuration
- visual builder work
- architecture refactors

## Architecture

The closure work stays inside existing governance paths:

`surface family template/helper/runtime -> existing content surface registry + content mood registry + feedback empty-state registry -> scanner completion status`

No new rendering system is introduced. No new commerce behavior is introduced. The work closes gaps where templates and runtime bridges still bypass the governed surface pipeline.

## Closure Principles

### 1. Surface family is the governance unit

Closure is measured by surface family rather than individual file. A family is only closure-ready when helper copy, empty states, runtime messages, fallback behavior, and localization posture all route through governed paths.

### 2. Registry-first before fallback removal

When a leak exists, the first correction is to route it through the existing registry or helper contract. Only after that should duplicate local fallback be removed or reduced.

### 3. Trust-critical issues escalate first

Checkout, account access, and purchase-sensitive guidance remain the highest priority because copy drift there changes customer trust and can fragment runtime behavior.

### 4. Runtime alignment matters as much as SSR

A surface is not closed if SSR copy is governed but runtime still carries divergent local messaging.

### 5. Localization closure is required

A family is not governance-complete if the visible language is routed correctly in English but still bypasses mood-safe, localization-safe fallback rules.

## Design Units

### Surface Family Closure Workflow

Define lifecycle stages:

- unguarded
- mixed
- closure-in-progress
- closure-ready
- governed

Each family must declare:

- current leak classes
- trust-critical status
- runtime alignment state
- localization completion state
- freeze status

### Checkout Closure

Close the current delta findings by routing:

- unavailable/empty CTA labels
- summary helper language
- validation and loading wording
- payment fallback messaging
- no-payment state
- mobile summary guidance
- address and reassurance copy
- noscript guidance

through existing governed helpers or new registry keys within the existing content-surface system.

### Account Closure

Close the current delta findings by routing:

- order helper language
- downloads and dashboard helper copy
- secondary action labels
- address guidance and empty states
- edit-account reassurance copy
- sign-in persistence labels where governance permits

through the existing account surface and content-surface registry path.

### Search Closure

Close the current delta findings by routing:

- default discovery title/guidance
- predictive empty messaging
- unavailable-state actions
- recent-search guidance
- no-results language
- search input placeholder language

through the existing search surface copy path while preserving premium editorial commerce discovery tone.

### Reviews Closure

Close the current delta findings by routing:

- review title and intro
- purchase-required state
- review form reassurance
- cookies guidance
- moderation/rating helper copy where currently leaked

through governed review surface props and feedback state helpers.

### Governance Completion Status System

The scanner should summarize:

- governed percentage
- closure readiness
- unresolved critical leaks
- localization readiness
- preset compatibility health

for each closure-targeted family and as a rollup.

### Governance Freeze Rule

Unclosed surface families should be treated as feature-frozen except for:

- trust-critical fixes
- accessibility fixes
- localization fixes

This is a workflow and governance contract, not a runtime feature.

## Error Handling

- If a surface cannot fully remove local labels because of third-party WooCommerce structure, move the wording into the nearest governed helper and keep any unavoidable structural labels isolated.
- If runtime still needs an inline bridge, the bridge must consume SSR/governed data rather than hardcoded local wording.

## Testing

This phase needs:

- focused PHP tests for helper copy resolution across the four families
- scanner verification for completion status summary
- governance scan before and after cleanup to confirm delta reduction
