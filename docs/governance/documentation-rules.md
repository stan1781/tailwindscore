# Documentation Rules

## Markdown Creation Gate

Add a new markdown file only when all of these are true:

- the content has a distinct lifecycle
- the content has a clear canonical owner
- the content cannot fit an existing canonical document without harming readability
- the content is not historical notes that belong in archive
- the AI context impact has been checked
- no equivalent active doc already covers the same ownership

Before adding a new doc, declare:

| Required Declaration | Rule |
| --- | --- |
| canonical owner | name the single owning file or directory |
| equivalent existing docs | list the closest active docs and why they are insufficient |
| lifecycle stage | active, reference, frozen legacy, or archive |
| archive strategy | state how the doc will be retired or re-homed |
| AI context impact | say whether it enters default, optional, or excluded AI context |
| classification | label it constitution, workflow, reference, operational, or archive |

## When Consolidation Is Required

Consolidation is mandatory when any of these appear:

- two or more files define the same workflow
- the same governance rule is repeated across multiple directories
- a new file would only restate an existing concept with different phase language
- a completed plan leaves behind permanent narrative duplication

## When Reduction Is Required

Reduction is mandatory when any of these appear:

- long prose can become a table, matrix, index, or map
- a workflow repeats the same gate already defined elsewhere
- a frozen legacy directory still reads like active authority
- AI would need to scan a directory instead of one named canonical file

## When Archiving Is Required

Archive the document when it is:

- completed
- superseded
- phase-specific and no longer active
- audit-only historical evidence
- legacy guidance replaced by a canonical rule
- outdated governance narrative with no remaining active ownership

## Duplication Threshold

Treat duplication as unresolved when:

- one concept needs maintenance in more than one canonical file
- the same rule appears in more than two non-archive files
- AI would need to read more than one workflow file to understand the same decision path

## Ownership Rules

- every active document must have an obvious owning area
- every governed domain must have one canonical source
- phase docs own active implementation context
- governance docs own rules and lifecycle
- architecture docs own runtime boundaries
- AI docs own prompt and reading behavior
- reference docs own lookup material, not workflow

## Lifecycle Rules

- one active phase at a time
- one default AI entry point
- archive is read-by-exception only
- reference is human-first unless the active phase names it
- completed plans and specs do not remain in the active tree

## Naming Rules

- use stable noun-based filenames for canonical docs
- avoid date prefixes outside archive material
- reserve dated names for historical records
- avoid creating a new permanent file for each implementation change

## Canonical Source Rules

- one domain may have only one canonical source
- secondary references may support but may not redefine canonical ownership
- frozen legacy docs may describe migration state but may not act as authority
- archive docs may preserve history but may not act as authority

## AI Readability Rules

- active AI context must stay small and bounded
- default AI context is limited to the six-file stack in `docs/AI-ENTRY.md`
- archive is excluded from default context
- frozen legacy directories are human reference only unless explicitly named
- AI prompts must declare current phase, allowed scope, forbidden scope, and unresolved debt

## Narrative Reduction Rules

- prefer matrices, ownership tables, lifecycle maps, capability maps, and governance maps over long prose
- do not create multiple narrative markdown files for one implementation change
- if a narrative file becomes canonical, reduce nearby narrative files to reference or archive
