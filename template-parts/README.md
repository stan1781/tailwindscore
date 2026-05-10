# template-parts

SSR fragments assembled by top-level templates via `get_template_part()`.

Suggested layout:

- `components/` — buttons, forms, modal/drawer shells, price-block, etc.
- `layout/` — headers/footers sections when they grow beyond `header.php`.
- `woocommerce/` — shared fragments consumed by `woocommerce/*.php` overrides.

Do not paste long Tailwind utility chains here — prefer `.ts-*` component classes from `src/css/components/`.
