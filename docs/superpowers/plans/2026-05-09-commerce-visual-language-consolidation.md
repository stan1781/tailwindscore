# Commerce Visual Language Consolidation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add the motion token layer and the design documentation needed to formalize TailwindScore's premium commerce visual language without expanding runtime scope.

**Architecture:** Keep the change in the token and docs layer. Load a new `motion.css` from the main CSS entrypoint, preserve compatibility with current motion variables, and add design docs that define the visual language, motion rules, spacing rhythm, and image ratio system. Avoid component rewrites unless a token bridge is required.

**Tech Stack:** Tailwind CSS v4, CSS custom properties, Vite, Markdown documentation

---

## File Structure

- Create: `src/css/tokens/motion.css`
- Create: `docs/design/commerce-visual-language.md`
- Create: `docs/design/motion-system.md`
- Create: `docs/design/spacing-rhythm.md`
- Create: `docs/design/image-ratio-system.md`
- Modify: `src/css/app.css`
- Modify: `src/css/tokens/presets/default.css`

### Task 1: Add motion token file and load it

**Files:**
- Create: `src/css/tokens/motion.css`
- Modify: `src/css/app.css`
- Modify: `src/css/tokens/presets/default.css`

- [ ] **Step 1: Write the intended motion contract in the token file**

Add primitive and semantic motion tokens plus reduced-motion overrides in `src/css/tokens/motion.css`.

- [ ] **Step 2: Load the token file from the CSS entrypoint**

Import `./tokens/motion.css` from `src/css/app.css` after preset imports and before `theme.css`.

- [ ] **Step 3: Preserve compatibility for existing motion consumers**

Keep `--ts-duration-fast`, `--ts-duration-normal`, and `--ts-ease-standard` defined in `src/css/tokens/presets/default.css`, and expand the preset with any missing primitives needed by the new token layer.

- [ ] **Step 4: Build CSS to verify the new token file is accepted**

Run: `npm run build`
Expected: Vite build completes successfully.

### Task 2: Write the commerce visual language documents

**Files:**
- Create: `docs/design/commerce-visual-language.md`
- Create: `docs/design/motion-system.md`
- Create: `docs/design/spacing-rhythm.md`
- Create: `docs/design/image-ratio-system.md`

- [ ] **Step 1: Write the visual language main document**

Document design intent, principles, system relationships, and unsupported styles in `docs/design/commerce-visual-language.md`.

- [ ] **Step 2: Write the motion system document**

Document duration, easing, interaction mappings, reduced-motion rules, and disallowed motion patterns in `docs/design/motion-system.md`.

- [ ] **Step 3: Write the spacing rhythm document**

Document section, PDP, archive, card, and mobile vertical rhythm rules in `docs/design/spacing-rhythm.md`.

- [ ] **Step 4: Write the image ratio system document**

Document default premium ratio posture, per-surface mappings, and exception rules in `docs/design/image-ratio-system.md`.

### Task 3: Verify the docs and token surface align

**Files:**
- Verify: `src/css/tokens/motion.css`
- Verify: `src/css/tokens/presets/default.css`
- Verify: `src/css/app.css`
- Verify: `docs/design/commerce-visual-language.md`
- Verify: `docs/design/motion-system.md`
- Verify: `docs/design/spacing-rhythm.md`
- Verify: `docs/design/image-ratio-system.md`

- [ ] **Step 1: Review the motion token names against the docs**

Confirm the docs reference the same primitive and semantic motion tokens defined in CSS.

- [ ] **Step 2: Scan for scope drift**

Confirm the docs do not introduce new runtime, marketplace behavior, or component redesign requirements.

- [ ] **Step 3: Re-run the build after documentation and token edits**

Run: `npm run build`
Expected: Vite build completes successfully with the final file set.

- [ ] **Step 4: Summarize the final diff and verification evidence**

Prepare the completion summary with modified files and build status.
