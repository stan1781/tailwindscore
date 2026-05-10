# Embla runtime

## Packages

- `embla-carousel` v8 — main viewport + thumbnail viewport as separate instances.

## Main slider (`gallery-slider.ts`)

- Options: `align: 'start'`, `containScroll: 'trimSnaps'`, `loop: false`.
- Provides drag / touch gestures via Embla defaults.

## Thumbnails (`gallery-thumbs.ts`)

- **Axis:** `y` when `min-width: 768px`, otherwise `x`.
- Viewport height capped on desktop (`md:h-[min(70vh,520px)]` in CSS) so vertical scrolling behaves predictably.

## Sync (`gallery/index.ts`)

- `select` on main updates thumbs + `.is-active` on thumb buttons.
- `select` on thumbs updates main.
- Internal `syncing` flag avoids feedback loops between the two Embla instances.

## Resize behaviour

Axis is chosen once at mount (breakpoint at initialization). Resizing across `768px` without reload may diverge axis vs CSS — acceptable trade-off for the commerce foundation phase; full remount-on-breakpoint can be added later.

## Unsupported

- Looping galleries (`loop: false` by design — matches WC gallery UX).
- RTL-specific Embla options — inherits document direction; verify if WooCommerce RTL stores require `direction` tweaks.
