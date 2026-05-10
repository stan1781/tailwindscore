# Preset Mood Mapping

Preset 可以映射 content mood，但不能任意切换文案人格。

允许：

- mapped content mood
- supported tone surfaces
- tone intensity
- commerce language pacing

禁止：

- arbitrary copy switching
- unrelated brand personalities
- inconsistent tone systems

## Current Mapping Direction

- `minimal-editorial` -> `editorial`
- `premium-dtc` -> `premium-commerce`
- `soft-luxury` -> `assured`
- `modern-lifestyle` -> `confident`
- `dark-commerce` -> `dramatic`

运行时上，preset 只提供 mood mapping；具体 surface fallback 仍通过 content mood registry 统一治理。
