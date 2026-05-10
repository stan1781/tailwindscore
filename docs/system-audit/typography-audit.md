# Typography Audit

## Goal
Sharpen hierarchy so commerce surfaces feel editorial and deliberate rather than plugin-styled.

## Stable system
- Base heading and body tokens are already centralized in `src/css/base/typography.css`.
- Product pricing, meta, and prose roles are clearly separated.

## Drift to correct
- Empty states were too close to utility copy in scale.
- Auth panels mixed hero-like intros with standard form titles, which softened hierarchy.
- Review content lacked dedicated title, metadata, and body relationships.

## Actions in this phase
- Increase empty-state title presence without turning empty UI into marketing.
- Keep auth intros in restrained body copy while preserving stronger panel titles.
- Add explicit review author, date, rating, and body typography.

## Modules showing language drift
- Predictive search empty state
- WooCommerce login and reset templates
- Product review list and comment form

## Density rule
- No enterprise-style compressed labels, stacked captions, or all-caps heavy panels in core commerce flows.
