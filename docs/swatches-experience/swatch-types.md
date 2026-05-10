# Swatch 类型（text / color / image）

## text

- 适用于：尺码、包装、容量等。
- SSR：`swatch-button.php`。
- 状态：`.is-selected`、`.is-unavailable`（对应 `<option disabled>`）。

## color

- 适用于：颜色属性；支持 **solid** 与 **dual**（`tailwindscore_swatch_color` + `tailwindscore_swatch_color_secondary`）。
- 选中强调：**细环** `box-shadow: 0 0 0 2px`（非大面积阴影）。
- SSR：`swatch-color.php`；色值通过内联 `--ts-swatch-color` 传入。

## image

- 适用于：纹理、材质、图案；使用 `woocommerce_gallery_thumbnail` 尺寸生成 `src` / `srcset`。
- 无附件 ID 时显示占位块，仍保留 `data-value` 以参与 WC 逻辑。

## 组件路径

- PHP：`template-parts/components/swatches/`
- CSS：`src/css/components/swatches/`
- TS：`src/ts/modules/swatches/`（由 `tailwindscore-variation-runtime` 挂载）
