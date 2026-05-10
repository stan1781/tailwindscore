# Coding Rules

Applies to PHP, TypeScript, and CSS/Tailwind work inside TailwindScore.

See also:

- `tailwind-rules.md`
- `typescript-rules.md`
- `../governance-audit/development-workflow.md`

## 1. PHP Rules

- Name functions with the `tailwindscore_{domain}_{verb}` pattern.
- Keep each `inc/` file focused on one responsibility.
- Prefer early returns over deep nesting.
- Do not put heavy queries, remote requests, or business orchestration directly in templates.
- Prefer WordPress and WooCommerce public APIs over direct data access.
- Use context-appropriate escaping and sanitization.
- Use translation wrappers for user-facing strings.

## 2. TypeScript Rules

- Keep `strict` assumptions intact.
- Mount behavior from local roots rather than global page ownership.
- Prefer SSR truth and progressive enhancement over client-owned rendering.
- Avoid runtime copy that drifts away from governed SSR language.

## 3. Tailwind and CSS Rules

- Prefer component-level classes or reusable composition over repeated long utility strings.
- Keep token-driven decisions centralized.
- Avoid page-local magic values unless there is a clear local reason.

## 4. AI-generated Code Rules

Regardless of whether code comes from Cursor or another model, it must satisfy:

1. architecture alignment
2. token and component discipline
3. WooCommerce compatibility awareness
4. reviewable file ownership
5. no unused imports, helpers, or padding

## 5. Governance-native Workflow

Customer-facing work should follow this order:

1. define surface ownership
2. define trust classification
3. define governance state
4. define registry consumption
5. define runtime messaging strategy
6. define fallback behavior
7. implement SSR
8. implement runtime
9. run governance scan and review delta

For trust-critical work, inline reassurance and duplicated helper copy are not acceptable defaults.

## 6. Surface Checklist

Before closing a surface change, confirm:

- surface is marked `governed`, `mixed`, or `unguarded`
- trust-critical status is explicit
- registry integration is explicit
- runtime feedback path is explicit
- localization path is explicit
- accessibility implications are considered
- SSR/runtime tone alignment is intact

## 7. Pre-merge Checks

- PHP output is escaped appropriately
- TypeScript has no stray debug output
- CSS does not add unnecessary duplication
- customer-facing copy follows the governed path where expected
- `npm run governance:scan` has been reviewed

## 8. Related Docs

| Topic | Doc |
|------|------|
| Naming | `../components/naming-conventions.md` |
| WooCommerce flow | `../woocommerce/woocommerce-architecture.md` |
| AI prompting | `../ai/prompting-guide.md` |
| Governance workflow | `../governance-audit/development-workflow.md` |
