# Kirki Architecture

## Transport Matrix

| Transport Stage | Input | Output | Authority |
| --- | --- | --- | --- |
| Customizer registration | Kirki / Customizer field | governed setting id | local implementation reference only |
| sanitization | governed setting | whitelisted value | code path |
| SSR resolver | sanitized value | CSS variable or behavior/content output | runtime implementation |
| preset binding | preset selector | bounded profile | `docs/presets/preset-boundaries.md` |
| behavior binding | behavior control | bounded runtime state | `docs/configuration/configuration-rules.md` |
| content binding | registry key | registry-backed output | `docs/content-surfaces/content-surface-rules.md` |

## Control Family Index

| API | Scope | Compatibility Rule |
| --- | --- | --- |
| `tailwindscore_register_token_control()` | token profiles | preset-compatible only |
| `tailwindscore_register_preset_control()` | preset selection | one selector, no template forks |
| `tailwindscore_register_behavior_control()` | bounded template/runtime behavior | enum or boolean only |
| `tailwindscore_register_content_surface_control()` | compatibility-only transport metadata | no new copy-authoring surface area |

## Lifecycle

This file is implementation reference only. It does not define governance authority.
