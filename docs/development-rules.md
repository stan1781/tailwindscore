# TailwindScore Lite Development Rules

Use these rules as the default implementation boundary during the Lite reduction phase.

## Build Direction

- prefer WordPress-native and WooCommerce-native patterns
- prefer server-rendered templates over new runtime abstraction
- prefer direct helpers over new intermediate layers
- prefer consolidation over expansion
- prefer stable commerce UX over framework purity

## Runtime Rules

- JavaScript is progressive enhancement only
- keep runtime ownership small and explicit
- avoid duplicate copy or fallback chains across PHP and TS
- do not add new registries or scanner-driven runtime behavior
- prefer feature entry points over deep register-and-mount fan-out
- keep `data-ts-module` contracts stable while reducing registry depth

## PHP Rules

- keep WooCommerce hooks close to the surface they modify
- consolidate thin adapters when they do not provide real reuse
- keep helper APIs small and specific
- avoid adding new framework-style subsystems
- prefer feature-owned files over governance-owned or transport-owned files
- flatten bootstrap ownership when a layer only forwards to another layer

## Docs Rules

- add or update root-level canonical docs before expanding topical trees
- keep new docs short and implementation-facing
- treat older governance-heavy docs as historical unless explicitly referenced
- do not grow AI-only documentation layers

## Preset and Registry Rules

- presets should converge toward `minimal`, `editorial`, `luxury`, and `dark`
- registry usage should narrow to tokens, presets, notices, and essential commerce copy
- do not add new mood, lifecycle, or fallback families

## Change Acceptance

Every reduction pass should preserve:

- commerce UX
- SSR-first behavior
- WooCommerce compatibility
- accessibility
- performance
