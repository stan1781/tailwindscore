# Configuration UX Consolidation Design

## Goal

Consolidate TailwindScore's existing governance-driven configuration system into a long-term maintainable premium commerce configuration experience without expanding runtime commerce features, arbitrary customization, live editing, or template branching.

## Scope

This phase covers:

- preset personality metadata
- SSR-safe preset preview language for Kirki
- configuration information architecture documentation and admin language
- governance dashboard contract documentation
- localization governance strategy documentation
- governance scanner extension for configuration drift detection

This phase does not cover:

- new commerce modules
- new visual builder capabilities
- preset-specific templates or bundles
- runtime feature expansion
- arbitrary design controls

## Architecture

The system remains registry-first and SSR-first.

`preset registry -> preset metadata -> Kirki preview summaries + governance scanner + documentation`

The existing preset registry in `inc/presets/registry.php` stays responsible for runtime preset definitions and SSR token/content-mood overrides. A new metadata layer in `inc/presets/metadata/` becomes the canonical governance contract for preset personality, capability boundaries, localization posture, and admin-facing preview semantics.

Kirki continues to act as a governed transport layer. The preset selector becomes a commerce personality selector through SSR-safe preview descriptions assembled from metadata. No live visual rendering, independent preset stylesheet, or client-only preview pipeline is introduced.

The governance scanner expands from copy leakage detection into configuration-governance drift detection. It reads preset metadata and configuration section definitions to flag capability violations, localization mismatch, admin IA drift, and invalid configuration grouping. The scanner remains read-only and report-oriented.

## Design Units

### 1. Preset Personality System

Add a metadata registry under `inc/presets/metadata/` that defines, for each preset:

- `visual_identity`
- `commerce_rhythm`
- `density_profile`
- `motion_personality`
- `mood_family`
- `shell_language`
- `content_pacing`
- `capability_matrix`
- `governance_boundary`
- `localization_posture`

This metadata is descriptive and governing, not an alternate runtime rendering system. Runtime preset output remains in the existing preset registry and loader. Metadata must explicitly forbid:

- template branching
- component structure changes
- runtime divergence
- arbitrary expansion of preset scope

### 2. Preset Preview UX

Upgrade the current preset selector description pipeline so the preview summary is assembled from preset metadata. The output is text-first and SSR-safe, describing:

- tone
- motion
- density
- commerce pacing
- governance compatibility note

This is a bounded preview summary, not a live rendered preview card system with separate styling assets. If the active control remains a native Kirki select/radio experience, the summary still expresses the intended personality system without introducing a new runtime dependency.

### 3. Configuration Information Architecture

TailwindScore configuration language should reflect governance domains instead of legacy theme-option categories.

The target top-level admin language is:

- Design Language
- Commerce Experience
- Content Surfaces
- Governance

Existing panel and section text should be updated to align with this IA where possible inside the existing Customizer foundation. This phase documents the IA contract and adjusts current panel/section labels and descriptions to reduce drift.

### 4. Governance Visualization Layer

This phase defines the contract for a read-only governance dashboard. It does not introduce a new admin application page yet.

The dashboard contract must be supportable by scanner output for:

- governed surface coverage
- critical leak count
- preset compatibility
- localization coverage
- runtime alignment health

### 5. Localization Governance Strategy

Localization rules must preserve commerce tone across locales. The new strategy documents:

- tone-safe localization rules
- mood compatibility rules
- locale-aware fallback strategy

The localization posture of each preset must be explicit in preset metadata so scanner rules can detect mismatches between preset claims and content mood support.

### 6. Governance Scanner Extension

The scanner extends into configuration governance without becoming a runtime subsystem. New detection families:

- preset capability violation
- localization mismatch
- admin IA drift
- invalid configuration grouping

The scanner should continue to report findings, coverage, and deltas. It must not mutate configuration, suggest AI rewrites, or attempt automatic fixes.

## Data Flow

1. Preset runtime definitions remain in `inc/presets/registry.php`.
2. Preset personality metadata is loaded from `inc/presets/metadata/`.
3. Kirki preset helpers merge runtime preset labels with metadata summaries for SSR-safe admin descriptions.
4. Configuration IA docs mirror the section and panel structure defined in `inc/configuration/kirki/`.
5. The governance scanner reads preset metadata, preset runtime definitions, configuration section definitions, and content mood registry data to produce governance drift findings.

## Error Handling

- Missing preset metadata falls back to a governed warning state in scanner output.
- Preview summary helpers should degrade to the preset description instead of failing hard.
- Scanner rules should classify missing or malformed metadata as governance findings, not fatal script errors, whenever possible.

## Testing

This phase needs focused regression coverage around:

- preset metadata resolution
- preset preview summary formatting
- configuration section IA grouping consistency
- governance scanner configuration findings for new drift categories

PHP tests should cover metadata and preview helpers. Node-based verification should cover scanner output behavior. No frontend rendering test suite is needed because this phase intentionally avoids live preview rendering.
