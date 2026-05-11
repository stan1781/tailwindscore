# Governance Prompt Rules

Every implementation prompt for TailwindScore must declare:

- current phase
- allowed scope
- forbidden scope
- governance ownership
- runtime ownership
- registry path
- localization strategy
- fallback strategy
- preset compatibility
- unresolved debt
- governance impact

## Required Prompt Frame

```text
Current phase: TailwindScore Documentation Governance Consolidation
Allowed scope: docs, documentation governance, IA, lifecycle, archive, AI workflow
Forbidden scope: commerce features, runtime branching, scanner expansion, WooCommerce runtime changes
Governance owner: documentation governance
Runtime owner: existing runtime boundaries remain unchanged
Registry path: <named registry or "not applicable">
Localization strategy: <existing localization path or "not applicable">
Fallback strategy: <existing fallback path or "not applicable">
Preset compatibility: <affected preset boundary or "not applicable">
Unresolved debt: <name the docs or duplicate clusters still pending>
Governance impact: <what canonical doc, lifecycle state, or AI boundary changes>
Task: <specific bounded change>
```

## Prompt Rules

- name the canonical destination before asking for a new markdown file
- state whether the result is constitution, workflow, reference, or archive
- call out any reference files that must be read beyond the default entry stack
- forbid unrelated runtime or feature work in the same prompt
- do not omit registry, localization, or fallback posture when the task touches governed content
- do not rely on implicit authority when multiple legacy references exist
- do not expand frozen legacy directories without an explicit migration target
- do not invent new governance terminology when existing canonical wording already exists
- do not expand governance philosophy prose when a matrix or redirect is sufficient

## Invalid Prompt Patterns

- implementation prompts with no phase declaration
- prompts that ask AI to scan all docs recursively
- prompts that mix documentation consolidation with new runtime capability
- prompts that create multiple permanent markdown files for a single narrow change
- prompts that omit governance impact for a canonical or lifecycle change
- prompts that create multi-canonical ownership for one domain
- prompts that duplicate narrative already covered by a frozen legacy area plus a canonical source
- prompts that change a frozen legacy area without naming its migration target
- prompts that invent new governance terminology instead of reusing canonical labels
- prompts that expand governance philosophy prose without reducing authority noise
