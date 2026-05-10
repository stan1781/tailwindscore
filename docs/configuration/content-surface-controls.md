# Content Surface Controls

Kirki only exposes curated content surfaces already governed by the content surface registry.

## Current Curated Controls

- announcement
- footer summary
- support message
- checkout reassurance
- account guidance
- search guidance

## Source of Truth

Each control must point back to a registry entry in `inc/content-surfaces/registry.php`.

That registry defines:

- fallback
- mood surface
- sanitizer
- SSR output contract
- storage transport

## Prohibited Inputs

- arbitrary HTML textarea
- inline JavaScript
- raw shortcode execution

## Localization and Mood

Every exposed surface must remain:

- translation-safe
- preset-mood aware
- server-rendered
- sanitizer-backed
