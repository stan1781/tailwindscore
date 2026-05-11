# Kirki Governance

## Governance Boundary Table

| Rule Area | Allowed | Prohibited |
| --- | --- | --- |
| field registration | TailwindScore governance APIs | direct freeform Kirki field usage outside governed area |
| control scope | token, preset, content surface | arbitrary visual controls, giant settings trees |
| transport | SSR-aligned server path | client-only transport |

## Ownership Declaration

| Field Owner | Meaning |
| --- | --- |
| `design_tokens` | bounded token profiles |
| `preset_governance` | preset selection and compatibility |
| `content_surfaces` | registry-backed messaging surfaces |

## SSR Alignment Index

- presets -> `inc/presets/loader.php`
- token overrides -> whitelisted `--ts-*` variables
- content surfaces -> `inc/content-surfaces/registry.php`
