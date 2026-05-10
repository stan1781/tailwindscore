# Token Governance

TailwindScore 只开放 token，不开放任意组件样式。

## 1. Token-First Rule

允许自定义的方向：

- `colors`
- `typography`
- `spacing`
- `radius`
- `motion`
- `layout width`
- `density`
- `shell spacing`

所有视觉变化必须先落到 token，再由组件消费 token。

禁止：

- 直接给单个组件开放颜色面板
- 直接给单个组件开放圆角面板
- 给模板局部写独立 spacing overrides

## 2. Flow

配置流向必须保持一致：

`Customizer / Kirki transport -> governed token setting -> CSS custom properties -> component styles`

不允许出现第二套平行命名。

## 3. Allowed Change Shape

允许：

- primitive token variation
- semantic token remapping
- density rhythm adjustments
- shell spacing adjustments

不允许：

- custom component CSS generator
- ad hoc hex value injection per block
- preset bypass through template markup changes

## 4. Maintenance Standard

每个 token 变更都要满足：

- 可回退到默认 preset
- 不要求复制组件 CSS
- 不创建新组件人格
- 不破坏 design-system 文档中的层级关系

## 5. Kirki Rule

Kirki 只覆盖 `--ts-*` 变量。

禁止 Kirki 生成大段针对组件类名的覆盖规则。
