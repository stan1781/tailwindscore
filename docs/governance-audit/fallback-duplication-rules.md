# Fallback Duplication Rules

Fallback 必须集中。

禁止：

- template 内再写第二套默认文案
- helper 内绕开 content surface registry
- preset-specific hardcoded copy
- mood-specific branching直接出现在模板里

## Correct Pattern

统一路径：

`content surface -> content mood fallback -> preset mapping -> SSR output`

模板只消费最终结果，不再自己判断 fallback。
