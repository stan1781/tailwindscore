# Content Surface Rules

TailwindScore uses a registry-first content surface model instead of scattered text options.

## 1. Required Surface Contract

Every governed content surface should define:

- `key`
- `fallback`
- `sanitizer`
- `translation support`
- `SSR output`
- `customization entry`

If one of these is missing, the surface is not complete.

## 2. Why Registry-first

The registry exists to:

- prevent duplicated text settings
- prevent the same meaning from appearing in multiple editing paths
- give SSR, runtime, and docs one canonical key
- keep customization transport separate from content governance

## 3. Surface Types

Current supported categories include:

- plain text
- textarea copy
- richer text where explicitly allowed
- structured arrays such as social links

Every type needs an explicit sanitizer path.

## 4. Output Rules

Content surfaces provide content, not component structure.

That means:

- SSR helpers inject governed content into existing templates
- surfaces do not decide layout
- surfaces do not become a second UI composition system

## 5. Customization Entry Rules

Customization entries should describe:

- transport
- setting id
- group
- section
- control type

They are transport metadata, not UI architecture.

## 6. Empty State Governance

Empty-state language should be governed through canonical keys.

Use separate governance for:

- `eyebrow`
- `title`
- `message`

Do not scatter multiple local versions of the same empty-state meaning.

## 7. Social Link Governance

Social links can be governed content surfaces when the project needs structured brand-controlled output rather than a generic editable menu.

## 8. Development Workflow Rule

When implementing a new customer-facing surface:

- check for an existing governed key first
- define the surface owner before adding copy
- decide whether runtime will consume the same value through SSR
- do not duplicate fallback logic in template and runtime
- do not add trust or support copy locally when a governed family already exists
