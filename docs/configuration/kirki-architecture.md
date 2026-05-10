# Kirki Architecture

TailwindScore uses Kirki as a governed Customizer registration layer, not as a freeform theme designer.

## Foundation Shape

The foundation lives in `inc/configuration/kirki/`:

- `bootstrap.php`
- `panels.php`
- `sections.php`
- `fields/`
- `transport/`
- `sanitizers/`
- `presets/`
- `content-surfaces/`

Every module is loaded through `inc/bootstrap.php` and resolved on the server.

## Runtime Contract

The configuration pipeline is:

`Kirki / Customizer -> governed setting -> sanitizer -> SSR resolver -> CSS variable or content surface output`

No client-only preview layer is required for correctness.

## Control Families

Only three registration APIs may create fields:

- `tailwindscore_register_token_control()`
- `tailwindscore_register_preset_control()`
- `tailwindscore_register_content_surface_control()`

Each control must declare:

- governance owner
- sanitize callback
- transport boundary
- preset compatibility
- localization strategy

## Preset First

Preset selection is the primary configuration decision.

Token controls are bounded profile selectors that layer on top of the active preset.

Content surface controls only expose registry-backed surfaces already covered by content mood governance.
