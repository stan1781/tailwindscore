# TailwindScore

TailwindScore is a WordPress theme framework for WooCommerce builds that want classic PHP rendering, a modern front-end toolchain, and a stricter design system.

It is built around three ideas:

- PHP SSR first for templates and commerce truth
- Tailwind token-driven styling for consistent theming
- Vite + TypeScript for progressive enhancement, not app-style takeover

## What It Includes

- WordPress theme structure with WooCommerce support
- PHP template parts and feature modules under `inc/`
- Tailwind CSS v4 token and component layers
- Vite build pipeline for CSS, icons, and TypeScript modules
- Progressive front-end behavior for product, archive, cart, checkout, account, and search surfaces
- Documentation-first project structure under `docs/`

## Stack

- WordPress 6.5+
- PHP 8.1+
- WooCommerce
- Node.js 20+
- Tailwind CSS 4
- Vite 6
- TypeScript 5

## Project Structure

```text
tailwindscore/
|- assets/           Static brand assets and fonts
|- docs/             Architecture, UX, and implementation docs
|- inc/              PHP bootstrapping, hooks, helpers, WooCommerce integrations
|- src/              Source CSS, TypeScript, and icons
|- template-parts/   Reusable SSR view fragments
|- tests/            Focused PHP-level checks
|- woocommerce/      WooCommerce template overrides
|- functions.php     Theme bootstrap entry
|- style.css         Theme metadata and root stylesheet
```

For a deeper map of responsibilities, see [STRUCTURE.md](./STRUCTURE.md).

## Getting Started

### 1. Install dependencies

```bash
npm install
```

### 2. Build assets

```bash
npm run build
```

### 3. Activate the theme

Place the theme in your WordPress `wp-content/themes/` directory and activate `TailwindScore` in wp-admin.

## Local Development

Start the Vite dev server:

```bash
npm run dev
```

Then enable dev mode in `wp-config.php`:

```php
define( 'TAILWINDSCORE_VITE_DEV', true );
```

If your dev server uses a custom origin, filter it:

```php
add_filter( 'tailwindscore/vite/origin', fn () => 'http://localhost:5173' );
```

## Available Scripts

```bash
npm run dev
npm run build
npm run typecheck
```

## Philosophy

### SSR first

Templates render meaningful HTML on the server. JavaScript enhances interactions but does not replace WooCommerce state, checkout truth, or cart truth.

### Token first

Theme customization is meant to flow through `--ts-*` variables and shared design tokens rather than one-off template styling.

### Component first

UI behavior and styling are organized into reusable PHP, CSS, and TypeScript units to keep templates from turning into utility soup.

## WooCommerce Notes

- WooCommerce support is registered in `inc/woocommerce/support.php`
- Theme-specific WooCommerce hooks live under `inc/woocommerce/`
- Template overrides belong in `woocommerce/` only when an override is genuinely needed

## Repository Notes

- `node_modules/` is ignored
- `dist/` is ignored by default
- Build before deployment if your target environment does not compile assets for you

## Documentation

Most of the detailed project thinking lives in [`docs/`](./docs/), including:

- architecture and system decisions
- design token and UI rules
- WooCommerce behavior guidance
- component and feature-level implementation notes

## Contributing

This repository is currently structured like a product framework rather than a generic starter. If you extend it, try to preserve the existing patterns:

- keep PHP responsibilities in `inc/` and `template-parts/`
- keep styling token-driven
- use TypeScript for enhancement layers, not rendering ownership
- update docs when architectural rules change

## License

Released under the [MIT License](./LICENSE).
