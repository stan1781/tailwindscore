# TailwindScore AI Entry

This file is the default entry point for AI-assisted work in the Lite reduction phase.

## Default AI Context Stack

Read these files first and stop unless the task explicitly needs more:

1. `docs/AI-ENTRY.md`
2. `docs/architecture.md`
3. `docs/commerce-surfaces.md`
4. `docs/development-rules.md`
5. `docs/components.md`

## Default Intent

Assume TailwindScore is being reduced toward a premium WooCommerce theme with:

- SSR-first rendering
- WordPress-native and WooCommerce-native architecture
- progressive enhancement
- minimal abstraction
- small runtime ownership
- small documentation surface

## Allowed Default Scope

- architecture reduction
- runtime simplification
- WooCommerce surface preservation
- helper and folder consolidation
- CSS and TypeScript reduction
- docs consolidation toward the root-level canonical set

## Forbidden Default Scope

Do not add by default:

- governance systems
- scanner capability
- AI workflow layers
- registry families
- lifecycle families
- new abstraction layers
- enterprise architecture patterns

## Escalation Rule

Read older topical or governance-heavy docs only when one of these is true:

- a current implementation detail cannot be resolved from code or root docs
- a WooCommerce compatibility detail needs historical context
- the user explicitly asks for historical or archived material

When escalation happens, cite the extra path you used.
