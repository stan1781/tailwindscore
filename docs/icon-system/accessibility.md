# Accessibility

## Decorative Usage

Use decorative icons when nearby text already names the action.

Example:

- menu buttons with screen-reader text
- utility links with visible labels
- buttons where the text label is already present

## Non-Decorative Usage

Set `decorative` to `false` and provide `aria_label` when the icon carries meaning on its own.

## Rules

1. Do not rely on color alone.
2. Do not rely on motion alone.
3. Do not use unlabeled icon-only controls.
4. Preserve consistent hit areas through the container, not by enlarging the glyph.

## Unsupported Patterns

- icon-only actions without accessible text
- decorative icons announced by screen readers
- icons used as branding substitutes inside navigation labels
