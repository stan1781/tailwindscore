# Preset UI Rules

The preset UI is the center of theme configuration.

## Preset Contract

Preset selection may change:

- token defaults
- spacing rhythm defaults
- motion temperament
- content mood mapping

Preset selection may not change:

- template trees
- WooCommerce runtime logic
- component contracts
- bundle topology

## UI Rules

- use the preset registry as the source of truth
- render one preset selector
- provide SSR-safe preview descriptions
- keep the same template and bundle set for every preset

## Token Follow-Up

Token controls are secondary.

They may only offer bounded profile choices that remain compatible with every supported preset.
