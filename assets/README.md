# assets/

Static assets that are **not** TypeScript modules:

- `fonts/` — self-hosted font files (preload/enqueue from PHP when introduced).
- `images/` — brand marks, placeholders (not product imagery).
- `svg/` — raw icons before optimization/sprite pipelines.

Built CSS/JS belongs in `dist/` via Vite — not here.
