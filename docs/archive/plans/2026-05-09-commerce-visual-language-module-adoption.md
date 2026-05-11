# Commerce Visual Language Module Adoption Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Update archive card, gallery, swatches, and PDP-adjacent commerce CSS so they consume the semantic motion token system and align more closely with TailwindScore's premium commerce visual language.

**Architecture:** Keep the work within existing CSS modules. Replace raw timing and easing values with semantic motion tokens where they map cleanly, and tighten heavy hover, shadow, radius, and ratio choices without changing structure or behavior.

**Tech Stack:** Tailwind CSS v4, CSS custom properties, Vite

---

## File Structure

- Modify: `src/css/components/product-archive/product-card.css`
- Modify: `src/css/components/product-archive/product-card-media.css`
- Modify: `src/css/components/product-archive/product-card-actions.css`
- Modify: `src/css/components/gallery/index.css`
- Modify: `src/css/components/swatches/swatch-base.css`
- Modify: `src/css/components/swatches/swatch-color.css`
- Modify: `src/css/components/swatches/swatch-image.css`
- Modify: `src/css/components/swatches/swatch-text.css`
- Modify: `src/css/components/swatches/swatch-stack.css`
- Modify: `src/css/components/commerce-experience/purchase-flow.css`
- Modify: `src/css/components/commerce-experience/product-information.css`
- Modify: `src/css/components/variation-experience/variation-state.css`
- Modify: `src/css/components/variation-experience/variation-price.css`

### Task 1: Align archive card with semantic motion tokens

**Files:**
- Modify: `src/css/components/product-archive/product-card.css`
- Modify: `src/css/components/product-archive/product-card-media.css`
- Modify: `src/css/components/product-archive/product-card-actions.css`

- [ ] Replace raw duration/easing usage with semantic motion tokens where appropriate.
- [ ] Keep the archive card hover restrained and border-led.
- [ ] Keep the card media transition reduced-motion safe.

### Task 2: Tighten gallery and thumbs

**Files:**
- Modify: `src/css/components/gallery/index.css`

- [ ] Reduce heavy shadow and overly soft radius treatment on gallery controls and frames.
- [ ] Align transitions and thumb states with semantic motion tokens.
- [ ] Preserve current layout and runtime compatibility.

### Task 3: Unify swatch motion and surface treatment

**Files:**
- Modify: `src/css/components/swatches/swatch-base.css`
- Modify: `src/css/components/swatches/swatch-color.css`
- Modify: `src/css/components/swatches/swatch-image.css`
- Modify: `src/css/components/swatches/swatch-text.css`
- Modify: `src/css/components/swatches/swatch-stack.css`

- [ ] Replace hardcoded swatch timing values with semantic motion tokens.
- [ ] Tighten radius posture and selected-state clarity.
- [ ] Keep reduced-motion coverage intact.

### Task 4: Align PDP-adjacent commerce surfaces

**Files:**
- Modify: `src/css/components/commerce-experience/purchase-flow.css`
- Modify: `src/css/components/commerce-experience/product-information.css`
- Modify: `src/css/components/variation-experience/variation-state.css`
- Modify: `src/css/components/variation-experience/variation-price.css`

- [ ] Move transitions to semantic motion tokens where they map cleanly.
- [ ] Calm CTA, tabs, and variation-state motion and surface weight.
- [ ] Preserve existing behavior and layout structure.

### Task 5: Verify

**Files:**
- Verify: all files above

- [ ] Run: `npm run build`
- [ ] Confirm the CSS pipeline still succeeds.
- [ ] Summarize affected modules and any intentionally retained legacy behavior.
