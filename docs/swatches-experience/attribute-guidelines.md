# 属性映射指南（两种展示方案）

## 推荐：`image_attributes`（两种方案）

业务上通常只需两种 Variation 属性展示：

| 方案 | 条件 | 行为 |
| --- | --- | --- |
| **图片优先** | 属性 slug 出现在 **`tailwindscore/swatches/image_attributes`** | 按 **presentation-priority.md** 解析（变体图 → term 图 → 色块兜底） |
| **文本按钮** | **未**列入上述列表 | 仅文本 Swatch |

在子主题 `functions.php`：

```php
add_filter(
	'tailwindscore/swatches/image_attributes',
	static function (): array {
		return array(
			'pa_color',
			'pa_material',
		);
	}
);
```

属性名须与 WooCommerce 一致（如全局属性 `pa_color`，自定义属性则为对应 slug）。

## 可选：`presentation_map`（单属性覆盖）

对某一属性需要 **`color` / `text` / `auto`** 等特例时，使用 **`tailwindscore/swatches/presentation_map`**；**同一属性若同时配置**，**presentation_map 优先于 image_attributes**。

## 词条级覆盖

词条 meta **`tailwindscore_swatch_display`** 仍可覆盖单个 term 的展示类型。

## 禁止行为

主题 **不**根据属性名称子串（如包含 `color`）推断类型。

## 门店迁移提示

未配置 **`image_attributes`** 且未写 **`presentation_map`** 时，属性默认为 **文本按钮**。需要颜色/材质大图展示的，务必加入 **`image_attributes`** 列表。
