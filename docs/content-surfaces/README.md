# Content Surfaces

`content surfaces` 是 TailwindScore 对主题文案与结构化内容的统一治理层。

它们不是 scattered theme options，也不是插件式设置页。

## Scope

统一管理：

- announcement content
- trust messaging
- footer copy
- social links
- empty state language
- newsletter copy
- guarantee text
- support messaging

## Source of Truth

- runtime registry: `inc/content-surfaces/registry.php`
- sanitizer layer: `inc/content-surfaces/sanitizers.php`
- governance rules: `content-surface-rules.md`

任何新文案设置都应先判断它是不是一个 content surface。
