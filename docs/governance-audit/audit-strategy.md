# Audit Strategy

Governance audit 的目标：

- 找出未消费 registry 的 surface
- 找出 duplicated fallback logic
- 找出 hardcoded reassurance language
- 找出 tone leakage

当前优先顺序：

1. account
2. checkout
3. search
4. remaining commerce support surfaces

## Audit Standard

每个 surface 都要回答：

- 是否 registry-driven
- 是否 SSR-safe
- 是否 tone-governed
- 是否 localization-safe

如果其中一项不能回答为是，它就进入 audit backlog。
