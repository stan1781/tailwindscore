# Swatch 展示优先级（presentation priority）

本主题通过 **`tailwindscore_swatch_resolve_taxonomy_term_visual()`**（`swatch-image-resolver.php`）解析「词条级」视觉层，顺序如下：

| 优先级 | 含义 | 数据来源（Term meta / WC） |
| --- | --- | --- |
| **1** | Variation swatch image（专用缩略图） | `tailwindscore_swatch_variation_image`（attachment ID） |
| **2** | Variation featured image | 当前 variable 下，**首个**匹配该 `attribute_* = term slug` 且 `image_id` 非空的子变体 |
| **3** | Attribute term image | `tailwindscore_swatch_image` |
| **4** | Color swatch | `tailwindscore_swatch_color` / `_secondary` |
| **5** | Text fallback | 无图无色 → `text` 类型按钮 |

## 与 Presentation Mapping 的关系

默认 **`presentation_map` 为空** 且 **`image_attributes` 为空** 时，所有属性均为 **文本按钮**。指定 **`tailwindscore/swatches/image_attributes`** 后，列表内属性为「图片优先 → 无图则颜色」，其余仍为文本（见 `attribute-guidelines.md`）。

## 不支持

- 在主题内按属性 **slug 字符串**（如含 color）自动判定类型——已移除；必须通过 filter 或词条 meta 声明。
- 替换 WooCommerce `<option>` / variation JSON。
