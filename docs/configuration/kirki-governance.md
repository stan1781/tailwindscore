# Kirki Governance

Kirki is allowed only as a thin governed adapter.

## Required Rules

All Kirki fields must be registered through the TailwindScore governance APIs.

Direct usage such as:

- `Kirki::add_field()`
- `new \Kirki\Field\...`

is prohibited outside `inc/configuration/kirki/`.

## Boundary Rules

- No giant settings file
- No arbitrary visual controls
- No raw color pickers
- No spacing sliders
- No radius sliders
- No preset-specific templates
- No client-only transport

## Ownership Rules

Every field must name its governance owner:

- `design_tokens`
- `preset_governance`
- `content_surfaces`

If a field cannot be assigned to one of those layers, it should not ship.

## SSR Alignment

Kirki values must resolve through the same SSR path used by non-Customizer requests.

That means:

- presets resolve through `inc/presets/loader.php`
- token overrides emit only whitelisted `--ts-*` variables
- content surfaces resolve through `inc/content-surfaces/registry.php`
