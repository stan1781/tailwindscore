# Theme Configuration Rules

TailwindScore 的配置层不是传统的 “Theme Options Framework”。

它是一个带边界的 theme configuration architecture，目标是长期维护，而不是无限开放。

## 1. Core Principle

Theme configuration 只能落在 3 个治理层：

1. `Layer 1 - Design Tokens`
2. `Layer 2 - Commerce Behaviors`
3. `Layer 3 - Content Surfaces`

任何新配置在进入主题前，必须先回答：

- 它属于哪一层
- 它是否会破坏现有组件边界
- 它是否会制造 scattered settings
- 它是否能在不复制组件系统的前提下长期维护

如果答案不清晰，不进入架构。

## 2. Layer Boundaries

### Layer 1 - Design Tokens

开放范围：

- `colors`
- `typography`
- `spacing`
- `radius`
- `motion`
- `layout width`
- `density`
- `shell spacing`

原则：

- token-first customization
- 通过 CSS custom properties / Tailwind token bridge 输出
- 不提供 component-specific styling panels

### Layer 2 - Commerce Behaviors

有限开放：

- `sticky_header`
- `sticky_add_to_cart`
- `cart_drawer_behavior`
- `archive_density`
- `gallery_layout_mode`
- `mobile_shell_behavior`

原则：

- controlled behavioral customization
- 值必须是 enum / boolean / bounded range
- 行为由主题预先定义，不允许用户拼装组件结构

禁止：

- arbitrary component manipulation
- per-component layout toggles
- visual-builder style composition

### Layer 3 - Content Surfaces

统一通过 content surface registry 管理：

- announcement content
- trust messaging
- footer copy
- social links
- empty state language
- newsletter copy
- guarantee text
- support messaging

原则：

- centralized content governance
- 一个语义 surface 只有一个 canonical entry
- SSR helper 只能读取 registry，不直接散落读取 theme mods

禁止：

- scattered theme options
- duplicated text settings
- plugin-style admin pages

## 3. Kirki Governance

Kirki 在 TailwindScore 中只允许作为 configuration transport layer。

它可以做：

- 将 token 写入 CSS variables
- 将受控行为字段写入单一 setting
- 将 content surface 值写入 registry 对应 entry

它不可以做：

- UI builder system
- Elementor philosophy
- giant option trees
- per-component styling panels

Kirki panel 的存在不能反向定义主题架构。

## 4. New Setting Admission Rules

新增配置前先检查：

1. 这是 token、behavior、还是 content surface
2. 有没有现成 surface / token / behavior key 可以复用
3. 是否会产生第二个语义重复入口
4. 不启用该配置时，SSR 默认体验是否仍然完整
5. 该配置是否能写进治理文档，而不是靠约定俗成

只有通过以上检查，才允许新增。

## 5. Unsupported Direction

以下方向不进入 TailwindScore：

- classic theme options dumping ground
- component-by-component visual styling UI
- multiple disconnected admin pages
- different visual personalities living inside one theme
- template-level layout forks as “options”

## 6. Code-Level Source of Truth

治理边界由以下代码文件表达：

- `inc/configuration/governance.php`
- `inc/content-surfaces/registry.php`

这两个文件优先于任何 UI transport。
