# Feedback Rules

## Intent

TailwindScore feedback should read as commerce guidance, not as app notification chrome.

## Hierarchy

1. Inline validation for the thing the customer can immediately fix.
2. Inline notice region for page-level commerce status.
3. Lightweight toast for confirmed actions that do not need a blocking surface.
4. Live-region announcements for assistive tech parity.

## Rules

- Prefer one calm message over stacked bursts.
- Keep copy short, literal, and operational.
- Use success language sparingly; confirmation should feel settled, not celebratory.
- Error states should direct the next action without sounding punitive.
- Loading states should describe the current operation, not the implementation.
- Auto-dismiss is allowed for catalog and PDP confirmations, but not for cart or checkout risk states.

## Unsupported Styles

- SaaS toast stacks
- dashboard alert panels
- gamified confirmation language
- oversized success motion
- loud warning palettes detached from the theme token system
