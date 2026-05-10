# Swatches — 无障碍（Accessibility）

## 模式

- **Swatch 按钮**：`role="radio"` + `aria-checked` + `aria-disabled`（与 `<option disabled>` 同步）。
- **分组**：`role="radiogroup"` + `aria-label`（使用 `wc_attribute_label()` 文案）。
- **原生 `<select>`**：保留在 DOM 中，默认 **visually hidden**；**`:focus-within`** 时取消隐藏，便于仅键盘用户聚焦系统控件或调试。

## 键盘

- **Tab**：进入组时 roving `tabindex` 只保留一个 `tabindex="0"`（当前选中项或第一个可用项）。
- **方向键**：在可用选项间移动焦点。
- **Home / End**：第一个 / 最后一个可用项。
- **Enter / Space**：激活当前项（等同点击）。

## 与屏幕阅读器

- 优先通过 **radiogroup + radio** 播报选项；隐藏 select 仍参与部分 SR 的「表单控件」路径，`:focus-within` 展开可降低与插件冲突风险。

## 未覆盖场景

- 若第三方脚本强制显示 select 并隐藏 swatch，需自行保证不重复播报。
