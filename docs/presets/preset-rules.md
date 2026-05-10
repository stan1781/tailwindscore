# Preset Rules

TailwindScore preset 不是 theme skin system。

它是 governed commerce mood preset。

## 1. Core Rule

Preset 只能在同一 design system 内做 mood variation。

允许控制：

- design tokens
- spacing rhythm
- motion rhythm
- density
- shell pacing
- content mood

禁止：

- component duplication
- layout rewrites
- runtime divergence
- interaction model changes

## 2. Required Preset Contract

每个 preset 必须包含：

- `key`
- `label`
- `description`
- `token_overrides`
- `spacing_rhythm_overrides`
- `motion_intensity_overrides`
- `density_overrides`
- `shell_mood_overrides`

可选补充：

- `content_mood_overrides`

## 3. Source Of Truth

Preset registry 的唯一真实来源：

- `inc/presets/registry.php`

Preset 加载与 fallback：

- `inc/presets/loader.php`

## 4. Governance Outcome

最终目标是 stable preset governance foundation，而不是多风格主题市场。
