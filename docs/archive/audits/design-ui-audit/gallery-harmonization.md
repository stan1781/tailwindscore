# Gallery Harmonization

## Goal

Bring PDP gallery behavior closer to a premium media browser and further from stock WooCommerce plugin styling.

## Decisions

- Main gallery frame now uses the shared media radius and surface shadow tokens.
- Navigation controls use the same control radius language as buttons and inputs.
- Thumb rhythm is tightened with quieter hover states and token-backed shadows.
- Sticky gallery offset now consumes the shared PDP sticky token instead of a local spacing value.

## Replaced Patterns

- Arbitrary gallery nav radius replaced by control radius token
- Utility shadow styling replaced by semantic surface / hover shadows
- Local sticky top spacing replaced by `--ts-pdp-sticky-top`

## Deprecated Styles

- Oversoft nav pills
- Prominent floating control shadows
- Thumbnail emphasis through exaggerated decoration

## Unsupported Interactions

- Gallery chrome competing with imagery
- Heavy overlay depth
- Over-animated thumb transitions
