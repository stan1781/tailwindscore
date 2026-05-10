# Button（`.ts-btn`）

## 职责（Responsibility）

- 提供 **单一按钮交互面**：填充、边框、圆角、阴影、焦点环、加载与禁用。
- 与 **购物车 / 结账 CTA** 共用同一套视觉语言（高级感、较大幅度圆角、克制微动效）。

## 边界（Boundary）

- **不包含**业务文案决策（由 PHP `template-parts/components/button.php` 传入 label）。
- **不包含**路由或 AJAX；加载态仅表达 **进行中**，具体请求由调用方 TS 模块设置/清除。
- 图标按钮仅负责 **尺寸与对齐**；SVG 路径由调用方提供。

## DOM 契约（SSR）

推荐结构：

```html
<button type="button" class="ts-btn ts-btn--primary">
  <span class="ts-btn__label">Add to cart</span>
</button>

<button type="button" class="ts-btn ts-btn--primary ts-btn--loading" aria-busy="true" disabled>
  <span class="ts-btn__label">Adding…</span>
</button>

<a href="#" class="ts-btn ts-btn--secondary">Continue shopping</a>

<button type="button" class="ts-btn ts-btn--ghost ts-btn--icon" aria-label="Close">
  <svg class="ts-btn__icon" aria-hidden="true">...</svg>
</button>
```

## Variants

| Class | 用途 |
|-------|------|
| `.ts-btn--primary` | 主 CTA（accent 填充） |
| `.ts-btn--secondary` | 次级（surface + 细边框） |
| `.ts-btn--ghost` | 文本型 / 工具栏 |
| `.ts-btn--sm` / `.ts-btn--lg` | 尺寸 |
| `.ts-btn--icon` | 图标正方形按钮 |
| `.ts-btn--loading` | 加载 spinner（配合 `aria-busy`） |

## States

- **Hover / Active**：轻微位移与阴影抬升（尊重 `prefers-reduced-motion`）。
- **Disabled**：`disabled` 或 `aria-disabled="true"` + 降低透明度。
- **Focus**：`:focus-visible` 使用 accent 聚焦环（Token）。
- **Loading**：`.ts-btn--loading` 隐藏 label 可视、显示 spinner；应 **`pointer-events: none`**。

## Tokens

依赖 `--ts-color-accent*`、`--ts-radius-*`、`--ts-shadow-*`、`--ts-duration-*`、`--ts-ease-standard`。禁止在模板堆 utility 替代按钮肤层。

## WooCommerce 提示

Add to cart / Place order 应保持 **原生 `button`/`input[type=submit]`**；主题层包一层 `.ts-btn*` class（通过 PHP args 或 filter），勿替换 WC 表单语义。
