# Transport Rules

## Transport Matrix

| Rule Area | Allowed | Prohibited | Status |
| --- | --- | --- | --- |
| transport boundary | `refresh` | alternate preview transport branches | operational-only |
| output path | `setting -> whitelist bundle -> CSS custom properties -> :root inline style` | component selector CSS generation | operational-only |
| CSS shape | `--ts-*` variables only | arbitrary style strings | operational-only |
| render target | `:root { ... }` block only | inline `style=""` leakage from configuration | operational-only |
| preset handling | merged into existing preset inline style pipeline | preset-specific stylesheet bundles | operational-only |
| preview model | SSR-first correctness | client-only `postMessage` preview contracts | operational-only |

## Canonical Redirect

- runtime boundary: `docs/architecture/runtime-boundaries.md`
- preset boundary: `docs/presets/preset-boundaries.md`
- local transport reference: `docs/configuration/kirki-architecture.md`
