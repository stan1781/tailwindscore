# Kirki Governance

## Governance Boundary Table

| Rule Area | Allowed | Prohibited |
| --- | --- | --- |
| field registration | TailwindScore governance APIs | direct freeform Kirki field usage outside governed area |
| control scope | token, preset, bounded behavior | arbitrary visual controls, giant settings trees, copy-authoring sprawl |
| transport | SSR-aligned server path | client-only transport |

## Ownership Declaration

| Field Owner | Meaning |
| --- | --- |
| `design_tokens` | bounded token profiles |
| `preset_governance` | preset selection and compatibility |
| `commerce_behaviors` | bounded template and runtime behaviors |
| `content_surfaces` | registry-backed messaging surfaces, not Kirki-authored copy |

## SSR Alignment Index

- presets -> `inc/presets/loader.php`
- token overrides -> whitelisted `--ts-*` variables
- behavior defaults -> `inc/configuration/behaviors/registry.php`
- content surfaces -> `inc/content-surfaces/registry.php`
