# Template Copy Audit Design

## Goal

Audit customer-visible copy emitted directly by the template layer and classify each item as:

- `keep`: required for function, accessibility, or minimum comprehension
- `remove`: non-essential copy that does not belong in a base template
- `lift`: copy that may still be useful, but should not be owned by the base template layer

The purpose is to reduce template-layer copy debt before any further baseline cleanup or governance expansion.

## Scope

This audit covers customer-visible copy emitted from the template layer in:

- `template-parts/**`
- `woocommerce/**`
- tightly coupled PHP helpers that directly supply template copy, starting with:
  - `inc/woocommerce/account.php`
  - `inc/woocommerce/search.php`
  - adjacent template-facing helpers only if they are necessary to explain a template finding

This audit does not cover:

- JavaScript runtime messages
- admin or Customizer copy
- internal developer-facing strings
- schema, metadata, or transport-only registry values that are not directly used by templates in this pass

## Audit Standard

The audit uses a strict base-template standard.

Base templates should primarily own:

- structure
- semantics
- data binding
- accessibility-required text
- minimum functional labels and state text

Base templates should not default to owning:

- eyebrow or kicker copy
- intro, guidance, reassurance, or support paragraphs
- mood-setting or brand-tonal filler
- explanatory copy that repeats what the UI already makes clear
- "completeness" copy added to make a surface feel fuller without changing task success

## Classification Rules

### Keep

Classify as `keep` when the text is required to complete the task or understand the control safely, including:

- field labels
- action labels
- required error text
- minimum empty-state text when the user would otherwise lose context
- screen-reader and accessibility text

### Remove

Classify as `remove` when the text can disappear without harming task completion, comprehension, or accessibility, including:

- decorative headings
- intro paragraphs
- reassurance copy
- duplicated explanation
- helper guidance that is not required to act

### Lift

Classify as `lift` when the text should not live in the base template but may still be legitimate in a higher content layer, including:

- brand- or mood-dependent explanatory text
- discovery framing copy that belongs to a configurable surface
- optional merchandising or guidance text

## Output Format

The audit result will be grouped by surface:

- `account`
- `search`
- `cart`
- `checkout`
- `archive`
- `reviews`
- any additional surface encountered during the template pass if needed

Each finding should include:

- file path
- local context or template role
- current text or copy token
- classification: `keep`, `remove`, or `lift`
- short reason

## Execution Approach

1. Enumerate template-layer files in scope.
2. Inspect customer-visible text output in each file.
3. Trace helper-provided copy only when needed to classify template output accurately.
4. Produce a grouped audit report using the `keep` / `remove` / `lift` model.
5. From that audit, derive the next implementation plan:
   - remove non-essential template copy first
   - then shrink governed surfaces and baseline entries that were only supporting removable template copy

## Expected Follow-Up

This design intentionally separates audit from implementation.

After the audit is approved, the next plan should execute in two passes:

1. remove non-essential template copy
2. reconcile helper surfaces, tests, and baseline entries that become obsolete after removal

## Risks And Controls

### Risk: over-pruning functional context

Control:
- preserve minimum labels, accessibility text, and task-critical states

### Risk: mixing template copy with runtime or admin concerns

Control:
- keep runtime JS and admin copy out of this audit

### Risk: deleting text that should exist but not in base templates

Control:
- use `lift` instead of `remove` when the content may still belong in a higher-level surface

## Success Criteria

The audit is successful when:

- the repo has a complete template-layer copy inventory for the scoped surfaces
- every customer-visible template copy item in scope is classified as `keep`, `remove`, or `lift`
- the result is specific enough to drive a low-ambiguity removal plan
- future baseline cleanup can be tied to template responsibility rather than copy-by-copy patching
