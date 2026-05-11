# Governance Dashboard

The governance dashboard is a read-only visualization layer for configuration health.

This phase defines the contract only. It does not introduce:

- a visual editor
- an AI auto-fix workflow
- a live runtime mutation layer

## Purpose

The dashboard should let maintainers understand whether the premium commerce configuration system is staying inside governance boundaries.

## Required Metrics

The dashboard must be able to visualize:

- governed surface coverage
- critical leak count
- preset compatibility
- localization coverage
- runtime alignment health

## Metric Intent

### Governed Surface Coverage

Shows how many scanned surfaces are:

- governed
- mixed
- unguarded

### Critical Leak Count

Tracks high-severity trust, runtime, or configuration leaks that break governance assumptions.

### Preset Compatibility

Shows whether preset runtime definitions, personality metadata, and configuration transport rules are aligned.

### Localization Coverage

Shows whether mood-aware content surfaces and preset localization posture remain compatible across governed locales.

### Runtime Alignment Health

Shows whether the runtime still respects:

- SSR-first transport
- registry-first resolution
- preset-driven behavior
- no arbitrary rendering paths

## Data Sources

The initial dashboard contract should be supportable by scanner output derived from:

- preset runtime registry
- preset personality metadata
- content mood registry
- Kirki panel and section definitions
- configuration transport rules

## Non-Goals

- no editing from the dashboard
- no auto-remediation
- no template preview mode
- no visual builder hooks
