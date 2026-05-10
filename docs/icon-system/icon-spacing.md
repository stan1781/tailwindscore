# Icon Spacing

## Goal

Icon spacing should feel optically calm and structurally repeatable across commerce surfaces.

## Tokens

- `--ts-icon-gap-inline`
- `--ts-icon-gap-button`
- `--ts-icon-size-xs`
- `--ts-icon-size-sm`
- `--ts-icon-size-md`
- `--ts-icon-size-lg`

## Rules

1. Inline text icons should use the inline gap token.
2. Button icons should use the button gap token.
3. Utility and navigation icons should stay in the small range.
4. Large icons should be rare and reserved for editorial moments.
5. Increase hit area with the container, not by oversizing the glyph.

## Alignment

- Icons should sit on a stable optical center.
- Chevron and directional icons should align to text rhythm, not float visually high.
- Icon containers should handle spacing; the SVG should stay neutral.

## Unsupported Patterns

- ad hoc per-component icon spacing
- icons with inconsistent optical scale
- enlarged glyphs used to fake emphasis
