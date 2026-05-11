# Cursor Governance Protocol

This file is a secondary AI reference.

Read `docs/AI-ENTRY.md`, `docs/ai/system-constitution.md`, and `docs/ai/governance-prompt-rules.md` first for the active AI workflow boundary.

Applies to Cursor and any AI-assisted implementation inside TailwindScore once the active documentation boundary has been loaded.

The goal is governance-native generation, not post-hoc governance cleanup.

## Core Rule

Before generating a new surface or modifying a trust-adjacent surface, define:

1. surface ownership
2. trust classification
3. registry usage
4. runtime copy strategy
5. fallback behavior
6. localization path
7. SSR/runtime alignment

If these are not clear, generation should stop and resolve them first.

## Required Implementation Contract

Every generated surface must declare:

- `surface owner`
- `governed | mixed | unguarded`
- `trust-critical: yes | no`
- `registry-backed: yes | no`
- `runtime messaging path`
- `fallback path`
- `localization-safe: yes | no`
- `SSR/runtime alignment notes`

## Hard Prohibitions

Do not generate:

- arbitrary inline reassurance
- duplicated helper copy already represented by a governed surface
- runtime hardcoded trust or support language
- client-only governance bypass
- local fallback branching that duplicates registry fallback logic

## Registry-first Rules

When the surface includes customer-facing messaging:

- prefer an existing content surface key first
- if no key exists, define the governed surface contract before implementation
- SSR should consume governed values directly
- runtime should consume SSR-provided governed values, not invent parallel copy

## Trust-critical Rules

Treat these as trust-critical by default:

- checkout
- cart mutation feedback
- payment guidance
- account recovery and password flows
- validation summary and inline validation
- order confidence messaging

For trust-critical work:

- never add inline reassurance casually
- never split trust tone between SSR and runtime
- never introduce a local helper paragraph when a governed path exists

## Runtime Governance Contract

Toast, validation, loading, feedback, and aria messaging must be registry-aware.

That means:

- runtime copy should come from SSR dataset bridges, governed helpers, or registered surface values
- runtime fallback literals are temporary migration debt, not the preferred implementation
- if runtime fallback is unavoidable, it must be documented as governance debt or baseline debt

## Surface Checklist

Before finalizing generated code, confirm:

- surface classification is named
- trust-critical status is named
- registry integration is defined
- mood compatibility is considered
- runtime messaging strategy is explicit
- accessibility implications are checked
- governance scan is part of the closeout

## SSR / Runtime Alignment Rules

Avoid:

- runtime tone drift
- duplicated fallback logic
- inconsistent reassurance language
- runtime-only helper copy with no SSR equivalent

Preferred path:

`registry -> SSR helper -> template output -> runtime dataset consumption`

## Feature Flow

Cursor should follow this order:

1. define surface
2. define governance classification
3. define registry consumption
4. define runtime messaging
5. define fallback behavior
6. implement SSR
7. implement runtime
8. run governance scan
9. evaluate delta against baseline

## Required References

Before generating governed commerce work, mentally check:

- `docs/governance-audit/development-workflow.md`
- `docs/governance-audit/trust-critical-surfaces.md`
- `docs/content-surfaces/content-surface-rules.md`
- `docs/feedback-system/feedback-rules.md`
- `docs/governance-audit/baseline-system.md`

## Future Direction

AI-assisted generation should eventually default to loading:

- governance workflow rules
- trust-critical rules
- registry rules
- runtime governance contract

This document establishes the workflow expectation now, without adding a new subsystem.
