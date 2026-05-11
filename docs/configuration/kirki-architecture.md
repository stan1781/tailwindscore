# Kirki Architecture

## Transport Matrix

| Transport Stage | Input | Output | Authority |
| --- | --- | --- | --- |
| Customizer registration | Kirki / Customizer field | governed setting id | local implementation reference only |
| sanitization | governed setting | whitelisted value | code path |
| SSR resolver | sanitized value | CSS variable or content output | runtime implementation |
| preset binding | preset selector | bounded profile | `docs/presets/preset-boundaries.md` |
| content binding | surface control | registry-backed output | `docs/content-surfaces/content-surface-rules.md` |

## Control Family Index

| API | Scope | Compatibility Rule |
| --- | --- | --- |
| `tailwindscore_register_token_control()` | token profiles | preset-compatible only |
| `tailwindscore_register_preset_control()` | preset selection | one selector, no template forks |
| `tailwindscore_register_content_surface_control()` | registry-backed content | localization and SSR-safe only |

## Lifecycle

This file is implementation reference only. It does not define governance authority.
