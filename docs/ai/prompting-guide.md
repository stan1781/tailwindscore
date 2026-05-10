# Governance-aware Prompting Guide

Use this guide when prompting Cursor, ChatGPT, or another coding assistant for TailwindScore work.

The goal is prevention-first governance.

## Base Prompt Template

```text
Context: TailwindScore (PHP SSR + TypeScript enhancement + registry-first governance)
Task: <specific change>
Surface: <checkout | cart | account | search | reviews | archive | site shell>
Surface owner: <owner>
Trust classification: <trust-critical | standard>
Governance classification: <governed | mixed | unguarded>
Registry path: <existing key or "new governed surface required">
Runtime copy strategy: <SSR dataset bridge | governed helper | none>
Fallback behavior: <registry fallback | temporary migration fallback | none>
Localization path: <translation wrapper / registry surface / existing helper>
Constraints: no SPA, no inline reassurance, no duplicated helper copy, no runtime hardcoded trust messaging
Output: only necessary changes, plus governance scan follow-up
```

## Surface Creation Prompt Add-on

When asking AI to build a new surface, include:

- surface owner
- governed or mixed target state
- trust-critical status
- required content surface usage
- runtime feedback needs
- accessibility implications

Example:

```text
Create a checkout helper surface.
- Owner: Checkout surfaces
- Trust classification: trust-critical
- Target governance state: governed
- Use existing registry surfaces where possible
- Runtime copy must come from SSR dataset attributes
- No local reassurance paragraphs
- Finish with governance scan delta review
```

## Runtime Module Prompt Add-on

When asking AI to build or edit runtime behavior, include:

- mount target
- dataset inputs
- SSR source of messaging
- allowed runtime fallbacks
- expected governance scan impact

Example:

```text
Update the cart runtime module.
- Use SSR-provided data-feedback-* attributes
- Do not add new hardcoded trust or support copy
- If a temporary runtime fallback is unavoidable, flag it as documented debt
- Keep runtime tone aligned with the SSR cart surface
```

## Review Prompt

Use this when asking AI to review a diff:

```text
Review this change for governance-native compliance.
Check:
- surface ownership
- trust classification
- registry-first usage
- runtime copy leakage
- duplicated fallback logic
- SSR/runtime tone alignment
- likely governance delta findings
```

## Negative Prompt Add-on

Append this when the model tends to overreach:

```text
Do not add:
- arbitrary inline reassurance
- local duplicated helper copy
- runtime hardcoded messaging
- client-only governance bypass
- unrelated refactors
```

## Required Workflow

Good prompts should naturally drive this sequence:

1. define surface
2. define governance classification
3. define registry consumption
4. define runtime messaging
5. define fallback behavior
6. implement SSR
7. implement runtime
8. run governance scan
9. evaluate delta

## Future Direction

Governance-aware AI generation should eventually preload:

- governance workflow rules
- trust-critical surface rules
- content surface rules
- runtime governance contract

This guide prepares prompts for that future without introducing a new toolchain layer.
