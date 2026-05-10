# Hardcoded String Detection

需要重点检查的风险：

- template 内 reassurance copy
- empty state message inline text
- secondary action label drift
- search/discovery helper text
- checkout/payment helper text

## Detection Heuristics

优先扫描：

- `esc_html_e(...)`
- `esc_html__(...)`
- `__(...)`
- `sprintf(...)` with translatable prose
- `wc_print_notice(...)`

## Allowed vs Review

通常允许：

- structural labels
- field labels
- ARIA labels
- native navigation labels

必须 review：

- helper paragraphs
- reassurance text
- empty-state guidance
- discovery copy
- support language
