# Preset Boundaries

Preset 边界必须非常清晰。

## Supported

- design tokens
- spacing rhythm
- motion rhythm
- density
- shell pacing
- content mood

## Unsupported

- component structure
- WooCommerce logic
- archive architecture
- cart behavior
- checkout runtime
- interaction patterns
- runtime-specific forks
- separate markup systems

## Architectural Meaning

Preset 可以改变“气质”，不能改变“系统”。

也就是说：

- 同一 preset family 共享同一 SSR 模板
- 共享同一前端 runtime
- 共享同一 WooCommerce integration
- 共享同一 component API

如果某个需求需要改组件结构，它就不是 preset。
