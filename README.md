# TailwindScore

Modern WooCommerce starter framework — **PHP SSR first**, **Tailwind tokens**, **Vite + TypeScript modules**.

Docs live in `docs/`. Directory responsibilities: see `STRUCTURE.md`.

## Requirements

- WordPress **6.5+**, PHP **8.1+**
- Node **20+** (recommended) for tooling

## Quick start

```bash
npm install
npm run build
```

Activate **TailwindScore** in WP Admin. For local HMR:

1. `npm run dev`
2. Add to `wp-config.php`:

```php
define( 'TAILWINDSCORE_VITE_DEV', true );
```

Filter dev origin if needed:

```php
add_filter( 'tailwindscore/vite/origin', fn () => 'http://localhost:5173' );
```

## WooCommerce

WooCommerce support is declared in `inc/woocommerce/support.php`. Template overrides belong in `woocommerce/` only when required.

## Project principles

- SSR first — JS enhances; it does not replace cart/checkout truth.
- Token first — customize via `--ts-*` variables (Kirki-compatible).
- Component first — avoid utility explosion in PHP templates.
