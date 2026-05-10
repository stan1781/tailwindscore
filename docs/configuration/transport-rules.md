# Transport Rules

TailwindScore supports one configuration transport boundary for Kirki fields:

- `refresh`

## Why

The theme is SSR-first. Configuration must be correct even when no Customizer preview JavaScript runs.

## Allowed Output

The only governed visual output path is:

`setting -> whitelist bundle -> CSS custom properties -> :root inline style`

Allowed CSS output characteristics:

- `--ts-*` variables only
- `:root { ... }` block only
- merged into the existing preset inline style pipeline

## Prohibited Output

- component selector CSS generation
- arbitrary style strings
- inline `style=""` leakage from configuration
- client-only `postMessage` preview contracts
- preset-specific stylesheet bundles
