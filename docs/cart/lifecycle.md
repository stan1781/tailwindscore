# Cart Lifecycle

## Goal

TailwindScore cart should run through one mutation pipeline:

Client Action  
-> REST Endpoint  
-> WooCommerce Cart Mutation  
-> SSR Fragment Render  
-> DOM Replace

## Single Source Of Truth

The server-rendered WooCommerce cart is the source of truth.

- initial drawer markup is SSR
- add/update/remove all return fresh SSR fragments
- TypeScript never renders cart HTML

## Hydration Strategy

- initial page load uses SSR drawer and SSR badge
- opening the drawer performs a lightweight sync
- add-to-cart replaces drawer and badge fragments from REST
- quantity and remove do the same

## Delegation Rules

- drawer root owns quantity/remove interactions
- no item-level runtime mount
- DOM replacement must not require re-binding each line item

## Unsupported Patterns

- native POST add-to-cart refresh in JS-enabled flow
- duplicated client/server cart state
- client-side cart templating
- patching multiple cart sources at once
