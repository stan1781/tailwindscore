# Governance-native Commerce Development Workflow

Date: 2026-05-10
Stage: Governance-native Commerce Development Workflow

## Intent

Governance should be part of the default development path.

The goal is prevention-first governance, not audit-first governance.

This phase does not add a new scanner subsystem or a large commerce feature.

It does define the default workflow for implementing governed surfaces.

## Development Contract

Every new or materially changed customer-facing surface should define:

- surface ownership
- trust classification
- governance state: `governed`, `mixed`, or `unguarded`
- registry consumption path
- runtime messaging strategy
- fallback behavior
- localization path
- SSR/runtime alignment notes
- accessibility implications

## Surface Governance Checklist

Before implementation, answer:

1. Which surface family owns this work?
2. Is the surface trust-critical?
3. Is the target state governed, mixed, or temporarily unguarded?
4. Does an existing content surface key already cover the copy?
5. Does the surface need mood compatibility?
6. Will runtime output any customer-facing copy?
7. How will SSR and runtime stay tone-aligned?
8. What is the fallback path?
9. What is the localization-safe path?
10. What governance scan result should be expected after implementation?

## Trust-critical Implementation Contract

Treat these as trust-critical by default:

- checkout
- cart mutation flows
- payment guidance
- account recovery and password flows
- validation messaging
- order confidence surfaces

Trust-critical work must:

- be registry-aware
- avoid inline reassurance
- avoid duplicated helper copy
- keep runtime messaging aligned with SSR
- avoid client-only governance bypass

## Registry-first Development Rules

When a surface includes customer-facing copy:

- check for an existing governed key first
- prefer extending the current surface family over inventing local copy
- keep fallback logic centralized in the governed path
- let templates consume resolved values rather than rebuild fallback logic locally

Preferred sequence:

`governed surface definition -> SSR helper consumption -> template output -> runtime dataset consumption`

## Runtime Governance Contract

All of the following must be registry-aware:

- toast
- validation
- loading
- feedback
- aria messaging

Runtime governance rules:

- runtime should consume SSR-provided governed values whenever possible
- runtime must not invent parallel trust tone
- runtime fallback literals count as temporary migration debt
- if runtime fallback is unavoidable, document it so it can enter the baseline or debt workflow

## SSR / Runtime Alignment Checklist

Before finishing a governed surface, confirm:

- SSR and runtime use the same tone family
- runtime does not drift into a second reassurance voice
- fallback behavior is not duplicated in two layers
- trust messaging is not split between template-local and JS-local wording
- accessibility announcements follow the same operational tone as visible feedback

## Feature Implementation Flow

Use this order by default:

1. define surface
2. define governance classification
3. define registry consumption
4. define runtime messaging
5. define fallback behavior
6. implement SSR
7. implement runtime
8. run governance scan
9. evaluate delta against baseline

## Governance Scan Closeout

After implementation:

- run `npm run governance:scan`
- inspect new delta findings
- confirm no unexpected trust-critical regression appeared
- if a temporary exception is intentionally introduced, document it

Use `npm run governance:scan:all` only when full historical context is needed.

## AI Generation Direction

Future AI-assisted development should default to reading:

- governance workflow rules
- trust-critical rules
- surface registry rules
- runtime governance contract

This document establishes the workflow contract now, without adding a new governance subsystem.
