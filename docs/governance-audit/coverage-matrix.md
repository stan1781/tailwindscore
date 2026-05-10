# Governance Coverage Matrix

Date: 2026-05-10
Stage: Governance Closure Phase

| Surface | Registry Support | Mood Fallback | SSR Consumption | Localization Safety | Governance Completeness | Status | Governance Owner |
|---|---|---|---|---|---|---|---|
| Checkout reassurance | Yes | Yes | Yes | Yes | High | `resolved` | Checkout surfaces |
| Checkout payment guidance | Yes | Yes | Yes | Yes | High | `resolved` | Checkout surfaces |
| Checkout validation summary | Yes | Yes | Yes | Yes | High | `resolved` | Checkout surfaces |
| Checkout empty cart / unavailable | Yes | Yes | Yes | Yes | High | `resolved` | Checkout surfaces |
| Checkout no-payment / noscript helper | No | No | Inline | Partial | Low | `pending` | Checkout surfaces |
| Account dashboard intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account orders intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account view-order intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account downloads intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account login reassurance | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account lost-password intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account reset-password intro | Yes | Yes | Yes | Yes | High | `resolved` | Account surfaces |
| Account order-detail fallback wording | Partial | Partial | Mixed | Yes | Medium | `deferred` | Account surfaces |
| Search guidance | Yes | Yes | Yes | Yes | High | `resolved` | Discovery surfaces |
| Search recent-searches guidance | Yes | Yes | Yes | Yes | High | `resolved` | Discovery surfaces |
| Search predictive empty message | Yes | Yes | Yes | Yes | High | `resolved` | Discovery surfaces |
| Search discovery headings | No | No | Inline | Yes | Medium | `deferred` | Discovery surfaces |
| Cart drawer reassurance/loading | Yes | Yes | Yes | Yes | High | `resolved` | Cart surface runtime |
| Cart summary note | No | No | Inline | Yes | Low | `pending` | Cart surfaces |
| Cart empty state | Yes | Yes | Yes | Yes | High | `resolved` | Cart surfaces |
| Review access empty state | No | No | Inline | Partial | Low | `deferred` | Review surfaces |
| Archive discovery helper labels | No | No | Inline | Yes | Medium | `deferred` | Archive discovery |

## Matrix Reading Rules

- `High`: registry-driven, mood-governed, SSR-safe, localization-safe
- `Medium`: partly governed, but still contains local fallback or local labels
- `Low`: customer-facing copy still lives outside enforcement path

## Closure Rules

- A trust-critical surface cannot be marked complete while any `critical` finding remains open.
- `pending` means the surface is in active enforcement scope and should not be treated as accepted debt.
- `deferred` means the finding is known and documented, with follow-up intentionally moved to a later phase.
- `accepted-exception` applies only to structural labels or low-risk UI copy that does not carry reassurance, support, or mood semantics.
