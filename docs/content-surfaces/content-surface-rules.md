# Content Surface Rules

TailwindScore 使用 content surface registry system，而不是零散 options。

## 1. Required Surface Contract

每个 content surface 必须包含：

- `key`
- `fallback`
- `sanitizer`
- `translation support`
- `SSR output`
- `customization entry`

缺少其中任一项，不视为正式 surface。

## 2. Why Registry First

Registry 的意义：

- 防止 duplicated text settings
- 防止同一语义出现多个编辑入口
- 让 SSR / runtime / docs 使用同一个 canonical key
- 为 Kirki 只做 transport 提供边界

## 3. Surface Types

当前优先支持：

- plain text
- textarea copy
- rich text
- structured arrays such as social links

所有类型都必须有明确 sanitizer。

## 4. Output Rules

surface 只负责内容，不负责组件结构。

这意味着：

- 文案通过 SSR helper 注入既有模板
- 不通过 surface 决定组件树
- 不通过 surface 注入任意布局控制

## 5. Customization Entry Rules

每个 surface 的 customization entry 只描述：

- transport
- setting id
- group
- section
- control type

它不是 UI architecture 本身。

Kirki / Customizer 只能读取这个 entry 去挂载 transport。

## 6. Empty State Governance

empty state language 统一纳入 registry。

规则：

- 每个 context 使用 canonical key
- `eyebrow / title / message` 分别治理
- fallback copy 必须可 SSR 直接输出
- 不允许页面内散写不同版本的同义文案

## 7. Social Links Governance

social links 是 content surface，不是 menu duplication。

适用场景：

- 没有 `footer_social` menu 时的 governed fallback
- 需要品牌化 icon + label 输出时

如果项目明确需要编辑型导航，优先使用 menu；如果需要结构化品牌社媒入口，使用 governed surface。
