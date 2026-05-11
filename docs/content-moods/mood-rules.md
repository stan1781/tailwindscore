# Mood Rules

## Mood Ownership Matrix

| Mood Concern | Required Field / Rule | Canonical Owner |
| --- | --- | --- |
| identity | `key`, `label`, `description` | local reference only |
| supported usage | `supported surfaces` | `docs/content-surfaces/content-surface-rules.md` |
| tone constraints | `tone rules` | local reference only |
| fallback | `fallback behavior` | content governance |
| localization | `localization compatibility` | localization reference |

## Tone Compatibility Map

| Allowed Tone Traits | Prohibited Tone Traits |
| --- | --- |
| calm | pushy |
| assured | slang-heavy |
| legible across locales | novelty-first |
| commercially useful without pressure | AI-assistant-like |
| premium without self-importance | overexcited |

## Supported Surface Table

| Surface Family | Mood Governance Status |
| --- | --- |
| announcement language | supported |
| trust messaging | supported |
| empty states | supported |
| support messaging | supported |
| newsletter prompts | supported |
| footer messaging | supported |
| checkout reassurance | supported |
| account messaging | supported |
| search guidance | supported |
