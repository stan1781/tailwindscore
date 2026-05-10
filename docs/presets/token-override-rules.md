# Token Override Rules

Preset override pipeline 只能覆盖受支持的 token 与节奏变量。

## 1. Override Pipeline

当前方向：

`preset registry -> preset loader -> SSR inline CSS variables -> existing stylesheet`

这保证：

- SSR-first preset application
- 无需 duplicated CSS bundles
- 无需 client-only preset switching

## 2. Allowed Variable Targets

允许覆盖：

- `--ts-color-*`
- `--ts-space-*`
- `--ts-radius-*`
- `--ts-shadow-*`
- `--ts-duration-*`
- `--ts-ease-*`
- `--ts-grid-*`
- `--ts-container-*`
- other governed `--ts-*` shell pacing variables

## 3. Disallowed Targets

禁止：

- component class rules
- WooCommerce markup hooks
- JS runtime flags
- bespoke page-level structural CSS

## 4. Loader Rule

Preset loader 只负责：

- 读取 active preset
- 校验 fallback
- 生成 variable overrides
- 将 overrides 挂到主 stylesheet

它不负责：

- 动态切换 bundle
- 生成 alternate template trees
- 注入 component-specific CSS panels
