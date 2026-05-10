# Motion System

## Goal

TailwindScore motion should feel calm, premium, and commercially appropriate. It should improve continuity across archive, PDP, gallery, swatches, and action states without introducing theatrical behavior.

## Primitive Tokens

Defined in `src/css/tokens/motion.css`:

- `--ts-duration-instant`
- `--ts-duration-fast`
- `--ts-duration-normal`
- `--ts-duration-slow`
- `--ts-ease-standard`
- `--ts-ease-smooth`
- `--ts-ease-emphasis`
- `--ts-ease-exit`

## Semantic Tokens

Use semantic motion tokens in component CSS whenever possible:

- `--ts-motion-hover-duration`
- `--ts-motion-hover-ease`
- `--ts-motion-fade-duration`
- `--ts-motion-fade-ease`
- `--ts-motion-reveal-duration`
- `--ts-motion-reveal-ease`
- `--ts-motion-image-duration`
- `--ts-motion-image-ease`
- `--ts-motion-button-duration`
- `--ts-motion-button-ease`
- `--ts-motion-gallery-duration`
- `--ts-motion-gallery-ease`

## Easing Roles

### Standard

Default interaction easing for most hover and control transitions.

### Smooth

Preferred for fade, reveal, and image continuity. This should be the default premium commerce motion posture.

### Emphasis

Reserved for rare cases where a primary action or key surface needs a slightly stronger sense of intention.

### Exit

Used for dismiss, collapse, and departure states. Exit motion should be shorter and less lingering than entrance motion.

## Interaction Mappings

### Hover

- Use short duration.
- Prefer color, border, shadow, or opacity changes.
- Avoid scale-based hover as a default card behavior.

### Fade

- Use smooth easing.
- Fade should feel quiet and fast enough to avoid lag.

### Reveal

- Use restrained duration.
- Movement should be minimal.
- Reveal should not feel like a staged entrance.

### Image transition

- Crossfade and continuity are preferred over aggressive motion.
- Gallery and card media transitions should feel stable.

### Button state

- Hover and pressed states should respond quickly.
- Buttons should feel crisp, not rubbery.

### Gallery interaction

- Motion should support browsing confidence.
- Avoid over-animated sliding and ornamental transitions.

## Reduced Motion

All commerce interactions must support `prefers-reduced-motion: reduce`.

Rules:

- Remove non-essential translation and scale.
- Collapse motion durations to near-instant transitions.
- Preserve state changes through opacity, contrast, border, or content updates.
- Do not rely on motion as the only interaction cue.

Covered surfaces:

- hover
- gallery
- sticky surfaces
- image transitions
- button states
- reveal transitions

## Unsupported Motion

The following patterns are not supported:

- bounce
- elastic easing
- overshoot
- cheap zoom hover
- large default parallax
- decorative motion without interaction value
- motion used to distract from weak layout or hierarchy
