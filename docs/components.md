# TailwindScore Components

TailwindScore components should support commerce surfaces directly, without turning the theme into a component framework.

## Preferred Component Shape

- SSR-first PHP template parts
- small helper functions when markup needs shared data preparation
- small TS mounts only when interaction is necessary
- CSS grouped by real commerce surfaces and reusable primitives

## Good Candidates for Shared Components

- buttons
- badges
- notices
- quantity controls
- gallery primitives
- product card primitives
- account cards

## Components to Treat Carefully

- wrappers that only forward data between helpers and templates
- generic system components with no commerce ownership
- parallel PHP and TS abstractions that describe the same UI concern
- component families that only exist to satisfy documentation or registry structure

## Lite Direction

During reduction, prefer:

- direct template usage over thin component indirection
- commerce-surface grouping over abstract system taxonomy
- fewer component entry points with clearer ownership

## Ownership Rule

If a component only exists for one commerce surface, it should usually live close to that surface rather than in a broad framework-style component layer.

## Template Flattening Rule

During ownership reduction, prefer template grouping by commerce feature before grouping by runtime category, governance category, or abstract component family.
