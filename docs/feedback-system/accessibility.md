# Accessibility

## Live Region Strategy

- Use `polite` for confirmations and in-flow updates.
- Use `assertive` for blocking validation and failed actions.
- Do not fire multiple announcements for the same state change.

## Focus Rhythm

- Keep focus where the next action happens.
- Do not steal focus for lightweight confirmations.
- Preserve trigger return paths for drawers and overlays.

## Validation

- Pair field invalid state with a visible message in the local scope.
- Checkout and cart errors must remain persistent until resolved.
- Variation selection failures should announce the missing action without opening a new surface.

## Motion

- Reduced motion disables nonessential entrance and exit choreography.
- Dismiss transitions should remain subtle and short.
